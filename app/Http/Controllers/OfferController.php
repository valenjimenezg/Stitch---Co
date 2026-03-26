<?php

namespace App\Http\Controllers;

use App\Models\DetalleProducto;

class OfferController extends Controller
{
    public function index()
    {
        $variantes = DetalleProducto::with('producto')
            ->where('en_oferta', true)
            ->paginate(12);

        return view('offers.index', compact('variantes'));
    }
}
