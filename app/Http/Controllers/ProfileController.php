<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nombre'   => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'telefono' => 'nullable|string|max:20',
            'tipo_documento' => 'required|in:V,E,J,G',
            'documento_identidad' => 'required|string|max:20|unique:users,documento_identidad,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::min(6)],
        ]);

        $data = $request->only('nombre', 'apellido', 'email', 'telefono', 'tipo_documento', 'documento_identidad');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function addresses()
    {
        $direcciones = auth()->user()->direcciones;
        return view('profile.addresses', compact('direcciones'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'calle'       => 'required|string|max:255',
            'ciudad'      => 'required|string|max:100',
            'referencia'  => 'nullable|string|max:255',
        ]);

        auth()->user()->direcciones()->create($request->only('calle', 'ciudad', 'referencia'));

        return back()->with('success', 'Dirección agregada.');
    }

    public function destroyAddress(int $id)
    {
        auth()->user()->direcciones()->where('id', $id)->delete();
        return back()->with('success', 'Dirección eliminada.');
    }

    public function orders()
    {
        $ventas = auth()->user()->ordenes()->with('detalles.variante.producto')->latest()->paginate(10);
        return view('profile.orders', compact('ventas'));
    }

    public function resumeOrder($id)
    {
        $venta = auth()->user()->ordenes()->findOrFail($id);
        return view('profile.resume_order', compact('venta'));
    }

    public function storeReference(Request $request, $id)
    {
        $venta = auth()->user()->ordenes()->findOrFail($id);
        $request->validate([
            'banco_pago'      => 'nullable|string|max:100',
            'referencia_pago' => 'required|string|max:100',
        ]);
        
        $venta->update([
            'banco_pago'      => $request->banco_pago,
            'referencia_pago' => $request->referencia_pago,
        ]);
        
        return redirect()->route('profile.orders')->with('success', 'Referencia enviada con éxito. Revisaremos tu pago.');
    }

    public function cancelOrder($id)
    {
        $venta = auth()->user()->ordenes()->with('detalles.variante')->findOrFail($id);
        
        if (!in_array($venta->estado, ['pendiente', 'pending'])) {
            abort(403, 'No puedes cancelar un pedido que ya ha sido procesado o pagado.');
        }

        if (!empty($venta->referencia_pago)) {
            abort(403, 'No puedes cancelar un pedido para el cual ya has registrado un pago. Por favor contacta a soporte.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($venta) {
            $venta->update(['estado' => 'cancelado']);

            foreach ($venta->detalles as $detalle) {
                if ($detalle->variante) {
                    $varianteBase = $detalle->variante->parent_id ? $detalle->variante->parent : $detalle->variante;
                    $cantidadRestaurar = $detalle->cantidad * ($detalle->variante->factor_conversion ?? 1);
                    $varianteBase->increment('stock_base', $cantidadRestaurar);
                    
                    \App\Models\InventarioLog::create([
                        'variante_id'     => $detalle->variante->id,
                        'cantidad_cambio' => $detalle->cantidad,
                        'motivo'          => 'Devolución: Autocancelación por Cliente - Orden #' . $venta->id,
                        'orden_id'        => $venta->id,
                        'user_id'         => auth()->id(),
                    ]);
                }
            }
        });

        return back()->with('success', 'Tu pedido ha sido cancelado y los productos han sido devueltos al inventario.');
    }

    public function factura($id)
    {
        $venta = auth()->user()->ordenes()->with(['detalles.variante.producto'])->findOrFail($id);
        
        if (empty($venta->invoice_number)) {
            abort(404, 'Factura no disponible o pedido no pagado completamente.');
        }
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('profile.factura_pdf', compact('venta'));
        return $pdf->stream('factura_STITCH_ORD-' . str_pad($venta->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }
}
