<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // under any circumstances that we change the files for the user's component file
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Blade::component('components.dropdown-comp', 'dropdown-comp');

    }
}
