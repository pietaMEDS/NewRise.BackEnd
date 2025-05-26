<?php

namespace App\Providers;

use App\Models\achieve;
use App\Models\User;
use App\Observers\AchieveObserver;
use App\Observers\UserObserver;
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
        achieve::observe(AchieveObserver::class);
        User::observe(UserObserver::class);
    }
}
