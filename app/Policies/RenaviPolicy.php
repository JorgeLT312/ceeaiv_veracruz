<?php

namespace App\Policies;

use App\Models\Renavi;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RenaviPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // REV
        return in_array($user->area_id, [3]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Renavi $renavi): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Renavi $renavi): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Renavi $renavi): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Renavi $renavi): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Renavi $renavi): bool
    {
        //
    }
}
