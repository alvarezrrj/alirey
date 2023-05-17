<?php

namespace App\Http\Livewire;

use App\Models\Config;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Anticipation extends Component
{
    public $config;

    protected $rules = [
        'config.allways_open' => 'required|boolean',
        'config.anticipation' => 'required|integer',
        'config.open_until' => 'required|date'
    ];

    public function mount()
    {
        $this->config = Config::where('user_id', Auth::user()->id)->first();
    }

    public function render()
    {
        return view('livewire.config.anticipation');
    }

    public function updated()
    {
        $this->validate();
        $this->config->save();
    }
}
