<?php

namespace App\Policies;

use App\User;
use App\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any roles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('roles_view');
    }

    /**
     * Determine whether the user can view the role.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Role  $role
     * @return mixed
     */
    public function view(User $user, Role $role)
    {
        return $user->hasPermission('roles_view');
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('roles_create');
    }

    /**
     * Determine whether the user can update the role.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Role  $role
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        return $user->hasPermission('roles_update');
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Role  $role
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        return ! $role->is_protected && $user->hasPermission('roles_delete');
    }
}
