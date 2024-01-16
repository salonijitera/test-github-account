<?php

namespace App\Listeners\Auth;

use App\Events\Auth\AttemptLock;

class CheckLocked
{
    public function handle(AttemptLock $event): void
    {
        if (! $event->user) {
            abort(404, 'auth.failed');
        }
        if (method_exists($event->user, 'isLocked')) {
            if ($event->user->isLocked()) {
                abort(403, 'auth.locked_account');
            }
        }
    }
}
