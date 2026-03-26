<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'password' => 'nullable|string|min:8',
        ]);

        $data = $request->only('nombre', 'apellido', 'email', 'telefono', 'cedula_identidad');

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
        $ventas = auth()->user()->ventas()->with('detalles.variante.producto')->latest()->paginate(10);
        return view('profile.orders', compact('ventas'));
    }

    public function resumeOrder($id)
    {
        $venta = auth()->user()->ventas()->findOrFail($id);
        return view('profile.resume_order', compact('venta'));
    }

    public function storeReference(Request $request, $id)
    {
        $venta = auth()->user()->ventas()->findOrFail($id);
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
        $venta = auth()->user()->ventas()->findOrFail($id);
        if (in_array($venta->estado, ['pendiente', 'pending'])) {
            $venta->update(['estado' => 'cancelado']);
        }
        return back()->with('success', 'Pedido cancelado permanentemente.');
    }

    public function factura($id)
    {
        $venta = auth()->user()->ventas()->with(['detalles.variante.producto', 'factura'])->findOrFail($id);
        
        if (!$venta->factura) {
            abort(404, 'Factura no disponible.');
        }
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('profile.factura_pdf', compact('venta'));
        return $pdf->stream('factura_STITCH_ORD-' . str_pad($venta->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }
}
