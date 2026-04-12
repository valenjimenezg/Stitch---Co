<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>
    body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f8fafc; color: #334155; margin: 0; padding: 20px; }
    .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    .header { background: #0F172A; padding: 30px 40px; text-align: center; color: white; }
    .header h1 { margin: 0; font-size: 24px; letter-spacing: 1px; }
    .header p { margin: 5px 0 0 0; color: #94A3B8; font-size: 14px; }
    .content { padding: 40px; }
    .greeting { font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #0F172A; }
    .order-meta { background: #f1f5f9; padding: 15px; border-radius: 8px; margin-bottom: 30px; font-size: 14px; }
    .order-meta p { margin: 5px 0; }
    table { border-collapse: collapse; margin-bottom: 30px; font-size: 14px; width: 100%; }
    th { text-align: left; padding: 12px 0; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: bold; }
    td { padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
    .total-row td { font-weight: bold; font-size: 18px; color: #0F172A; border-bottom: none; padding-top: 20px; }
    .footer { text-align: center; padding: 30px; font-size: 12px; color: #94A3B8; background: #f8fafc; border-top: 1px solid #e2e8f0; }
    .text-right { text-align: right; }
    @media print {
        body { background: white !important; padding: 0 !important; }
        .container { box-shadow: none !important; border: none !important; max-width: 100% !important; margin: 0 !important; }
        button { display: none !important; }
    }
</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>STITCH &amp; CO</h1>
            <p>Factura de Compra</p>
        </div>
        <div class="content">
            <div class="greeting">¡Hola, {{ $venta->nombre_cliente ?? ($venta->user->nombre ?? 'Cliente') }}!</div>
            <p style="font-size: 14px; line-height: 1.6; color: #475569;">Gracias por tu compra. Tu pedido ha sido procesado de forma exitosa y aquí tienes tu comprobante oficial electrónico.</p>

            <table class="order-meta" style="width: 100%; border: none; padding: 15px; background: #f1f5f9; border-radius: 8px; margin-bottom: 30px;">
                <tr>
                    <td style="border: none; padding: 0; vertical-align: middle;">
                        <p style="margin: 5px 0;"><strong>Factura N°:</strong> #{{ str_pad($venta->id, 5, '0', STR_PAD_LEFT) }}</p>
                        <p style="margin: 5px 0;"><strong>Fecha:</strong> {{ $venta->created_at->format('d/m/Y h:i A') }}</p>
                        <p style="margin: 5px 0;"><strong>Método de Pago:</strong> {{ strtoupper(str_replace('_', ' ', $venta->metodo_pago ?? 'Tienda')) }}</p>
                        <p style="margin: 5px 0;"><strong>Método de Envío:</strong> {{ strtoupper(str_replace('_', ' ', $venta->tipo_envio ?? 'retiro_tienda')) }} @if($venta->agencia_envio) - Vía {{ $venta->agencia_envio }} @endif</p>
                        @if($venta->referencia_pago)
                            <p style="margin: 5px 0;"><strong>Referencia:</strong> {{ $venta->referencia_pago }}</p>
                        @endif
                        <p style="margin: 5px 0;"><strong>Estado:</strong> {{ strtoupper($venta->estado) }}</p>
                    </td>
                    <td style="border: none; padding: 0; text-align: right; vertical-align: middle;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('profile.orders.invoice', $venta->id)) }}" alt="Validación QR" width="100" height="100" style="border-radius: 8px; display: inline-block;">
                        <p style="font-size: 10px; color: #94A3B8; margin-top: 5px; margin-bottom: 0;">Escanear Validación</p>
                    </td>
                </tr>
            </table>

            <table>
                <thead>
                    <tr>
                        <th>Artículo</th>
                        <th class="text-right">Cant.</th>
                        <th class="text-right">Unidad (Bs.)</th>
                        <th class="text-right">Subtotal (Bs.)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($venta->detalles as $detalle)
                    <tr>
                        <td>
                            <strong>{{ $detalle->variante->producto->nombre ?? 'Producto' }}</strong><br>
                            <span style="color: #64748b; font-size: 12px;">{{ $detalle->variante->color ?? '' }} {{ $detalle->variante->talla ?? '' }}</span>
                        </td>
                        <td class="text-right">{{ $detalle->cantidad }}</td>
                        <td class="text-right">{{ bs($detalle->precio_unitario, false, $venta->tasa_bcv_aplicada) }}</td>
                        <td class="text-right">{{ bs($detalle->subtotal, false, $venta->tasa_bcv_aplicada) }}</td>
                    </tr>
                    @endforeach
                    @if($venta->costo_envio > 0)
                    <tr>
                        <td colspan="3" class="text-right" style="padding-right: 15px; color: #64748b;">Delivery Local (Guanare)</td>
                        <td class="text-right">{{ bs($venta->costo_envio ?? 0, false, $venta->tasa_bcv_aplicada) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td colspan="3" class="text-right" style="padding-right: 15px;">TOTAL A PAGAR</td>
                        <td class="text-right" style="color: #0F172A; font-weight: bold;">{{ bs($venta->total_amount, false, $venta->tasa_bcv_aplicada) }}</td>
                    </tr>
                </tbody>
            </table>
            
            <p style="font-size: 13px; color: #64748b;">Enviaremos tu pedido a: <strong>{{ $venta->direccion ?? 'Retiro en Tienda / Confirmar con soporte' }}</strong>.</p>
            
            <div style="text-align: center; margin-top: 35px;">
                @if(isset($print_mode))
                    <button onclick="window.print()" style="background: #0F172A; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                        Imprimir / Descargar Factura
                    </button>
                @else
                    <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('profile.orders.invoice', $venta->id) }}" style="display: inline-block; background: #0F172A; color: white; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold;">
                        Ver Comprobante Web o Imprimir
                    </a>
                @endif
            </div>

        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Stitch &amp; Co Haberdashery. RIF: J-40123456-7</p>
            <p>Este es un comprobante electrónico y tiene completa validez tributaria ante las autoridades competentes.</p>
        </div>
    </div>
</body>
</html>
