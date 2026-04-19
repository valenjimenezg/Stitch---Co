<?php

namespace App\Http\Controllers;

use App\Models\ProductoVariante;

class OfferController extends Controller
{
    public function index()
    {
        $variantes = ProductoVariante::with(['producto.variantes', 'producto.categoria'])
            ->whereNull('parent_id')
            ->where('en_oferta', true)
            ->paginate(12);

        return view('offers.index', compact('variantes'));
    }
}
