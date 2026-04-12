<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function bcvIndex()
    {
        $config = (object) [
            'usar_tasa_manual' => Cache::get('config_usar_tasa_manual', false),
            'tasa_bcv_manual' => Cache::get('config_tasa_bcv_manual', 0)
        ];
        return view('admin.settings.bcv', compact('config'));
    }

    public function bcvUpdate(Request $request)
    {
        // Limpiar coma por punto para facilitar la validación numérica
        if ($request->has('tasa_bcv_manual')) {
            $request->merge([
                'tasa_bcv_manual' => str_replace(',', '.', $request->tasa_bcv_manual)
            ]);
        }

        $request->validate([
            'usar_tasa_manual' => 'nullable|boolean',
            'tasa_bcv_manual'  => 'nullable|numeric|min:0.01',
        ]);

        $usarManual = $request->has('usar_tasa_manual');
        Cache::forever('config_usar_tasa_manual', $usarManual);
        
        if ($usarManual) {
            Cache::forever('config_tasa_bcv_manual', $request->tasa_bcv_manual);
        }

        Cache::forget('bcv_rate'); // Borrar caché para que el helper recalcule inmediatamente

        return back()->with('success', 'Configuración de Tasa BCV actualizada exitosamente (via Caché).');
    }
}
