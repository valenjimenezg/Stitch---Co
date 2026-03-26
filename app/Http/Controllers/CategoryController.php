<?php

namespace App\Http\Controllers;

use App\Models\DetalleProducto;

class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $variantes = DetalleProducto::with('producto')
            ->whereHas('producto', fn($q) => $q->where('categoria', $slug))
            ->paginate(12);

        return view('categories.show', compact('variantes', 'slug'));
    }
}
