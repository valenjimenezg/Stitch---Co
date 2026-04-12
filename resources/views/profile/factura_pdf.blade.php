<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura Pedido #{{ $venta->id }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; margin: 0; padding: 20px; }
        .header { width: 100%; margin-bottom: 40px; }
        .header td { vertical-align: top; }
        .logo-title { font-size: 28px; font-weight: bold; color: #7c3aed; margin-bottom: 5px; }
        .company-details { color: #666; font-size: 12px; line-height: 1.5; }
        .invoice-title { font-size: 24px; font-weight: bold; color: #ccc; text-transform: uppercase; text-align: right; margin-bottom: 5px; }
        .invoice-details { text-align: right; font-size: 12px; color: #555; }
        
        .bill-to { background-color: #f8fafc; padding: 20px; border: 1px solid #e2e8f0; border-radius: 5px; margin-bottom: 40px; width: 100%; border-collapse: collapse; }
        .bill-to td { vertical-align: top; width: 50%; }
        .section-label { font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .customer-name { font-weight: bold; font-size: 16px; margin: 0 0 5px 0; color: #1e293b; }
        .customer-info { margin: 0; font-size: 13px; color: #64748b; line-height: 1.4; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .items-table th { border-bottom: 2px solid #e2e8f0; padding: 10px 0; text-align: left; font-size: 12px; color: #475569; text-transform: uppercase; }
        .items-table th.center { text-align: center; }
        .items-table th.right { text-align: right; }
        .items-table td { padding: 15px 0; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        .item-name { font-weight: bold; color: #1e293b; margin: 0 0 5px 0; font-size: 13px; }
        .item-meta { font-size: 11px; color: #64748b; margin: 0; }
        .items-table td.center { text-align: center; }
        .items-table td.right { text-align: right; }
        
        .totals-wrapper { width: 100%; margin-bottom: 40px; }
        .totals-table { width: 350px; float: right; border-collapse: collapse; }
        .totals-table td { padding: 10px 0; }
        .totals-table .label { font-weight: bold; color: #64748b; font-size: 13px; }
        .totals-table .value { text-align: right; font-weight: bold; color: #1e293b; font-size: 13px; }
        .totals-table .total-row td { border-top: 2px solid #e2e8f0; padding-top: 15px; font-size: 18px; }
        .totals-table .total-value { color: #7c3aed; }
        
        .footer { clear: both; margin-top: 80px; padding-top: 20px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 11px; color: #94a3b8; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td>
                <div class="logo-title">STITCH & CO</div>
                <div class="company-details">
                    C.C. Los Ilustres, Local 4, Guanare, VE<br>
                    RIF: J-12345678-9 | Tel: +58 424 5659154
                </div>
            </td>
            <td>
                <div class="invoice-title">Factura</div>
                <div class="invoice-details">
                    <b>{{ $venta->invoice_number ?: 'ORD-' . str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</b><br>
                    Fecha: {{ $venta->created_at->format('d/m/Y') }}
                </div>
            </td>
        </tr>
    </table>

    <table class="bill-to">
        <tr>
            <td>
                <div class="section-label">Facturar a:</div>
                <p class="customer-name">{{ $venta->user->nombre }} {{ $venta->user->apellido }}</p>
                <p class="customer-info">{{ $venta->user->email }}<br>
                @if($venta->user->telefono) {{ $venta->user->telefono }} @endif</p>
            </td>
            <td style="text-align: right;">
                <div class="section-label">Método de Pago:</div>
                <p class="customer-name">{{ strtoupper(str_replace('_', ' ', $venta->metodo_pago)) }}</p>
                @if($venta->referencia_pago)
                    <p class="customer-info" style="margin-top: 5px;">Ref: {{ $venta->referencia_pago }}</p>
                @endif
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th class="center" style="width: 10%;">Cant</th>
                <th class="right" style="width: 25%;">Precio</th>
                <th class="right" style="width: 25%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->detalles as $detalle)
            <tr>
                <td>
                    <p class="item-name">{{ $detalle->variante->producto->nombre ?? 'Producto' }}</p>
                    @if($detalle->variante->color || $detalle->variante->talla)
                        <p class="item-meta">Color: {{ $detalle->variante->color }} | Talla: {{ $detalle->variante->talla }}</p>
                    @endif
                </td>
                <td class="center">{{ $detalle->cantidad }}</td>
                <td class="right">Bs. {{ number_format((float)($detalle->precio_unitario ?? 0), 2) }}</td>
                <td class="right" style="font-weight: bold;">Bs. {{ number_format((float)($detalle->subtotal ?? 0), 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-wrapper">
        <table class="totals-table">
            <tr>
                <td class="label">Subtotal</td>
                <td class="value">{{ bs($venta->total_amount ?? 0, false, $venta->tasa_bcv_aplicada) }}</td>
            </tr>
            <tr class="total-row">
                <td class="label" style="font-size: 18px; color:#1e293b;">TOTAL</td>
                <td class="value total-value">{{ bs($venta->total_amount ?? 0, false, $venta->tasa_bcv_aplicada) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p style="font-weight: bold; margin-bottom: 5px;">¡Gracias por tu compra en Stitch & Co!</p>
        <p>Este documento es un comprobante de pago digital válido emitido el {{ now()->format('d/m/Y H:i') }}.</p>
    </div>
</body>
</html>
