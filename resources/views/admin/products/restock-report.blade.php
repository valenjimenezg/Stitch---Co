<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Reporte de Reposición | Stitch & Co</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: 'Inter', sans-serif; margin: 0; padding: 40px; color: #1e293b; background: white; }
        .header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; border-bottom: 2px solid #8b52ff; padding-bottom: 20px; }
        .brand h1 { margin: 0; font-size: 28px; font-weight: 900; color: #8b52ff; display: flex; align-items: center; gap: 8px; }
        .brand p { margin: 5px 0 0 0; font-size: 14px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .meta { text-align: right; font-size: 12px; color: #64748b; }
        .meta strong { color: #0f172a; }
        table { w-full; border-collapse: collapse; margin-top: 20px; width: 100%; }
        th { text-align: left; padding: 12px; background: #f8fafc; border-bottom: 2px solid #e2e8f0; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; }
        td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
        .stock-critical { color: #dc2626; font-weight: bold; }
        .stock-low { color: #d97706; font-weight: bold; }
        .suggested-qty { width: 60px; height: 30px; border: 1px dashed #cbd5e1; display: inline-block; vertical-align: middle; border-radius: 4px; }
        
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
            .page-break { page-break-inside: avoid; }
        }
        
        .print-btn { background: #8b52ff; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer; display: block; margin: 0 auto 30px auto; font-family: 'Inter', sans-serif; font-size: 14px; box-shadow: 0 4px 6px -1px rgba(139,82,255,0.2); transition: 0.2s; }
        .print-btn:hover { background: #7c3aed; }
    </style>
</head>
<body>

    <button type="button" class="print-btn no-print" onclick="window.print()">Imprimir PDF / Hoja de Requisición</button>

    <div class="header">
        <div class="brand">
            <h1>Stitch & Co.</h1>
            <p>Lista de Pedidos Mayoristas / Reposición</p>
        </div>
        <div class="meta">
            <p>Fecha Cierre: <br><strong>{{ date('d/m/Y h:i A') }}</strong></p>
            <p style="margin-top:5px">Documento Nº: <strong>REQ-{{ date('Ymd') }}</strong></p>
        </div>
    </div>

    <div style="margin-bottom: 20px; border-left: 4px solid #ef4444; padding-left: 15px;">
        <h2 style="margin:0 0 5px 0; font-size:16px; color:#0f172a;">Planilla de Abastecimiento Crítico</h2>
        <p style="margin:0; font-size:13px; color:#64748b;">Los siguientes artículos han caído por debajo del umbral mínimo de seguridad (5 unidades) y requieren ser encargados al proveedor inmediatamente para evitar quiebres comerciales.</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Código M/L</th>
                <th>Línea / Producto</th>
                <th>Especificaciones (Color / Grosor)</th>
                <th>Inv. Actual</th>
                <th style="text-align:center">Cant. a Comprar (Anotar)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($agotados as $item)
            <tr class="page-break">
                <td style="font-family: monospace; font-size: 11px; color:#94a3b8;">VAR-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <strong style="color: #0f172a;">{{ $item->producto->nombre ?? 'N/A' }}</strong><br>
                    <span style="color: #64748b; font-size: 11px;">{{ $item->producto->categoria ?? 'General' }}</span>
                </td>
                <td>
                    {{ $item->color ?? '—' }} 
                    @if($item->grosor) <span style="color: #94a3b8;">/</span> {{ $item->grosor }} @endif
                    @if($item->marca) <div style="font-size: 10px; color: #94a3b8; font-weight:bold;">BY: {{ strtoupper($item->marca) }}</div> @endif
                </td>
                <td>
                    @if($item->stock == 0)
                        <span class="stock-critical">0 AGOTADO</span>
                    @else
                        <span class="stock-low">{{ $item->stock }} Unid.</span>
                    @endif
                </td>
                <td style="text-align:center">
                    <div class="suggested-qty"></div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; padding: 40px; color:#94a3b8;">
                    No hay productos agotados o críticos en el sistema. Has mantenido un excelente control de inventario.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: center; color: #cbd5e1; font-size: 11px;" class="page-break">
        <p style="margin-bottom: 40px;">_________________________________________<br><strong style="color: #64748b;">Firma / Aprobación de Compra</strong></p>
        <p>Documento autogenerado por el Sistema de Reposición Stitch & Co.</p>
    </div>

    <script>
        // Imprimir automáticamente al abrir
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
