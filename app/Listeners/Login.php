<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class Login
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;
        $roles = $user->roles;
        if ($roles->count()) {
            $primary_role = $user->roles()->where('is_primary', 1)->first();

            session(['roles'=> $roles]);
            session(['role'=> $primary_role]);
            session(['role_id'=> $primary_role->id]);
        }

        $user->last_login = now();
        $user->save();
    }
}
