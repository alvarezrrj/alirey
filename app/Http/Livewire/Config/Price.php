<?php

namespace App\Http\Livewire\Config;

use App\Models\Config;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Price extends Component
{
    public $config;

    protected $rules = [
        'config.price' => 'required|integer|min:0',
    ];

    public function mount()
    {
        $this->config = Config::where('user_id', Auth::user()->id)->first();
    }

    public function render()
    {
        return view('livewire.config.price');
    }

    public function updated()
    {
        $this->validate();
        $this->config->save();
    }
}
