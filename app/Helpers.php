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
        return Cache::remember('bcv_rate', 3600, function () {
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
    function bs($usd, $raw = false)
    {
        $usd = (float) $usd;
        $val = $usd * bcv_rate();

        return $raw ? $val : 'Bs. '.number_format($val, 2, ',', '.');
    }
}
