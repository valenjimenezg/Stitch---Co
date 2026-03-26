<?php

namespace App\Http\Controllers;

use App\Models\DetalleProducto;

class HomeController extends Controller
{
    public function index()
    {
        $destacados = DetalleProducto::with('producto')
            ->latest()
            ->take(4)
            ->get();

        $ofertas = DetalleProducto::with('producto')
            ->where('en_oferta', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('home', compact('destacados', 'ofertas'));
    }
}
