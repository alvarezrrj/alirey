<?php

namespace App\Http\Livewire;

use App\Models\Config;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Anticipation extends Component
{
    public $config;

    protected $rules = [
        'config.allways_open' => 'boolean',
        'config.anticipation' => 'integer',
        'config.open_until' => 'date'
    ];

    public function mount() 
    {
        $this->config = Config::where('user_id', Auth::user()->id)->first();
    }

    public function render()
    {
        return view('livewire.anticipation');
    }

    public function updated()
    {
        $this->config->save();
    }
}
