<?php

namespace App\Supports\Facades;

use Illuminate\Support\Facades\Facade;

class StorageSupportFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'StorageSupport';
    }
}
