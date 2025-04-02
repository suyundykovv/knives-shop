<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
        // Set Vite prefetch concurrency
        Vite::prefetch(concurrency: 3);

        // Share isAdmin variable with all views
        View::composer('*', function ($view) {
            $view->with('isAdmin', Auth::check() ? Auth::user()->is_admin : false);
        });
    }
}
