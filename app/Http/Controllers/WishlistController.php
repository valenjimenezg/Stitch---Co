<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\ListaDeseo;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $items = ListaDeseo::with('variante.producto')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(12);

        return view('wishlist.index', compact('items'));
    }

    public function toggle(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('info', '¡Hola! Para guardar tus productos favoritos, por favor regístrate o inicia sesión.')
                ->withInput(['_tab' => 'registro']);
        }

        $request->validate(['variante_id' => 'required|exists:detalle_productos,id']);

        $existe = ListaDeseo::where('user_id', auth()->id())
            ->where('variante_id', $request->variante_id)
            ->first();

        if ($existe) {
            $existe->delete();
            $msg = 'Producto eliminado de la lista de deseos.';
            $inWishlist = false;
        } else {
            ListaDeseo::create([
                'user_id'     => auth()->id(),
                'variante_id' => $request->variante_id,
            ]);
            $msg = 'Producto guardado en tu lista de deseos.';
            $inWishlist = true;
        }

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
        $deseo = ListaDeseo::with('variante')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $carrito = auth()->user()->carritoActivo()
            ?? Carrito::create(['user_id' => auth()->id(), 'estado' => 'activo']);

        $item = $carrito->detalles()->where('variante_id', $deseo->variante_id)->first();

        if ($item) {
            $item->increment('cantidad');
        } else {
            $carrito->detalles()->create([
                'variante_id' => $deseo->variante_id,
                'cantidad'    => 1,
            ]);
        }

        $deseo->delete();

        return redirect()->route('cart.index')->with('success', 'Producto movido al carrito.');
    }
}
