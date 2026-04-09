<?php

namespace App\Http\Controllers;

use App\Models\DetalleProducto;

class ProductController extends Controller
{
    public function show(int $id)
    {
        $variante = DetalleProducto::with('producto.detalleProductos')->findOrFail($id);

        $relacionados = DetalleProducto::with('producto')
            ->whereHas('producto', fn($q) => $q->where('categoria', $variante->producto->categoria))
            ->where('id', '!=', $id)
            ->take(4)
            ->get();

        return view('products.show', compact('variante', 'relacionados'));
    }

    public function apiShow(int $id)
    {
        $variante = DetalleProducto::with('producto')->findOrFail($id);
        
        return response()->json([
            'id' => $variante->id,
            'nombre' => $variante->producto->nombre ?? 'Producto',
            'marca' => $variante->marca,
            'categoria' => $variante->producto->categoria,
            'precio' => $variante->precio,
            'precio_con_descuento' => $variante->precio_con_descuento,
            'en_oferta' => $variante->en_oferta,
            'color' => $variante->color,
            'grosor' => $variante->grosor,
            'cm' => $variante->cm,
            'unidad_medida' => $variante->unidad_medida,
            'talla' => $variante->talla,
            'descripcion' => $variante->producto->descripcion,
            'imagen' => $variante->imagen ? asset($variante->imagen) : null,
            'stock' => $variante->stock
        ]);
    }
}
