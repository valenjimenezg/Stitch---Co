<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\DetalleCarrito;
use App\Models\DetalleProducto;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // El carrito funciona 100% con localStorage + JS (cart.js)
        return view('cart.index');
    }

    public function add(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('info', '¡Hola! Para agregar productos al carrito y finalizar tu compra, por favor regístrate o inicia sesión.')
                ->withInput(['_tab' => 'registro']);
        }

        $request->validate([
            'variante_id' => 'required|exists:detalle_productos,id',
            'cantidad'    => 'required|integer|min:1',
        ]);

        $variante = DetalleProducto::findOrFail($request->variante_id);

        if ($variante->stock < $request->cantidad) {
            return back()->with('error', 'Stock insuficiente.');
        }

        $carrito = auth()->user()->carritoActivo()
            ?? Carrito::create(['user_id' => auth()->id(), 'estado' => 'activo']);

        $item = $carrito->detalles()->where('variante_id', $variante->id)->first();

        if ($item) {
            $nuevaCantidad = $item->cantidad + $request->cantidad;
            if ($variante->stock < $nuevaCantidad) {
                return back()->with('error', 'No hay suficiente stock.');
            }
            $item->update(['cantidad' => $nuevaCantidad]);
        } else {
            $carrito->detalles()->create([
                'variante_id' => $variante->id,
                'cantidad'    => $request->cantidad,
            ]);
        }

        return back()->with('success', 'Producto agregado al carrito.');
    }

    public function update(Request $request, int $item)
    {
        $detalle = DetalleCarrito::where('id', $item)
            ->whereHas('carrito', fn($q) => $q->where('user_id', auth()->id()))
            ->firstOrFail();

        $accion = $request->input('accion', 'inc');

        if ($accion === 'inc') {
            if ($detalle->variante->stock > $detalle->cantidad) {
                $detalle->increment('cantidad');
            }
        } else {
            if ($detalle->cantidad > 1) {
                $detalle->decrement('cantidad');
            } else {
                $detalle->delete();
            }
        }

        return back();
    }

    public function remove(int $item)
    {
        DetalleCarrito::where('id', $item)
            ->whereHas('carrito', fn($q) => $q->where('user_id', auth()->id()))
            ->firstOrFail()
            ->delete();

        return back()->with('success', 'Producto eliminado del carrito.');
    }
}
