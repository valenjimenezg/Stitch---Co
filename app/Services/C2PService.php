<?php

namespace App\Services;

class C2PService
{
    /**
     * Simula la pasarela de procesamiento C2P (Cobro a Persona) interbancaria.
     */
    public function processPayment(string $banco, string $cedula, string $telefono, string $token, float $montoBs): array
    {
        // 1. Simular latencia de red lógica real del Consorcio Credicard / Switch Suiche7B
        sleep(2);

        // 2. Validación de Token simulado (Hardcoded de control)
        // Token "000000" es el disparador para rechazo por fondos.
        if ($token === '000000') {
            return [
                'status'  => 'error',
                'message' => 'Fondos insuficientes o límite diario C2P excedido en cuenta de origen.'
            ];
        }

        // 3. Validación de estructura de Clave de Pago (Generalmente 6 a 8 dígitos según el banco)
        if (!preg_match('/^[0-9]{6,8}$/', $token)) {
            return [
                'status'  => 'error',
                'message' => 'Token Inválido. La Clave de Pago Dinámica debe contener entre 6 y 8 dígitos.'
            ];
        }

        // Simulación: Validar que el banco emisor pueda operar montos
        if ($montoBs <= 0) {
            return [
                'status'  => 'error',
                'message' => 'Transacción rechazada. El monto es inválido.'
            ];
        }

        // 4. Éxito Absoluto - Simulación de asentamiento en el core bancario del comercio
        return [
            'status'     => 'success',
            'message'    => 'Débito Inmediato procesado correctamente en tiempo real.',
            'referencia' => 'C2P' . str_pad((string) rand(10000, 999999), 8, '0', STR_PAD_LEFT),
            'banco'      => $banco
        ];
    }
}
