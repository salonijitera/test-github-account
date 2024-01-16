<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class ValidateUserActive
{
    public function handle(Validated $event): void
    {
        if ($event->user instanceof MustVerifyEmail && ! $event->user->hasVerifiedEmail()) {
            abort(403, __('auth.email_unconfirmed_account'));
        }
    }
}
