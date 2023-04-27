<?php

namespace App\Policies;

use App\models\User;
use Illuminate\Auth\Access\Response;

class UsersPolicy
{
    /**
     * Determine whether the user can view any clients.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the client.
     */
    public function view(User $user, User $client): bool
    {
        return $this->delete($user, $client);
    }

    /**
     * Determine whether the user can create clients.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the client.
     */
    public function update(User $user, User $client): bool
    {
        return $this->delete($user, $client);
    }

    /**
     * Determine whether the user can delete the client.
     */
    public function delete(User $user, User $client): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the client.
     */
    public function restore(User $user, User $client): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the client.
     */
    public function forceDelete(User $user, User $client): bool
    {
        //
    }
}
