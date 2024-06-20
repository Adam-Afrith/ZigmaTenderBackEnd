<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Holiday;

use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('unique_holiday_date', function ($attribute, $value, $parameters, $validator) {
            $count = Holiday::whereDate('date', $value)->count();
            return $count === 0; // Return true if the count is 0 (date is unique), otherwise return false.
        });
    
        Validator::replacer('unique_holiday_date', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, 'The selected :attribute is already taken.');
        });
    }
}
