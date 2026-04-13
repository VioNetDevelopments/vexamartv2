<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use App\Models\GuestCart;
use Illuminate\Support\Facades\View;

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
        View::composer('*', function ($view) {
            $view->with('settings', Setting::allAsArray());
            
            // Share cart count globally for guest sessions
            $cartCount = 0;
            if (session()->getId()) {
                $cartCount = GuestCart::where('session_id', session()->getId())->sum('quantity');
            }
            $view->with('cartCount', $cartCount);
        });
    }
}
