<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function show($id)
    {
        $cliente = User::with(['ordenes.detalles.variante.producto', 'comentarios.producto'])->findOrFail($id);
        
        if ($cliente->rol !== 'cliente') {
            return redirect()->route('admin.clients')->with('error', 'El usuario seleccionado no es un cliente.');
        }

        $notificaciones_stock = \App\Models\NotificacionCrm::with('variante.producto.categoria')
            ->whereIn('tipo', ['stock', 'stock_alert'])
            ->where('email', $cliente->email)
            ->orderBy('procesado', 'asc')
            ->latest()
            ->get();

        return view('admin.clients.show', compact('cliente', 'notificaciones_stock'));
    }

    public function edit($id)
    {
        $cliente = User::findOrFail($id);

        if ($cliente->rol !== 'cliente') {
            return redirect()->route('admin.clients')->with('error', 'El usuario seleccionado no es un cliente.');
        }

        return view('admin.clients.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $cliente = User::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,' . $cliente->id,
            'telefono' => 'nullable|string|max:20',
            'tipo_documento' => 'nullable|string|in:V,E,P,J,G',
            'documento_identidad' => 'nullable|string|max:20',
        ]);

        $cliente->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'tipo_documento' => $request->tipo_documento,
            'documento_identidad' => $request->documento_identidad,
        ]);

        return redirect()->route('admin.clients.show', $cliente->id)->with('success', 'Datos del cliente actualizados correctamente.');
    }
}
