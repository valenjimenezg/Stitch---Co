<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ($orden->monto_abonado > 0 && $orden->monto_abonado < $orden->total_amount) ? 'Tu Ticket de Abono' : 'Tu Factura' }}</title>
    <style>
        body { margin: 0; padding: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #1e293b; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05); }
        .header { background-color: #8b52ff; padding: 40px 30px; text-align: center; }
        .logo { font-size: 28px; font-weight: 900; color: #ffffff; letter-spacing: -0.5px; margin: 0; }
        .logo span { font-weight: 300; opacity: 0.8; }
        .content { padding: 40px 30px; }
        h1 { margin-top: 0; font-size: 24px; color: #0f172a; font-weight: 800; }
        p { font-size: 16px; line-height: 1.6; color: #475569; margin-bottom: 24px; }
        .invoice-card { border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: left; margin-bottom: 30px; background-color: #f8fafc; }
        .invoice-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; }
        .invoice-row.total { font-weight: bold; border-bottom: none; font-size: 18px; color: #0f172a; margin-top: 10px; margin-bottom: 0px; padding-bottom: 0px; }
        .cta-button { display: inline-block; background-color: #8b52ff; color: #ffffff; text-decoration: none; padding: 16px 32px; border-radius: 50px; font-weight: bold; font-size: 16px; transition: all 0.2s; }
        .footer { background-color: #0f172a; padding: 30px; text-align: center; color: #94a3b8; font-size: 13px; }
        .footer a { color: #8b52ff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h2 class="logo">Stitch <span>&amp;</span> Co.</h2>
        </div>

        <!-- Content -->
        <div class="content">
            <h1>{{ ($orden->monto_abonado > 0 && $orden->monto_abonado < $orden->total_amount) ? '¡Recibimos tu abono! ✅' : '¡Tu Pedido ha sido pagado! 🧾' }}</h1>
            <p>Hola {{ $orden->user->nombre ?? 'Cliente' }}, gracias por comprar en Stitch & Co.</p>
            
            @if($orden->monto_abonado > 0 && $orden->monto_abonado < $orden->total_amount)
                <p>Hemos procesado exitosamente tu pago y reservado tu pedido. A continuación te adjuntamos el acceso a tu <strong>Ticket de Abono Digital</strong> (apartado).</p>
            @else
                <p>Hemos procesado exitosamente tu pago. A continuación te adjuntamos el acceso directo a tu <strong>Factura Comercial Digital</strong> detallada.</p>
            @endif

            <!-- Mini Resumen -->
            <div class="invoice-card">
                <div class="invoice-row">
                    <span>Pedido Nº:</span>
                    <strong>#{{ str_pad($orden->id, 5, '0', STR_PAD_LEFT) }}</strong>
                </div>
                <div class="invoice-row">
                    <span>Estatus:</span>
                    <strong style="text-transform: capitalize;">{{ $orden->estado }}</strong>
                </div>
                <div class="invoice-row total">
                    <span>Total de la Compra:</span>
                    <span>${{ number_format((float) $orden->total_amount, 2) }}</span>
                </div>
                @if($orden->monto_abonado > 0 && $orden->monto_abonado < $orden->total_amount)
                <div class="invoice-row" style="border-bottom: none; border-top: 1px dashed #cbd5e1; padding-top: 10px; margin-top: 10px;">
                    <span style="color: #ef4444; font-weight: bold;">Saldo Deudor (Restante):</span>
                    <strong style="color: #ef4444;">${{ number_format(($orden->total_amount - $orden->monto_abonado), 2) }}</strong>
                </div>
                @endif
            </div>

            <p style="text-align: center; font-size: 14px;">Puedes descargar, conservar o imprimir tu factura oficial haciendo clic en el botón a continuación:</p>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ $invoiceUrl }}" class="cta-button" target="_blank">📄 Ver y Descargar Factura (PDF)</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Si tienes alguna duda sobre tu compra, responde a este correo o contáctanos vía WhatsApp.</p>
            <p>&copy; {{ date('Y') }} Stitch & Co. Haberdashery. Todos los derechos reservados.</p>
            <p><a href="{{ url('/') }}">Visita nuestra tienda</a></p>
        </div>
    </div>
</body>
</html>
