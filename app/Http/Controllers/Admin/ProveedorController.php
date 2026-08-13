<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::withCount('productoVariantes')->latest()->paginate(20);
        return view('admin.proveedores.index', compact('proveedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'              => 'required|string|max:150|unique:proveedores,nombre',
            'tipo_documento'      => 'nullable|string|in:V,E,J,G',
            'documento_identidad' => 'nullable|string|max:20',
            'telefono'             => 'nullable|string|max:20',
            'email'                => 'nullable|email|max:150',
            'direccion'            => 'nullable|string|max:250',
            'contacto_alternativo' => 'nullable|string|max:150',
            'cuenta_bancaria'      => 'nullable|string|max:500',
            'redes_sociales'       => 'nullable|string|max:150',
            'notas'                => 'nullable|string|max:1000',
        ], [
            'nombre.unique' => 'Ya existe un proveedor con ese nombre.'
        ]);

        Proveedor::create($request->all());

        return back()->with('success', 'Proveedor creado exitosamente.');
    }

    public function destroy(int $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        if ($proveedor->productoVariantes()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el proveedor porque tiene productos asociados. Primero desvincúlelos o elimine los productos.');
        }

        $proveedor->delete();
        return back()->with('success', 'Proveedor eliminado exitosamente.');
    }
}
