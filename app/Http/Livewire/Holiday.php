<?php

namespace App\Http\Livewire;

use App\Models\Booking;
use App\Models\Holiday as Holidays;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;


class Holiday extends Component
{
    use AuthorizesRequests;

    /**
     * Array of arrays of Carbon objects:
     *  [start-date, end-date]
     * where start-date == end-date for 1-day holidays
     *  */ 
    public $holidays = [];

    public $from;
    public $until;
    public $bookings_exist = false;
    public $holiday_overlap = false;

    protected $rules = [
        'from' => 'required|date',
        'until' => 'nullable|date',
    ];

    protected $listeners = [
        'rangeDeleted' => 'deleteRange',
    ];

    public function deleteRange($index)
    {
        unset($this->holidays[$index]);
    }

    public function mount(Request $request)
    {
        $this->fetchHolidays($request);
    }

    public function render()
    {
        return view('livewire.holidays');
    }

    public function updated()
    {
        $this->validate();

        $bookings = Booking::
            whereDate('day', '>=', $this->from)
            ->whereDate('day', '<=', $this->until ?? $this->from)
            ->get();

        if(count($bookings)) {
            $this->addError('bookings', 'There are pending bookings in that range');
            $this->bookings_exist = true;
        }
        else $this->bookings_exist = false;

        $holiday_overlap = Holidays::
            whereDate('day', '>=', $this->from)
            ->whereDate('day', '<=', $this->until ?? $this->from)
            ->get();

        if(count($holiday_overlap)) {
            $this->addError('holiday', 'Overlaping holiday plans!');
            $this->holiday_overlap = true;
        }
        else $this->holiday_overlap = false;

        return $this->bookings_exist || $this->holiday_overlap;

    }

    public function submit(Request $request)
    {
        if($this->updated()) return;
        /*
        if($this->bookings_exist) {
            $this->addError('bookings', 'There are bookings present in that range');
            return;
        }

        if($this->holiday_overlap) {
            $this->addError('holiday', 'Overlaping holiday plans!');
            return;
        }

        $this->validate();
        */

        if(! Gate::allows('admin-only')) {
            return response('', 403);
        }

        
        $from = Carbon::create($this->from);
        $until = $this->until ? Carbon::create($this->until) : $from;

        // Push new values into array for livewire to re-render table
        array_push($this->holidays, [
            $from->format('d/m/Y'), 
            $until->format('d/m/Y')
        ]);

        if($until->lte($from)) {
            $request->user()->holidays()->create([ 'day' => $from ]);
        } else {

            while($from->lte($until)) {
                
                $request->user()->holidays()->create([ 'day' => $from ]);
                $from->addDay();
            }
        }

        session()->flash('message', 'Holiday saved');

    }

    public function fetchHolidays(Request $request)
    {
        $dates = Holidays::where('user_id', $request->user()->id)
            ->whereDate('day', '>', 'now')
            ->oldest('day')
            ->get();
        
        if(! count($dates)) return;

        // Walk the array to find holiday groups
        /**
         * Holiday options:
         *  1 day single holiday
         *  multiple 1 day holidays
         *  multi-day holidays
         */
        $i = 0;
        $start = $dates[$i]->day;
        while($i < count($dates))
        {
            $current = $dates[$i]->day;

            $next = $dates[$i+1]->day ?? null;

            if(! $next) array_push($this->holidays, [
                $start->format('d/m/Y'), 
                $current->format('d/m/Y')
            ]);

            elseif($current->diffInDays($next) > 1)
            {
                array_push($this->holidays, [
                    $start->format('d/m/Y'), 
                    $current->format('d/m/Y')
                ]);
                $start = $next;
            }

            $i++;
        }

        $this->sort($this->holidays);

    }

    public function sort($array)
    {
        usort($array, function($a, $b) {
            $params1 = explode('/', $a[0]);
            $params2 = explode('/', $b[0]);

            $first = Carbon::createFromDate(
                $params1[2], $params1[1], $params1[0], env('TIMEZONE'));
            $second = Carbon::createFromDate(
                $params2[2], $params2[1], $params2[0], env('TIMEZONE'));

            if($first->lt($second)) return -1;
            if($first->eq($second)) return 0;
            if($first->gt($second)) return 1;
        });

    }
    
    public function resetUntil() 
    {
        $this->until = null;
    }
}
