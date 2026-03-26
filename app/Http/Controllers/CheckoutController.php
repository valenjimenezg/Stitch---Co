<?php

namespace App\Http\Controllers;

use App\Models\DetalleProducto;
use App\Models\DetalleVenta;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $direcciones = auth()->user()->direcciones;
        return view('checkout.index', compact('direcciones'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'calle'   => 'required|string|max:255',
            'ciudad'  => 'required|string|max:100',
            'metodo'  => 'required|in:efectivo,transferencia,pago_movil,transferencia_p2p,debito_inmediato,tarjeta,paypal',
            'cart_payload' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $cartData = json_decode($request->cart_payload, true);
            if (empty($cartData)) {
                throw new \Exception('Tu carrito está vacío o ha expirado.');
            }

            $banco = $request->banco_pago;
            $referencia = $request->referencia_pago;
            if (in_array($request->metodo, ['transferencia', 'transferencia_p2p'])) {
                $banco = $request->input('banco_pago_transf', $request->banco_pago);
                $referencia = $request->input('referencia_pago_transf', $request->referencia_pago);
            } elseif ($request->metodo === 'debito_inmediato') {
                $banco = $request->input('banco_pago_debito', $request->banco_pago);
                $referencia = $request->input('referencia_pago_debito', $request->referencia_pago);
            }

            // Crear la venta en borrador antes del bucle de validación
            $venta = Venta::create([
                'user_id'             => auth()->id(),
                'total_venta'         => 0, // Se actualizará luego
                'metodo_pago'         => $request->metodo,
                'banco_pago'          => $banco,
                'telefono_pago'       => $request->telefono_pago,
                'referencia_pago'     => $referencia,
                'estado'              => 'pendiente',
                'calle_envio'         => $request->calle,
                'ciudad_envio'        => $request->ciudad,
                'estado_envio'        => $request->estado_provincia,
                'codigo_postal_envio' => $request->codigo_postal,
            ]);

            $totalVentaCalculado = 0;

            foreach ($cartData as $item) {
                // Validación Estricta Pessimistic Locking
                $variante = DetalleProducto::with('producto')->where('id', $item['id'])->lockForUpdate()->first();

                if (!$variante) {
                    throw new \Exception("Un producto del carrito ya no existe en la base de datos.");
                }

                if ($variante->stock < $item['cantidad']) {
                    $nombre = $variante->producto->nombre ?? 'Producto no definido';
                    throw new \Exception("No hay suficiente stock para: {$nombre}. Disponibles: {$variante->stock}.");
                }

                // Fijar el Precio Únicamente desde PHP/MySQL (Prevención Hacking Frontend)
                $precioGuardar = $variante->en_oferta ? $variante->precio_con_descuento : $variante->precio;
                $subtotal = $precioGuardar * $item['cantidad'];
                $totalVentaCalculado += $subtotal;

                // Crear el Detalle
                DetalleVenta::create([
                    'venta_id'       => $venta->id,
                    'variante_id'    => $variante->id,
                    'cantidad'       => $item['cantidad'],
                    'precio_unitario' => $precioGuardar,
                    'subtotal'       => $subtotal,
                ]);

                // Modificar Stock Real (Safe Assignment)
                $variante->stock = $variante->stock - $item['cantidad'];
                $variante->save();

                // Generar Kardex / Auditoría de Salida
                \App\Models\MovimientoInventario::create([
                    'variante_id' => $variante->id,
                    'venta_id'    => $venta->id,
                    'cantidad'    => -$item['cantidad'],
                    'tipo'        => 'salida',
                    'motivo'      => 'Venta Web #' . $venta->id,
                ]);
            }

            // Actualizar el total oficial de la Factura y Confirmar ACID.
            $venta->update(['total_venta' => $totalVentaCalculado]);

            DB::commit();
            session()->flash('success', '¡Pedido realizado con éxito! Pronto nos comunicaremos contigo.');

            return response()->json([
                'success' => true,
                'message' => '¡Pedido realizado con éxito!',
                'redirect' => route('profile.orders')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
