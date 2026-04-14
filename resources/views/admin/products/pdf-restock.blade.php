<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Orden de Reposición - {{ $proveedor->nombre }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; margin: 0; padding: 20px; color: #333; font-size: 12px; }
        .header { width: 100%; border-bottom: 2px solid #8b52ff; padding-bottom: 10px; margin-bottom: 20px; }
        .header table { width: 100%; border-collapse: collapse; }
        .header td { vertical-align: bottom; }
        .brand h1 { margin: 0; font-size: 24px; color: #8b52ff; }
        .brand p { margin: 2px 0 0 0; font-size: 11px; color: #666; font-weight: bold; text-transform: uppercase; }
        .meta { text-align: right; font-size: 11px; color: #666; }
        .meta strong { color: #000; }
        
        .box { border: 1px solid #ddd; padding: 15px; background: #fdfdfd; margin-bottom: 20px; }
        .box h3 { margin: 0 0 5px 0; font-size: 14px; color: #333; }
        .box p { margin: 0; font-size: 12px; color: #555; line-height: 1.4; }

        table.items { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.items th { text-align: left; padding: 10px; background: #f4f4f4; border-bottom: 1px solid #ccc; font-size: 11px; text-transform: uppercase; color: #555; }
        table.items td { padding: 10px; border-bottom: 1px solid #eee; font-size: 12px; }
        
        .stock-critical { color: #d00; font-weight: bold; }
        .suggested-qty { width: 50px; border-bottom: 1px solid #000; display: inline-block; }

        .footer { margin-top: 50px; text-align: center; color: #999; font-size: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td class="brand" style="width: 50%;">
                    <h1>Stitch & Co.</h1>
                    <p>Requisición / Orden de Compra</p>
                </td>
                <td class="meta" style="width: 50%;">
                    <p>Fecha Cierre: <strong>{{ date('d/m/Y') }}</strong></p>
                    <p>Documento Nº: <strong>REQ-{{ date('Ymd') }}-{{ $proveedor->id }}</strong></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="box">
        <h3>Datos del Proveedor</h3>
        <p>
            <strong>Empresa:</strong> {{ $proveedor->nombre }}<br>
            <strong>Documento/RIF:</strong> {{ $proveedor->tipo_documento ?? 'J' }}-{{ $proveedor->documento_identidad ?? 'N/A' }}<br>
            <strong>Contacto:</strong> {{ $proveedor->telefono ?? 'No registrado' }} | {{ $proveedor->email ?? 'No registrado' }}
        </p>
    </div>

    <p style="font-size: 12px; margin-bottom: 10px;">Estimado proveedor, solicitamos cotización y disponibilidad de los siguientes productos que se encuentran agotados o en merma:</p>

    <table class="items">
        <thead>
            <tr>
                <th>Código</th>
                <th>Producto / Categoría</th>
                <th>Especificaciones</th>
                <th>Inv. Actual</th>
                <th style="width: 80px; text-align: center;">Cant. Solicitada</th>
            </tr>
        </thead>
        <tbody>
            @foreach($agotados as $item)
            <tr>
                <td style="font-family: monospace; color: #777;">VAR-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <strong>{{ $item->producto->nombre ?? 'N/A' }}</strong><br>
                    <span style="color: #888; font-size: 10px;">{{ $item->producto->categoria->nombre ?? 'General' }}</span>
                </td>
                <td>
                    {{ $item->color ?? '—' }} 
                    @if($item->grosor) <span style="color: #ccc;">|</span> {{ $item->grosor }} @endif
                    @if($item->marca) <br><span style="font-size: 9px; color: #888;">MARCA: {{ strtoupper($item->marca) }}</span> @endif
                </td>
                <td>
                    @if($item->stock_base <= 0)
                        <span class="stock-critical">0 (AGOTADO)</span>
                    @else
                        {{ $item->stock_base }} Unid.
                    @endif
                </td>
                <td style="text-align: center;">
                    <div class="suggested-qty">&nbsp;</div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p style="margin-bottom: 30px;">_________________________________________<br><strong style="color: #333;">Firma Autorizada - Compras</strong></p>
        <p>Documento generado por el sistema automatizado de Stitch & Co. Guanare, Portuguesa.</p>
    </div>

</body>
</html>
