<?php

namespace App\Http\Livewire;

use App\Models\Slot as ModelsSlot;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Slot extends Component
{
    use AuthorizesRequests;

    public ModelsSlot $slot;
    //public $index;

    public function render()
    {
        return view('livewire.slot');
    }

    public function delete()
    {
        $this->authorize('delete', $this->slot);

        $this->slot->delete();

        $this->emit('slotDeleted');
    }
}
