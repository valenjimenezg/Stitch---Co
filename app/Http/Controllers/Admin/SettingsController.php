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

        $oldRate = bcv_rate();

        $usarManual = $request->has('usar_tasa_manual');
        Cache::forever('config_usar_tasa_manual', $usarManual);
        
        if ($usarManual) {
            Cache::forever('config_tasa_bcv_manual', floatval($request->tasa_bcv_manual));
        }

        Cache::forget('bcv_rate'); // Borrar caché para que el helper recalcule inmediatamente
        $newRate = bcv_rate();

        $msg = 'Configuración de Tasa BCV actualizada exitosamente.';

        if (round($oldRate, 2) !== round($newRate, 2)) {
            $diff = $newRate - $oldRate;
            $percent = abs(($diff / $oldRate) * 100);
            $direction = $diff > 0 ? '🔺 Aumentó' : '🔻 Bajó';
            $color = $diff > 0 ? 'text-amber-500' : 'text-emerald-500';
            session()->flash('rate_change', "<span class='{$color} font-bold'>{$direction} un " . number_format($percent, 2) . "%</span> respecto a la tasa anterior (Bs. " . number_format($oldRate, 2, ',', '.') . ").");
        }

        if (in_array(date('N'), [6, 7])) {
            session()->flash('weekend_notice', "<strong>Aviso Ley BCV:</strong> Has actualizado la tasa en fin de semana. Por regulación, este precio acordado aplicará como tasa valor del día <strong>Lunes</strong>.");
        }

        return back()->with('success', $msg);
    }
}
