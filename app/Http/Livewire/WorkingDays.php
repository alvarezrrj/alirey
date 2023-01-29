<?php

namespace App\Http\Livewire;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WorkingDays extends Component
{
    public $working_days;

    protected $rules = [
        'working_days.*' => 'integer|min:0|max:1',
    ];

    public function mount()
    {
        $this->working_days = str_split(Config::where('user_id', Auth::user()->id)->first()->working_days);
        // Cast all values to integer
        foreach($this->working_days as &$day) $day = (int) $day;
    }

    public function render()
    {
        return view('livewire.working-days', [
            'days' => $this->working_days,
        ]);
    }

    public function updated() 
    {
        $this->validate();

        // Cast all values to integer
        //foreach($this->working_days as &$day) $day = (int) $day;

        $config = Config::where('user_id', Auth::user()->id)->first();
        $config->working_days = implode($this->working_days);
        $config->save();
    }
}
