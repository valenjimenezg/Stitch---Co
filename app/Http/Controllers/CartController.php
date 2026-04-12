<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\OrdenDetalle;
use App\Models\ProductoVariante;
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
            'variante_id' => 'required|exists:producto_variantes,id',
            'empaque_id'  => 'nullable|exists:producto_variantes,id', // empaque is now a variant with parent_id
            'cantidad'    => 'required|numeric|min:0.1',
        ]);

        $variante = ProductoVariante::findOrFail($request->variante_id);

        $factorConversion = 1;
        $precioUnit = $variante->en_oferta ? $variante->precio_con_descuento : $variante->precio;

        if ($request->empaque_id) {
            $empaque = ProductoVariante::find($request->empaque_id);
            $factorConversion = $empaque ? $empaque->factor_conversion : 1;
            // precio empaque is normally set directly
            $precioUnit = $empaque->en_oferta ? $empaque->precio_con_descuento : $empaque->precio;
        } else {
            $factorConversion = $variante->factor_conversion ?: 1;
        }

        if ($variante->stock_disponible < ($request->cantidad * $factorConversion)) {
            return back()->with('error', 'No hay suficiente stock para completar esta unidad de presentación.');
        }

        $carrito = Orden::firstOrCreate(
            ['user_id' => auth()->id(), 'estado' => 'carrito'],
            ['subtotal' => 0, 'impuesto' => 0, 'envio' => 0, 'total' => 0]
        );

        $item = $carrito->detalles()->where('variante_id', $variante->id)
            ->first(); // Note: Si agregas la version empaque se mezcla con la variante para simplicidad o usas el id del empaque directamente.

        // En la arquitectura ERP 12 tables, si compró empaque guardamos el ID del empaque como el ID de la variante. 
        // Porque el empaque es una variante en si misma!
        $varianteFinalId = $request->empaque_id ? $request->empaque_id : $variante->id;
        
        $item = $carrito->detalles()->where('variante_id', $varianteFinalId)->first();

        if ($item) {
            $nuevaCantidad = $item->cantidad + $request->cantidad;
            // Validamos contra stock master (el stock master está en el parent, obtenemos el base logic)
            $varChecker = ProductoVariante::find($varianteFinalId);
            if ($varChecker->stock_disponible < $nuevaCantidad) {
                return back()->with('error', 'No hay suficiente stock para completar esta unidad de presentación.');
            }
            $item->update([
                'cantidad' => $nuevaCantidad, 
                'subtotal' => $nuevaCantidad * $precioUnit
            ]);
        } else {
            $carrito->detalles()->create([
                'variante_id' => $varianteFinalId,
                'cantidad'    => $request->cantidad,
                'precio_unitario' => $precioUnit,
                'subtotal' => $request->cantidad * $precioUnit
            ]);
        }

        $carrito->recalcularTotales();

        return back()->with('success', 'Producto agregado al carrito.');
    }

    public function update(Request $request, int $item)
    {
        $detalle = OrdenDetalle::where('id', $item)
            ->whereHas('orden', fn($q) => $q->where('user_id', auth()->id())->where('estado', 'carrito'))
            ->firstOrFail();

        $accion = $request->input('accion', 'inc');

        // Determinar paso fractal si es tela. `unidad_medida` está en variante.
        $u = strtolower($detalle->variante->unidad_medida ?? '');
        $step = in_array($u, ['metro', 'centímetro', 'cm']) ? 0.5 : 1;
        
        if ($accion === 'inc') {
            if ($detalle->variante->stock_disponible >= ($detalle->cantidad + $step)) {
                $detalle->increment('cantidad', $step);
                $detalle->update(['subtotal' => $detalle->cantidad * $detalle->precio_unitario]);
            }
        } else {
            if ($detalle->cantidad > $step) {
                $detalle->decrement('cantidad', $step);
                $detalle->update(['subtotal' => $detalle->cantidad * $detalle->precio_unitario]);
            } else {
                $detalle->delete();
            }
        }

        $detalle->orden->recalcularTotales();

        return back();
    }

    public function remove(int $item)
    {
        $detalle = OrdenDetalle::where('id', $item)
            ->whereHas('orden', fn($q) => $q->where('user_id', auth()->id())->where('estado', 'carrito'))
            ->firstOrFail();
            
        $orden = $detalle->orden;
        $detalle->delete();
        $orden->recalcularTotales();

        return back()->with('success', 'Producto eliminado del carrito.');
    }
}
