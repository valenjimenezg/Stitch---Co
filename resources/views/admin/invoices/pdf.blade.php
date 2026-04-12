<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nota de Entrega #{{ $order->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        .header td {
            vertical-align: top;
        }
        .company-info h1 {
            margin: 0;
            color: #0f172a;
            font-size: 24px;
        }
        .company-info p {
            margin: 5px 0 0;
            color: #64748b;
            font-size: 13px;
        }
        .invoice-details {
            text-align: right;
        }
        .invoice-details h2 {
            margin: 0;
            color: #3b82f6;
            font-size: 20px;
            text-transform: uppercase;
        }
        .invoice-details p {
            margin: 5px 0 0;
            font-size: 13px;
            color: #475569;
        }
        .customer-info {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .customer-info h3 {
            margin: 0 0 10px;
            font-size: 15px;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        .customer-info p {
            margin: 5px 0;
            font-size: 13px;
        }
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table-items th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #cbd5e1;
        }
        .table-items td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
        }
        .table-items td.text-right,
        .table-items th.text-right {
            text-align: right;
        }
        .totals {
            width: 100%;
            border-collapse: collapse;
        }
        .totals td {
            padding: 8px 12px;
            font-size: 14px;
        }
        .totals .total-row td {
            font-weight: bold;
            font-size: 16px;
            color: #0f172a;
            border-top: 2px solid #cbd5e1;
            padding-top: 15px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td class="company-info">
                    <h1>Stitch & Co.</h1>
                    <p>La mejor selección en artículos de mercería</p>
                </td>
                <td class="invoice-details">
                    <h2>Nota de Entrega</h2>
                    <p><strong>N°:</strong> {{ $order->invoice_number ?? 'Borrador' }}</p>
                    <p><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
                    <p><strong>Método de Entrega:</strong> 
                        @if($order->delivery_method == 'store_pickup' || $order->tipo_envio == 'retiro_tienda') 
                            Retiro en Tienda 
                        @elseif($order->delivery_method == 'local_delivery' || $order->tipo_envio == 'delivery_local') 
                            Delivery Local 
                        @else 
                            Agencia de Envío 
                        @endif
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div class="customer-info">
        <h3>Datos del Cliente</h3>
        <p><strong>Nombre:</strong> {{ $order->user->nombre ?? 'Invitado' }} {{ $order->user->apellido ?? '' }}</p>
        <p><strong>Contacto:</strong> {{ $order->user->email ?? 'N/A' }} | Telf: {{ $order->user->telefono ?? 'N/A' }}</p>
        @if($order->calle_envio)
            <p><strong>Dirección:</strong> {{ $order->calle_envio }}, {{ $order->ciudad_envio }}, {{ $order->estado_envio }} (CP: {{ $order->codigo_postal_envio }})</p>
        @endif
    </div>

    <table class="table-items">
        <thead>
            <tr>
                <th width="15%">Cantidad</th>
                <th width="50%">Descripción del Producto</th>
                <th width="15%" class="text-right">P. Unitario</th>
                <th width="20%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->detalles as $item)
                @php
                    // Determinamos la unidad de medida (ej. 'Metros', 'Unidades', 'Docena')
                    $unidad = $item->variante && $item->variante->unidad_medida ? $item->variante->unidad_medida : 'Uds.';
                    // Ajustamos la lógica de cantidad visual si tiene decimales (ej. 2.5)
                    $cantidadVisual = (int)$item->cantidad == $item->cantidad ? (int)$item->cantidad : number_format($item->cantidad, 2, ',', '.');
                @endphp
                <tr>
                    <td>
                        <strong>{{ $cantidadVisual }}</strong> <span style="font-size: 11px; color: #64748b;">{{ $unidad }}</span>
                    </td>
                    <td>
                        {{ $item->variante->producto->nombre ?? 'Producto Genérico' }}
                        @if($item->variante)
                            <br>
                            <span style="font-size: 11px; color: #64748b;">
                                @if($item->variante->color) Color: {{ $item->variante->color }} @endif
                                @if($item->variante->grosor) | Grosor: {{ $item->variante->grosor }} @endif
                            </span>
                        @endif
                    </td>
                    <td class="text-right">
                        Bs. {{ number_format((float)$item->precio_unitario, 2, ',', '.') }}
                    </td>
                    <td class="text-right font-bold">
                        Bs. {{ number_format((float)$item->subtotal, 2, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals" style="width: 50%; float: right;">
        <tr>
            <td class="text-right" style="text-align: right; color: #64748b;">Subtotal:</td>
            <td class="text-right" style="text-align: right;">Bs. {{ number_format((float)$order->total_amount, 2, ',', '.') }}</td>
        </tr>
        @if($order->costo_envio > 0)
        <tr>
            <td class="text-right" style="text-align: right; color: #64748b;">Costo por Envío:</td>
            <td class="text-right" style="text-align: right;">Bs. {{ number_format((float)$order->costo_envio ?? 0, 2, ',', '.') }}</td>
        </tr>
        @endif
        <tr class="total-row">
            <td class="text-right" style="text-align: right;">Total General:</td>
            <td class="text-right" style="text-align: right;">Bs. {{ number_format((float)$order->total_amount + ($order->costo_envio ?? 0), 2, ',', '.') }}</td>
        </tr>
    </table>
    <div style="clear: both;"></div>

    <div class="footer">
        <p>Esta es una representación impresa de una Nota de Entrega en Punto de Venta.</p>
        <p>¡Gracias por confiar en Stitch & Co. para sus proyectos de costura!</p>
    </div>

</body>
</html>
