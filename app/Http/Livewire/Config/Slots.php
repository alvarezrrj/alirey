<?php

namespace App\Http\Livewire\Config;

use App\Models\Slot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\Component;

class Slots extends Component
{
    //public $slots = [];
    public $slot;
    public $overlaps = false;
    public $invalid = false;

    protected $rules = [
        'slot.start' => 'required|regex:/\d\d:\d\d/',
        'slot.end' => 'required|regex:/\d\d:\d\d/',
    ];

    public $listeners = [
        'slotDeleted' => 'noop',
    ];

    public function render(Request $request)
    {
        return view('livewire.config.slots', [
            'slots' => $request->user()->config->slots()->oldest('start')->get(),
        ]);
    }

    public function submit(Request $request)
    {
        if($this->updated()) return;

        $request->user()->config->slots()->create($this->slot);

        session()->flash('message', 'Slot saved.');
    }

    public function updated()
    {
        $this->validate();

        // Find overlapping slots
        $overlaps = Slot::
              whereTime('start', '>=', $this->slot['start'])
            ->whereTime('start', '<=', $this->slot['end'])
            ->orWhere(function ($query) {
                $query->whereTime('end', '>=', $this->slot['start'])
                      ->whereTime('end', '<=', $this->slot['end']);
            })
            ->get();

        if(count($overlaps)) {
            $this->addError('overlaps', 'That selection creates overlapping slots');
            $this->overlaps = true;
        }
        else $this->overlaps = false;

        // Ensure end > start
        $params1 = explode(':', $this->slot['start']);
        $start = Carbon::createFromTime(
            $params1[0],
            $params1[1],
            0,
            config('app.timezone')
        );
        $params2 = explode(':', $this->slot['end']);
        $end = Carbon::createFromTime(
            $params2[0],
            $params2[1],
            0,
            config('app.timezone')
        );

        if($end->lte($start)) {
            $this->addError('invalid', '\'End\' must be later than \'start\'');
            $this->invalid = true;
        }
        else $this->invalid = false;

        return $this->overlaps || $this->invalid;
    }

    public function noop()
    {
        // Calling a component's action triggers a re-render,
        // This function is called by the event listener when
        // a slot is deleted to trigger a fresh render of the table
    }
}
