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
            'cantidad'    => 'required|numeric|min:0.1',
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

        // Determinar paso fractal si es tela
        $step = in_array(strtolower($detalle->variante->unidad_medida), ['metro', 'centímetro', 'cm']) ? 0.5 : 1;

        if ($accion === 'inc') {
            if ($detalle->variante->stock >= ($detalle->cantidad + $step)) {
                $detalle->increment('cantidad', $step);
            }
        } else {
            if ($detalle->cantidad > $step) {
                $detalle->decrement('cantidad', $step);
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
