<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #333; margin: 0; padding: 20px; }
        .header { width: 100%; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header table { width: 100%; }
        .logo { font-size: 24px; font-weight: bold; color: #000; }
        .invoice-details { text-align: right; }
        .customer-info { margin-bottom: 30px; }
        .customer-info p { margin: 5px 0; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { background-color: #f3f4f6; padding: 10px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        .items-table td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
        .items-table .text-right { text-align: right; }
        .totals-table { width: 100%; border-collapse: collapse; }
        .totals-table td { padding: 5px 10px; text-align: right; }
        .totals-table .total-row { font-size: 18px; font-weight: bold; border-top: 2px solid #000; padding-top: 10px; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #666; }
        .qr-container { margin-top: 30px; text-align: center; }
        .qr-code { width: 100px; height: 100px; }
    </style>
</head>
<body>

<div class="header">
    <table border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="50%">
                <div class="logo">STITCH & CO.</div>
                <p>Moda y Estilo</p>
                <p>RIF: J-00000000-0</p>
            </td>
            <td width="50%" class="invoice-details">
                <h2>FACTURA</h2>
                <p><strong>Nro:</strong> {{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Fecha:</strong> {{ $venta->created_at->format('d/m/Y') }}</p>
                <p><strong>Estado:</strong> {{ strtoupper($venta->estado) }}</p>
            </td>
        </tr>
    </table>
</div>

<div class="customer-info">
    <h3>Datos del Cliente</h3>
    @if($venta->user)
        <p><strong>Nombre:</strong> {{ $venta->user->nombre }} {{ $venta->user->apellido }}</p>
        <p><strong>Email:</strong> {{ $venta->user->email }}</p>
        <p><strong>Cédula/RIF:</strong> {{ $venta->user->document_type ?? '' }}{{ $venta->user->document_number ?? 'N/A' }}</p>
        <p><strong>Teléfono:</strong> {{ $venta->user->telefono ?? 'N/A' }}</p>
    @else
        <p><strong>Cliente:</strong> Invitado</p>
    @endif
</div>

<table class="items-table">
    <thead>
        <tr>
            <th width="50%">Producto</th>
            <th width="10%">Cant.</th>
            <th width="20%" class="text-right">Precio Unit.</th>
            <th width="20%" class="text-right">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venta->detalles as $detalle)
        <tr>
            <td>
                {{ $detalle->variante->producto->nombre ?? 'N/A' }}
                @if($detalle->variante && $detalle->variante->color)
                    <br><small>Color: {{ $detalle->variante->color }}</small>
                @endif
            </td>
            <td>{{ $detalle->cantidad }}</td>
            <td class="text-right">
                ${{ number_format((float) $detalle->precio_unitario, 2) }}<br>
                <small>Bs {{ number_format((float) $detalle->precio_unitario * (float) ($venta->tasa_bcv_aplicada ?? bcv_rate()), 2, ',', '.') }}</small>
            </td>
            <td class="text-right">
                ${{ number_format((float) $detalle->subtotal, 2) }}<br>
                <small>Bs {{ number_format((float) $detalle->subtotal * (float) ($venta->tasa_bcv_aplicada ?? bcv_rate()), 2, ',', '.') }}</small>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<table width="100%">
    <tr>
        <td width="50%" valign="top">
            <div class="qr-container">
                <img src="data:image/svg+xml;base64,{{ $qrCode }}" class="qr-code">
                <p style="font-size: 10px; color: #666;">Escanear para validar</p>
            </div>
        </td>
        <td width="50%">
            <table class="totals-table">
                <tr>
                    <td>Subtotal:</td>
                    <td>${{ number_format((float) $venta->total_venta, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>TOTAL USD:</td>
                    <td>${{ number_format((float) $venta->total_venta, 2) }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px;">Total Bs (Tasa: {{ number_format((float) ($venta->tasa_bcv_aplicada ?? bcv_rate()), 2, ',', '.') }}):</td>
                    <td style="font-weight: bold;">Bs {{ number_format((float) $venta->total_venta * (float) ($venta->tasa_bcv_aplicada ?? bcv_rate()), 2, ',', '.') }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div class="footer">
    <p>Gracias por su compra en Stitch & Co.</p>
    <p>Esta factura fue generada automáticamente y es válida como comprobante de compra.</p>
</div>

</body>
</html>
