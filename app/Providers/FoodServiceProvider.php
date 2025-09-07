<?php

namespace App\Providers;

use App\Models\Food;
use Illuminate\Support\ServiceProvider;

class FoodServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(Food::class, function ($app) {
            return new Food();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
