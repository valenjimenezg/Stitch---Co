<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\ProductoVariante;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $variantesIds = is_array($user->lista_deseos) ? $user->lista_deseos : [];

        $items = collect();
        if (!empty($variantesIds)) {
            $items = ProductoVariante::with('producto.categoria')
                ->whereIn('id', $variantesIds)
                ->paginate(12);
        } else {
            // Retorna un paginador vacío para evitar errores de vista
            $items = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);
        }

        return view('wishlist.index', compact('items'));
    }

    public function toggle(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('info', '¡Hola! Para guardar tus productos favoritos, por favor regístrate o inicia sesión.')
                ->withInput(['_tab' => 'registro']);
        }

        $request->validate(['variante_id' => 'required|exists:producto_variantes,id']);
        $varianteId = (int) $request->variante_id;
        
        $user = auth()->user();
        $lista = is_array($user->lista_deseos) ? $user->lista_deseos : [];

        $pos = array_search($varianteId, $lista);

        if ($pos !== false) {
            unset($lista[$pos]);
            $msg = 'Producto eliminado de la lista de deseos.';
            $inWishlist = false;
        } else {
            $lista[] = $varianteId;
            $msg = 'Producto guardado en tu lista de deseos.';
            $inWishlist = true;
        }

        $user->lista_deseos = array_values($lista);
        $user->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'in_wishlist' => $inWishlist,
                'message' => $msg
            ]);
        }

        return back()->with('success', $msg);
    }

    public function moveToCart(int $id)
    {
        $user = auth()->user();
        $lista = is_array($user->lista_deseos) ? $user->lista_deseos : [];

        $pos = array_search($id, $lista);
        
        if ($pos === false) {
            abort(404, 'El producto no está en tu lista de deseos.');
        }

        $orden = Orden::firstOrCreate(
            ['user_id' => $user->id, 'estado' => 'carrito'],
            ['subtotal' => 0, 'impuesto' => 0, 'envio' => 0, 'total' => 0]
        );

        $item = $orden->detalles()->where('variante_id', $id)->first();

        if ($item) {
            $item->increment('cantidad');
        } else {
            $variante = ProductoVariante::find($id);
            if ($variante) {
                $orden->detalles()->create([
                    'variante_id' => $id,
                    'cantidad'    => 1,
                    'precio_unitario' => $variante->en_oferta ? $variante->precio * (1 - $variante->descuento_porcentaje / 100) : $variante->precio,
                    'subtotal'    => $variante->en_oferta ? $variante->precio * (1 - $variante->descuento_porcentaje / 100) : $variante->precio,
                ]);
            }
        }

        // Eliminar de deseos
        unset($lista[$pos]);
        $user->lista_deseos = array_values($lista);
        $user->save();
        $orden->recalcularTotales();

        return redirect()->route('cart.index')->with('success', 'Producto movido al carrito.');
    }
}
