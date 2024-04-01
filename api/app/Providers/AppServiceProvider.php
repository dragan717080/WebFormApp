<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\{ Passport, Client, RefreshToken, Token, AuthCode };

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
        //
    }
}
