<?php

namespace App\Http\Controllers;

use App\Models\ProductoVariante;

class HomeController extends Controller
{
    public function index()
    {
        // En el nuevo ERP, los 'Detalles' base son las variantes con parent_id nulo
        $destacados = ProductoVariante::with('producto')
            ->whereNull('parent_id')
            ->latest()
            ->take(4)
            ->get();

        $ofertas = ProductoVariante::with('producto')
            ->whereNull('parent_id')
            ->where('en_oferta', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('home', compact('destacados', 'ofertas'));
    }
}
