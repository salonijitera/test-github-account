<?php

namespace App\Providers;

use App\Supports\Facades\StorageSupport;
use Illuminate\Support\ServiceProvider;

class StorageSupportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('StorageSupport', function () {
            return new StorageSupport();
        });
    }
}
