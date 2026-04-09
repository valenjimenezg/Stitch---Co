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
            'tipo_envio' => 'required|in:delivery,retiro_tienda',
            'calle'   => 'required_if:tipo_envio,delivery|nullable|string|max:255',
            'ciudad'  => 'nullable|string|max:100',
            'metodo'  => 'required|in:efectivo,transferencia,pago_movil,transferencia_p2p,tarjeta,paypal',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'cart_payload' => 'required|string',
        ]);


        if (empty(auth()->user()->document_number)) {
            return response()->json([
                'success' => false,
                'message' => 'Es obligatorio proporcionar un Documento de Identidad válido para facturación.',
            ], 422);
        }

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
            }

            if (in_array($request->metodo, ['pago_movil', 'transferencia', 'transferencia_p2p'])) {
                if (!$request->hasFile('comprobante')) {
                    return response()->json(['success' => false, 'message' => 'El comprobante de pago es obligatorio.'], 422);
                }
                if (\App\Models\Pago::where('bank_name', $banco)->where('reference_number', $referencia)->exists()) {
                    return response()->json(['success' => false, 'message' => "La referencia {$referencia} del banco {$banco} ya ha sido registrada previamente. No se permite duplicar pagos."], 422);
                }
            }

            // Crear la venta en borrador antes del bucle de validación
            $venta = Venta::create([
                'user_id'             => auth()->id(),
                'total_venta'         => 0, // Se actualizará luego
                'metodo_pago'         => $request->metodo,
                'banco_pago'          => $banco,
                'telefono_pago'       => $request->celular_c2p ?? $request->telefono_pago,
                'referencia_pago'     => $referencia,
                'estado'              => 'pendiente',
                'tipo_envio'          => $request->tipo_envio,
                'calle_envio'         => $request->tipo_envio === 'retiro_tienda' ? 'Retiro en Tienda' : $request->calle,
                'ciudad_envio'        => $request->tipo_envio === 'retiro_tienda' ? 'Guanare' : 'Guanare',
                'estado_envio'        => 'Portuguesa',
                'tasa_bcv_aplicada'   => bcv_rate(),
                'delivery_method'     => $request->tipo_envio,
            ]);

            $subtotalVentaCalculado = 0;

            foreach ($cartData as $item) {
                // Validación Estricta Pessimistic Locking
                /** @var \App\Models\DetalleProducto|null $variante */
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
                $subtotalVentaCalculado += $subtotal;

                // Crear el Detalle
                DetalleVenta::create([
                    'venta_id'       => $venta->id,
                    'variante_id'    => $variante->id,
                    'cantidad'       => $item['cantidad'],
                    'precio_unitario' => $precioGuardar,
                    'subtotal'       => $subtotal,
                ]);

                // Modificar Stock Real (Safe Assignment) con Factor de Conversión
                $factorConversion = $variante->factor_conversion ?: 1;
                $cantidadAfectada = $item['cantidad'] * $factorConversion;
                
                $variante->stock = $variante->stock - $cantidadAfectada;
                $variante->save();

                // Generar Kardex / Auditoría de Salida
                \App\Models\MovimientoInventario::create([
                    'variante_id' => $variante->id,
                    'venta_id'    => $venta->id,
                    'cantidad'    => -$cantidadAfectada,
                    'tipo'        => 'salida',
                    'motivo'      => 'Venta Web #' . $venta->id,
                ]);
            }

            // Calculations based on requested spec
            $iva = round($subtotalVentaCalculado * 0.16, 2);
            $deliveryFee = ($request->tipo_envio === 'delivery') ? 1.00 : 0.00;
            $totalAmountCalculado = $subtotalVentaCalculado + $iva + $deliveryFee;

            // Handle unique Payment Record & Image Upload
            if (in_array($request->metodo, ['pago_movil', 'transferencia', 'transferencia_p2p'])) {
                $path = $request->file('comprobante')->store('receipts', 'public');
                \App\Models\Pago::create([
                    'venta_id'         => $venta->id,
                    'payment_method'   => $request->metodo,
                    'bank_name'        => $banco,
                    'reference_number' => $referencia,
                    'receipt_path'     => $path,
                    'amount'           => $totalAmountCalculado
                ]);
            }

            // Actualizar el total oficial de la Factura y Confirmar ACID.
            $venta->update([
                'subtotal'     => $subtotalVentaCalculado,
                'iva_amount'   => $iva,
                'delivery_fee' => $deliveryFee,
                'total_amount' => $totalAmountCalculado,
                'total_venta'  => $totalAmountCalculado // Legacy overwrite for old views
            ]);

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
