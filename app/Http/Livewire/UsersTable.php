<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UsersTable extends Component
{
    use AuthorizesRequests;

    public $search = '';

    public function render()
    {
        return view('livewire.users-table', [
            'users' => User::where('id', '!=', Auth::user()->id)
                ->where(function ($query) {
                    $query->where('firstName', 'like', '%' . $this->search . '%')
                        ->orWhere('lastName', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orderBy('lastName', 'asc')
                ->get(),
        ]);
    }

    public function delete(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();
    }
}
