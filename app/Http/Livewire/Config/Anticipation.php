<?php

namespace App\Http\Livewire\Config;

use App\Models\Config;
use Illuminate\Http\Request;
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

    public function mount(Request $request)
    {
        $this->config = $request->user()->config;
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
