<?php

namespace App\Policies;

use App\Models\PrimerContacto;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PrimerContactoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Primer contacto, foraneos, REV, Juridico
        return in_array($user->area_id, [2, 6, 3, 4]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PrimerContacto $primerContacto): bool
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
    public function update(User $user, PrimerContacto $primerContacto): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PrimerContacto $primerContacto): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PrimerContacto $primerContacto): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PrimerContacto $primerContacto): bool
    {
        //
    }
}
