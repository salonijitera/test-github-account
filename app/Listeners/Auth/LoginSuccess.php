<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Login;

class LoginSuccess
{
    public function handle(Login $event): void
    {
        if (method_exists($event->user, 'loginSuccess')) {
            $event->user->loginSuccess();
        }
    }
}
