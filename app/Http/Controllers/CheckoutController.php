<?php

namespace App\Http\Controllers;

use App\Models\ProductoVariante;
use App\Models\Orden;
use App\Models\OrdenDetalle;
use App\Models\InventarioLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $direcciones = auth()->user()->direcciones ?? [];
        return view('checkout.index', compact('direcciones'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'tipo_envio' => 'required|in:delivery,retiro_tienda',
            'sector' => 'required_if:tipo_envio,delivery|nullable|string|max:255',
            'calle'   => [
                'required_if:tipo_envio,delivery',
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->tipo_envio === 'delivery' && !empty($value)) {
                        $forbiddenZones = '/papel[oó]n|guanarito|ospino|boconoito|tucupido|biscucuy|acarigua|araure|tur[eé]n|mesa de cavacas|unellez|vicerrectorado|quebrada de la virgen|municipio sucre|san genaro|morita|pe[ñn]a|c[óo]rdova|la colonia|san jos[eé] de la monta[ñn]a|san juan de guanaguanare|virgen de coromoto/i';
                        if (preg_match($forbiddenZones, $value) || preg_match($forbiddenZones, $request->sector ?? '')) {
                            $fail('El servicio de delivery no está disponible para la ubicación ingresada. Por favor, elija "Retiro en Tienda".');
                        }
                    }
                }
            ],
            'ciudad'  => 'nullable|string|max:100',
            'metodo'  => 'required|in:efectivo,transferencia,pago_movil,transferencia_p2p,tarjeta,paypal',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'cart_payload' => 'required|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
        ]);

        if (empty(auth()->user()->documento_identidad)) {
            return response()->json([
                'success' => false,
                'message' => 'Es obligatorio configurar un Documento de Identidad válido en tu perfil para facturación.',
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
            $telefonoPago = ($request->prefijo_telefono ?? '') . ($request->numero_telefono ?? '');

            if (in_array($request->metodo, ['transferencia', 'transferencia_p2p'])) {
                $banco = $request->input('banco_pago_transf', $request->banco_pago);
                $referencia = $request->input('referencia_pago_transf', $request->referencia_pago);
                $telefonoPago = ($request->prefijo_telefono_transf ?? '') . ($request->numero_telefono_transf ?? '');
            }

            if (empty($telefonoPago) && !empty($request->telefono_pago)) {
                 $telefonoPago = $request->telefono_pago;
            }

            if (in_array($request->metodo, ['pago_movil', 'transferencia', 'transferencia_p2p'])) {
                if (!$request->hasFile('comprobante')) {
                    return response()->json(['success' => false, 'message' => 'El comprobante de pago es obligatorio.'], 422);
                }
                
                // Evitamos referencias duplicadas en nuestro nuevo modelo Orden (donde agrupamos todas las ventas y pagos)
                if (Orden::where('metodo_pago', $request->metodo)->where('banco_pago', $banco)->where('referencia_pago', $referencia)->exists()) {
                    return response()->json(['success' => false, 'message' => "La referencia {$referencia} del banco {$banco} ya ha sido registrada. No se permite duplicar pagos."], 422);
                }
            }

            // Manejar Imagen de Comprobante si la subió
            $rutaComprobante = null;
            if ($request->hasFile('comprobante')) {
                $rutaComprobante = $request->file('comprobante')->store('receipts', 'public');
            }

            // Si el cliente tenía un carrito guardado en BD, lo cerramos o sobreescribimos.
            // Para simplicidad, creamos la orden definitiva.
            $orden = Orden::create([
                'user_id'             => auth()->id(),
                'subtotal'            => 0, // Se actualizará luego
                'iva_amount'          => 0,
                'delivery_fee'        => 0,
                'total_amount'        => 0, // Se actualizará luego
                'monto_abonado'       => 0,
                'tasa_bcv_aplicada'   => bcv_rate(),
                'estado'              => 'pendiente',
                'metodo_pago'         => $request->metodo,
                'banco_pago'          => $banco,
                'telefono_pago'       => $telefonoPago,
                'referencia_pago'     => $referencia,
                'tipo_envio'          => $request->tipo_envio,
                'agencia_envio'       => 'local', 
                'direccion_envio'     => [
                     'calle' => $request->tipo_envio === 'retiro_tienda' ? 'Retiro en Tienda' : ($request->sector . ', ' . $request->calle),
                     'ciudad' => $request->tipo_envio === 'retiro_tienda' ? 'Guanare' : ($request->ciudad ?? 'Guanare'),
                     'estado' => 'Portuguesa',
                     'zona' => $request->tipo_envio === 'delivery' ? 'Parroquia Guanare' : 'Retiro en Tienda',
                     'coordenadas' => $request->latitud && $request->longitud ? [
                         'lat' => $request->latitud,
                         'lng' => $request->longitud
                     ] : null,
                ],
            ]);
            
            // Asignar el recibo a la orden
            if ($rutaComprobante) {
                // Si preferimos guardarlo como un campo en la orden o en notificaciones.
                // Usaremos un campo dinámico o extendiendo el fillable de Orden en el futuro,
                // Pero como es estricto de 12 tablas, el comprobante puede guardarse como anotacion_pago.
                // Como alternativa, podemos guardarlo en el array direccion_envio como metadata
                $dirArray = $orden->direccion_envio;
                $dirArray['comprobante_url'] = $rutaComprobante;
                $orden->direccion_envio = $dirArray;
                $orden->save();
            }

            $subtotalVentaCalculado = 0;

            foreach ($cartData as $item) {
                // Validación Estricta
                $variante = ProductoVariante::with('producto')->where('id', $item['id'])->first();

                if (!$variante) {
                    throw new \Exception("Un producto del carrito ya no existe en la base de datos.");
                }

                $factorConversion = 1;
                $empaqueId = $item['empaque_id'] ?? null;
                $precioGuardar = $variante->precio;

                // Si se selecciona empaque, el checkout de cart.js buscará los factores de conversión del empaque hijo.
                if ($empaqueId) {
                    $empaque = ProductoVariante::find($empaqueId);
                    if ($empaque && $empaque->parent_id === $variante->id) {
                        $factorConversion = $empaque->factor_conversion;
                        $precioGuardar = $empaque->en_oferta ? $empaque->precio_con_descuento : $empaque->precio;
                        // El empaque es ahora la variante definitiva a registrar.
                        $varianteIdReal = $empaque->id;
                    } else {
                        $varianteIdReal = $variante->id;
                        $factorConversion = $variante->factor_conversion ?: 1;
                        $precioGuardar = $variante->en_oferta ? $variante->precio_con_descuento : $variante->precio;
                    }
                } else {
                    $varianteIdReal = $variante->id;
                    $factorConversion = $variante->factor_conversion ?: 1;
                    $precioGuardar = $variante->en_oferta ? $variante->precio_con_descuento : $variante->precio;
                }

                $cantidadComprada = $item['cantidad'];
                $cantidadUnidadesBaseAfectadas = $cantidadComprada * $factorConversion;

                // Validación de stock verificando sobre el PADRE o sobre la misma variante si no tiene padre.
                $varianteBase = $variante->parent_id ? ProductoVariante::find($variante->parent_id) : $variante;

                if ($varianteBase->stock_base < $cantidadUnidadesBaseAfectadas) {
                    $nombre = $varianteBase->producto->nombre ?? 'Producto';
                    throw new \Exception("No hay suficiente stock para: {$nombre}. " . ($empaqueId ? 'Recuerde que el empaque equivalen a '.$factorConversion.' unidades.' : ''));
                }

                $subtotal = $precioGuardar * $cantidadComprada;
                $subtotalVentaCalculado += $subtotal;

                // Crear el Detalle
                OrdenDetalle::create([
                    'orden_id'       => $orden->id,
                    'variante_id'    => $varianteIdReal,
                    'cantidad'       => $cantidadComprada,
                    'precio_unitario' => $precioGuardar,
                    'subtotal'       => $subtotal,
                ]);

                // Modificar Stock Real
                $varianteBase->stock_base = $varianteBase->stock_base - $cantidadUnidadesBaseAfectadas;
                $varianteBase->save();

                // Generar InventarioLog 
                InventarioLog::create([
                    'variante_id'     => $varianteBase->id,
                    'orden_id'        => $orden->id,
                    'cantidad_cambio' => -$cantidadUnidadesBaseAfectadas,
                    'motivo'          => 'Venta Web #' . $orden->id,
                ]);
            }

            $iva = round($subtotalVentaCalculado * 0.16, 2);
            
            $deliveryFee = 0.00;
            if ($request->tipo_envio === 'delivery') {
                $zone2Regex = '/sucre|colombia|san jos[eé]|las flores|san rafael|falc[oó]n|milenio|santa rita|pr[oó]ceres|coromotana|guanaguanare|italven|san francisco|enriquera|el placer|arenosa|terminal|garzas|4 de febrero|4f|traki|granja|ceiba|pinos|hato modelo|progreso|nazareno|divino ni[ñn]o|nuestro guanare|cafi caf[eé]|buenos aires|guaicaipuro|pastora|12 de octubre|bolivariano|san antonio|am[eé]ricas|brisas|portugal|temaca|bolsillo|canales|tanques|guasimitos|cocos|panelas|cocuizas|quebrada del mam[oó]n/i';
                $calleTexto = ($request->sector ?? '') . ' ' . ($request->calle ?? '');
                $deliveryFee = preg_match($zone2Regex, $calleTexto) ? 2.00 : 1.00;
            }

            $totalAmountCalculado = $subtotalVentaCalculado + $iva + $deliveryFee;

            // Lógica de Abonos (ERP Estricto)
            $monto_abonado = $totalAmountCalculado; // Default: Paga completo
            $estadoInicial = 'pendiente';

            if ($request->filled('is_abono') && $request->is_abono == '1') {
                if ($totalAmountCalculado <= 30) {
                    throw new \Exception("El sistema de pago a crédito solo está disponible para compras superiores a $30 USD.");
                }
                if ($request->tipo_envio !== 'retiro_tienda') {
                    throw new \Exception("Los pagos a crédito solo son válidos para Retiro en Tienda.");
                }
                
                $montoDeclarado = floatval($request->monto_abonar);
                $minimoRequerido = $totalAmountCalculado * 0.30;
                
                if ($montoDeclarado < $minimoRequerido) {
                    throw new \Exception("Su pago inicial debe ser al menos el 30% del total ($" . number_format($minimoRequerido, 2) . ").");
                }

                $monto_abonado = $montoDeclarado;
                $estadoInicial = 'apartado'; // Se asume apartado hasta que se verifique y cancele el resto. Sin embargo, lo mantendremos en 'pendiente' para que pase por verificación, y lo sabremos porque $monto_abonado < $totalAmountCalculado.
                $estadoInicial = 'pendiente'; // Mejor dejarlo en pendiente para el flujo de Verificación.
            }

            // Actualizar Total ACID.
            $orden->update([
                'subtotal'     => $subtotalVentaCalculado,
                'iva_amount'   => $iva,
                'delivery_fee' => $deliveryFee,
                'total_amount' => $totalAmountCalculado,
                'monto_abonado' => $monto_abonado
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
