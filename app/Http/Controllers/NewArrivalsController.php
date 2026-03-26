<?php

namespace App\Http\Controllers;

use App\Models\DetalleProducto;

class NewArrivalsController extends Controller
{
    public function index()
    {
        // Fetch the 20 most recent product variants
        $variantes = DetalleProducto::with('producto')
            ->latest()
            ->paginate(12);

        return view('novedades.index', compact('variantes'));
    }
}
