<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'metodo' => 'required|in:efectivo,transferencia,pago_movil,zelle,debito_inmediato',
            'tipo_envio' => 'required|in:retiro_tienda,envio_domicilio',
        ];

        if ($this->metodo === 'pago_movil') {
            $rules['banco_origen_pm'] = 'required|string';
            $rules['telefono_origen_pm'] = 'required|string';
            $rules['referencia_pm'] = 'required|string';
        } elseif ($this->metodo === 'transferencia') {
            $rules['banco_origen_transf'] = 'required|string';
            $rules['referencia_transf'] = 'required|string';
        } elseif ($this->metodo === 'zelle') {
            $rules['banco_origen_zelle'] = 'required|string';
            $rules['referencia_zelle'] = 'required|string';
        } elseif ($this->metodo === 'debito_inmediato') {
            $rules['banco_c2p'] = 'required|string';
            $rules['telefono_c2p'] = 'required|string';
            $rules['cedula_c2p'] = 'required|string';
            $rules['clave_c2p'] = 'required|string';
        }

        if ($this->tipo_envio === 'envio_domicilio') {
            if (auth()->check()) {
                if ($this->direccion_id && $this->direccion_id !== 'new') {
                    $rules['direccion_id'] = 'required|exists:direcciones,id';
                } else {
                    $rules['estado_envio'] = 'required|string|max:100';
                    $rules['ciudad'] = 'required|string|max:100';
                    $rules['calle'] = 'required|string|max:255';
                    $rules['parroquia'] = 'required|string|max:100';
                }
            } else {
                $rules['email'] = 'required|email';
                $rules['nombre'] = 'required|string|max:255';
                $rules['telefono'] = 'required|string|max:20';
                $rules['estado_envio'] = 'required|string|max:100';
                $rules['ciudad'] = 'required|string|max:100';
                $rules['calle'] = 'required|string|max:255';
                $rules['parroquia'] = 'required|string|max:100';
            }
            $rules['agencia_envio'] = 'required|string|max:100';
        } elseif ($this->tipo_envio === 'retiro_tienda') {
            if (! auth()->check()) {
                $rules['email'] = 'required|email';
                $rules['nombre'] = 'required|string|max:255';
                $rules['telefono'] = 'required|string|max:20';
            }
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $carrito = null;
            if (auth()->check()) {
                $carrito = auth()->user()->carritoActivo();
            } else {
                $items = session()->get('cart', []);
                if (! empty($items)) {
                    $detalles = [];
                    foreach ($items as $variante_id => $cantidad) {
                        $variante = \App\Models\DetalleProducto::with('producto')->find($variante_id);
                        if ($variante) {
                            $detalles[] = (object) [
                                'cantidad' => $cantidad,
                                'variante' => $variante,
                            ];
                        }
                    }
                    $carrito = (object) ['detalles' => collect($detalles)];
                }
            }

            if (! $carrito || $carrito->detalles->isEmpty()) {
                $validator->errors()->add('cart', 'Tu carrito está vacío.');

                return;
            }

            foreach ($carrito->detalles as $item) {
                if (! $item->variante->hasStock($item->cantidad)) {
                    $validator->errors()->add('stock', "Agotado: No hay suficiente stock para {$item->variante->producto->nombre} ({$item->variante->color}).");
                }
            }
        });
    }
}
