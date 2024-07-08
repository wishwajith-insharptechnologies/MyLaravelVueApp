<?php

namespace App\Providers;

use App\Rules\NotEmptyArray;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app['validator']->extend('not_empty_array', function ($attribute, $value, $parameters) {
            return app(NotEmptyArray::class)->passes($attribute, $value);
        });
    }
}
