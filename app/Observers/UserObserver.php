<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    /**
     * Handle the user "creating" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function creating(User $user)
    {
        $user->password = bcrypt(request('password'));
    }

    /**
     * Handle the user "deleting" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleting(User $user)
    {
        $user->permissions()->detach();
    }
}
