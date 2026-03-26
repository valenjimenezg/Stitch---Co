<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>
    body { font-family: 'Helvetica Neue', Arial, sans-serif; background-color: #f8fafc; color: #334155; margin: 0; padding: 20px; }
    .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    .header { background: #EF4444; padding: 30px 40px; text-align: center; color: white; }
    .header h1 { margin: 0; font-size: 22px; letter-spacing: 1px; }
    .content { padding: 40px; }
    .warning { background: #FEF2F2; color: #991B1B; padding: 15px; border-radius: 8px; margin-bottom: 30px; font-size: 14px; border: 1px solid #FECACA; }
    table { border-collapse: collapse; margin-bottom: 30px; font-size: 14px; width: 100%; }
    th { text-align: left; padding: 12px 10px; border-bottom: 2px solid #e2e8f0; color: #64748b; font-weight: bold; background: #f8fafc; }
    td { padding: 12px 10px; border-bottom: 1px solid #f1f5f9; }
    .footer { text-align: center; padding: 30px; font-size: 12px; color: #94A3B8; background: #f8fafc; border-top: 1px solid #e2e8f0; }
</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Urgente: Stock Agotado</h1>
        </div>
        <div class="content">
            <p>Hola Administrador/Dueño,</p>
            <div class="warning">
                <strong>Atención:</strong> Los siguientes insumos/productos han alcanzado un nivel de inventario de cero (0) y necesitan ser repuestos inmediatamente para no perder oportunidades de venta.
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Producto Principal</th>
                        <th>Variante (Color/Talla)</th>
                        <th>Marca</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agotados as $item)
                    <tr>
                        <td><strong>{{ $item->producto->nombre ?? 'Desconocido' }}</strong></td>
                        <td>{{ $item->color ?? 'Única' }} {{ $item->talla ?? '' }}</td>
                        <td>{{ $item->marca ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 20px;">No hay productos agotados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <p style="font-size: 13px; color: #64748b;">Accede al panel de inventario para gestionar las compras y reactivar estos productos en la vitrina virtual.</p>
        </div>
        <div class="footer">
            <p>Mensaje generado automáticamente por el sistema de inventario de Stitch &amp; Co.</p>
        </div>
    </div>
</body>
</html>
