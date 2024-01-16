<?php

namespace App\Providers;

use App\Events\Auth\AttemptLock;
use App\Listeners\Auth\CheckLocked;
use App\Listeners\Auth\ReleaseLock;
use App\Listeners\Auth\ValidateFailedAttempt;
use App\Listeners\Auth\ValidateUserActive;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Validated;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Validated::class => [
            ValidateUserActive::class,
        ],
        AttemptLock::class => [
            CheckLocked::class,
        ],
        Failed::class => [
            ValidateFailedAttempt::class,
        ],
        Login::class => [
            ReleaseLock::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
