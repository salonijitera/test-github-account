<?php

namespace App\Listeners\Auth;

use App\Events\Auth\VerifyEmailFailed;

class FailedVerifyEmail
{
    public function handle(VerifyEmailFailed $event): void
    {
        if (! $event->user) {
            abort(400, __('auth.verify_email_token_invalid'));
        }
    }
}
