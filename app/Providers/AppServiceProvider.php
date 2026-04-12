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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // \App\Models\ProductoVariante::observe(\App\Observers\ProductoVarianteObserver::class);

        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $cartCount = 0;
            if (auth()->check()) {
                $carrito = \App\Models\Orden::where('user_id', auth()->id())
                    ->where('estado', 'carrito')
                    ->first();
                if ($carrito) {
                    $cartCount = $carrito->detalles()->count();
                }
            }
            $view->with('globalCartCount', $cartCount);
        });
    }
}
