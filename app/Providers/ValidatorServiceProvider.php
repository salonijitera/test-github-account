<?php

namespace App\Providers;

use App\Rules\Hiragana;
use App\Rules\Katakana;
use App\Rules\PasswordWithPattern;
use App\Rules\PhoneNumber;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
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
        Validator::extend('in_past', function ($attribute, $value, $parameters) {
            $currentDate = data_get($parameters, '0') == 'datetime' ? Carbon::now() : Carbon::today();

            return Carbon::parse($value)->isBefore($currentDate);
        });

        Validator::extend('in_future', function ($attribute, $value, $parameters) {
            $currentDate = data_get($parameters, '0') == 'datetime' ? Carbon::now() : Carbon::today();

            return Carbon::parse($value)->isAfter($currentDate);
        });

        Validator::extend('hiragana', function ($attribute, $value) {
            return (new Hiragana())->passes($attribute, $value);
        });

        Validator::extend('katakana', function ($attribute, $value) {
            return (new Katakana())->passes($attribute, $value);
        });

        Validator::extend('phone_number', function ($attribute, $value) {
            return (new PhoneNumber())->passes($attribute, $value);
        });

        Validator::extend('password_with_pattern', function ($attribute, $value) {
            return (new PasswordWithPattern())->passes($attribute, $value);
        });
    }
}
