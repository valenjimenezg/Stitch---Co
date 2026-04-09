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
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $cartCount = 0;
            if (auth()->check()) {
                $carrito = \App\Models\Carrito::where('user_id', auth()->id())
                    ->where('estado', 'pendiente')
                    ->first();
                if ($carrito) {
                    $cartCount = $carrito->detalles()->count();
                }
            } else {
                // Si usan un carrito en sesión (para invitados futuros)
                $sessionId = session()->getId();
                // Acepta otras lógicas; por default 0 si no hay guest carts
            }
            $view->with('globalCartCount', $cartCount);
        });
    }
}
