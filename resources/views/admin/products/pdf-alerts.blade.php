<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte General de Quiebre de Inventario - Gerencia</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; margin: 0; padding: 20px; color: #333; font-size: 12px; }
        .header { width: 100%; border-bottom: 2px solid #e11d48; padding-bottom: 10px; margin-bottom: 20px; }
        .header table { width: 100%; border-collapse: collapse; }
        .header td { vertical-align: bottom; }
        .brand h1 { margin: 0; font-size: 24px; color: #e11d48; }
        .brand p { margin: 2px 0 0 0; font-size: 11px; color: #666; font-weight: bold; text-transform: uppercase; }
        .meta { text-align: right; font-size: 11px; color: #666; }
        .meta strong { color: #000; }
        
        .supplier-block { margin-bottom: 30px; }
        .supplier-title { background: #f1f5f9; padding: 10px; border-left: 4px solid #94a3b8; font-size: 14px; font-weight: bold; color: #334155; margin-bottom: 10px; text-transform: uppercase;}
        .supplier-info { font-size: 10px; color: #64748b; margin-top: -5px; margin-bottom: 15px; padding-left: 10px; }

        table.items { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.items th { text-align: left; padding: 8px; background: #fafafa; border-bottom: 1px solid #ddd; font-size: 10px; text-transform: uppercase; color: #555; }
        table.items td { padding: 8px; border-bottom: 1px solid #eee; font-size: 11px; }
        
        .stock-critical { color: #e11d48; font-weight: bold; }
        .est-cost { font-weight: bold; color: #0f172a; }

        .summary-box { border: 1px dashed #ccc; background: #fffbeb; padding: 15px; margin-top: 30px; border-radius: 5px; }
        .summary-box h3 { margin: 0 0 5px 0; color: #d97706; font-size: 14px; }

        .footer { margin-top: 50px; text-align: center; color: #999; font-size: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td class="brand" style="width: 60%;">
                    <h1>Stitch & Co. - GERENCIA</h1>
                    <p>Reporte Consolidado de Inventario Crítico</p>
                </td>
                <td class="meta" style="width: 40%;">
                    <p>Fecha de Emisión: <strong>{{ date('d/m/Y h:i A') }}</strong></p>
                    <p>Documento Nº: <strong>GER-{{ date('Ymd') }}-STOCK</strong></p>
                </td>
            </tr>
        </table>
    </div>

    <p style="font-size: 11px; margin-bottom: 20px; line-height: 1.5; color: #475569;">
        El presente documento detalla el estado actual de los rubros que han alcanzado su límite operativo (Merma) o se encuentran totalmente agotados. 
        Se agrupa por Proveedor sugerido y se incluye un estimado referencial del costo individual para proyección contable.
    </p>

    @php
        $totalGeneralEstimado = 0;
        $totalItems = 0;
    @endphp

    @foreach($agotados as $proveedor_id => $variantes)
        @php
            $proveedor = $variantes->first()->proveedor;
            $nombreProv = $proveedor ? $proveedor->nombre : 'PROVEEDOR NO ASIGNADO / VARIOS';
            $infoProv = $proveedor ? "Contacto: {$proveedor->telefono} | Email: {$proveedor->email}" : "Asignar en sistema";
        @endphp

        <div class="supplier-block">
            <div class="supplier-title">{{ $nombreProv }}</div>
            <div class="supplier-info">{{ $infoProv }}</div>

            <table class="items">
                <thead>
                    <tr>
                        <th style="width: 15%;">Código</th>
                        <th style="width: 35%;">Producto / Especificación</th>
                        <th style="width: 15%;">Categoría</th>
                        <th style="width: 15%; text-align: center;">Estado Actual</th>
                        <th style="width: 20%; text-align: right;">Costo Ref. (Unid)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($variantes as $item)
                        @php 
                            $totalItems++; 
                            $precioSugerido = $item->precio > 0 ? $item->precio : ($item->producto->precio ?? 0);
                        @endphp
                        <tr>
                            <td style="font-family: monospace; color: #64748b;">VAR-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <strong>{{ $item->producto->nombre ?? 'N/A' }}</strong><br>
                                <span style="font-size: 9px; color: #64748b;">
                                    {{ $item->color ?? '' }} 
                                    @if($item->grosor) | {{ $item->grosor }} @endif
                                    @if($item->talla) | Talla: {{ $item->talla }} @endif
                                </span>
                            </td>
                            <td style="font-size: 10px;">{{ $item->producto->categoria->nombre ?? 'General' }}</td>
                            <td style="text-align: center;">
                                @if($item->stock_base <= 0)
                                    <span class="stock-critical">AGOTADO (0)</span>
                                @else
                                    <span style="color: #ea580c; font-weight: bold;">CRÍTICO ({{ $item->stock_base }})</span>
                                @endif
                            </td>
                            <td style="text-align: right;" class="est-cost">
                                ${{ number_format($precioSugerido, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="summary-box">
        <h3>Resumen de Requisición</h3>
        <p style="margin: 0; font-size: 12px; color: #78350f;">
            <strong>Total de Variantes Agotadas/En Merma:</strong> {{ $totalItems }} items a reponer distribuidos en {{ count($agotados) }} perfil(es) de proveedor.<br>
            <span style="font-size: 10px; color: #92400e; display: block; margin-top: 5px;">* Nota: El costo listado es referencial de venta/compra en sistema y no representa cotización real expedida por el proveedor. Solicitar actualización a los contactos anexos.</span>
        </p>
    </div>

    <div class="footer">
        <p style="margin-bottom: 25px;">_________________________________________<br><strong style="color: #333;">Autorización - Gerencia General</strong></p>
        <p>Stitch & Co. - Informe de Uso Interno - Generado el {{ date('d/m/Y') }}</p>
    </div>

</body>
</html>
