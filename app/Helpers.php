<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

if (! function_exists('bcv_rate')) {
    /**
     * Obtiene la tasa oficial del BCV desde la API pública de PyDolarVenezuela
     * y la cachea por 1 hora para mejorar el rendimiento.
     */
    function bcv_rate()
    {
        $usarManual = \Illuminate\Support\Facades\Cache::get('config_usar_tasa_manual', false);
        $tasaManual = \Illuminate\Support\Facades\Cache::get('config_tasa_bcv_manual', 0);
        if ($usarManual && $tasaManual > 0) {
            return (float) $tasaManual;
        }

        // Caché de 5 minutos en lugar de 1 hora para mayor fidelidad a cambios diarios del Banco.
        return Cache::remember('bcv_rate', 300, function () {
            try {
                $response = Http::timeout(5)->get('https://pydolarvenezuela-api.vercel.app/api/v1/dollar?page=bcv');
                if ($response->successful()) {
                    return (float) $response->json('monitors.bcv.price');
                }
            } catch (\Exception $e) {
            }

            return 36.50; // Fallback por defecto si falla la API
        });
    }
}

if (! function_exists('bs')) {
    /**
     * Convierte el precio base en dolares a Bolívares (Bs) a la tasa del día.
     */
    function bs($usd, $raw = false, $tasaManual = null)
    {
        $usd = (float) $usd;
        $tasa = $tasaManual ?? bcv_rate();
        $val = $usd * $tasa;

        return $raw ? $val : 'Bs. '.number_format($val, 2, ',', '.');
    }
}
