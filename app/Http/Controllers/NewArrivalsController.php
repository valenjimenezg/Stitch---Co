<?php

namespace App\Http\Controllers;

use App\Models\ProductoVariante;

class NewArrivalsController extends Controller
{
    public function index()
    {
        // Fetch the most recent product variants
        $variantes = ProductoVariante::with('producto')
            ->whereNull('parent_id') // Avoid repeating packaging rows
            ->latest()
            ->paginate(12);

        return view('novedades.index', compact('variantes'));
    }
}
