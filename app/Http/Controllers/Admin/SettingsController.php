<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Configuracion;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function bcvIndex()
    {
        $config = Configuracion::firstOrCreate(['clave' => 'general']);
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

        $config = Configuracion::firstOrCreate(['clave' => 'general']);
        $config->usar_tasa_manual = $request->has('usar_tasa_manual');
        if ($config->usar_tasa_manual) {
            $config->tasa_bcv_manual = $request->tasa_bcv_manual;
        }
        $config->save();

        Cache::forget('bcv_rate'); // Borrar caché para que el helper recalcule inmediatamente

        return back()->with('success', 'Configuración de Tasa BCV actualizada exitosamente.');
    }
}
