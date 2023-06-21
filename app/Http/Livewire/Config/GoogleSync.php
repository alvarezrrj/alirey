<?php

namespace App\Http\Livewire\Config;

use App\Models\Config;
use Illuminate\Http\Request;
use Livewire\Component;

class GoogleSync extends Component
{
    public Config $config;
    public string $popup_url;

    protected $rules = [
        'config.google_sync' => 'required|boolean'
    ];

    public function mount(Request $request)
    {
        $this->config = $request->user()->config;
    }

    public function render()
    {
        return view('livewire.config.google-sync');
    }

    public function save()
    {
        $this->validate();

        if ($this->config->google_sync && ! $this->config->user->google_token) {
            // Open oAuth popup
            $this->popup_url = route('google.calendar.auth');
        } else {
            $this->config->save();
            $this->popup_url = '';
        }
    }
}
