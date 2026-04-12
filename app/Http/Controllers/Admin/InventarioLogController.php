<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventarioLog;
use Illuminate\Http\Request;

class InventarioLogController extends Controller
{
    public function index(Request $request)
    {
        $query = InventarioLog::with(['variante.producto', 'user']);

        if ($request->has('tipo') && in_array($request->tipo, ['entrada', 'salida'])) {
            if ($request->tipo === 'entrada') {
                $query->where('cantidad_cambio', '>', 0);
            } else {
                $query->where('cantidad_cambio', '<', 0);
            }
        }

        $logs = $query->latest()->paginate(20);
        
        return view('admin.inventory-logs.index', compact('logs'));
    }
}
