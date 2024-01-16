<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Failed;

class ValidateFailedAttempt
{
    public function handle(Failed $event): void
    {
        if (! $event->user) {
            return;
        }

        if (method_exists($event->user, 'loginFailed')) {
            $event->user->loginFailed();
        }
        abort(400, __('auth.failed'));
    }
}
