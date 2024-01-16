<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Login;

class ReleaseLock
{
    public function handle(Login $event): void
    {
        if (method_exists($event->user, 'releaseLock')) {
            $event->user->releaseLock();
        }
    }
}
