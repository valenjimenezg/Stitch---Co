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
}
