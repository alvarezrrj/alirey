<?php

namespace App\Http\Livewire;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\Component;

class HolidayRange extends Component
{
    public $range;

    public function render()
    {
        return view('livewire.holiday-range');
    }

    public function delete(Request $request)
    {
        $params1 = explode('/', $this->range[0]);
        $params2 = explode('/', $this->range[1]);

        $first = Carbon::createFromDate(
            $params1[2], $params1[1], $params1[0], env('TIMEZONE'));
        $second = Carbon::createFromDate(
            $params2[2], $params2[1], $params2[0], env('TIMEZONE'));

        Holiday::where('user_id', $request->user()->id)
                ->whereDate('day', '>=', $first)
                ->whereDate('day', '<=', $second)
                ->delete();

        //$this->hidden = 'hidden';
        //$this->deleted = true;

        $this->emit('rangeDeleted');
    }
}
