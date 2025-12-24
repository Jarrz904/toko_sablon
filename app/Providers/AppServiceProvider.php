<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind vendor TrimStrings to our App TrimStrings implementation
        // so the framework will resolve \Illuminate\Foundation\Http\Middleware\TrimStrings
        // to \App\Http\Middleware\TrimStrings (defensive trim).
        $this->app->bind(
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
            \App\Http\Middleware\TrimStrings::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
    }
}