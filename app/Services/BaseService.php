<?php

namespace App\Services;

abstract class BaseService
{
    public static function getAuthModelFromProvider(string $provider)
    {
        $model = config("auth.providers.$provider.model");

        if (! $model) {
            $providers = config('auth.providers');
            $model = $providers[array_key_first($providers)]['model'];
        }

        return $model;
    }
}
