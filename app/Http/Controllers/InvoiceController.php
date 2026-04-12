<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoiceController extends Controller
{
    public function descargarFactura($id)
    {
        // Determinamos el query en base al rol del usuario
        $query = Orden::with(['detalles.variante.producto', 'user']);

        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $orden = $query->findOrFail($id);

        if (!in_array($orden->estado, ['completado', 'entregado'])) {
            // Error estricto bloqueando PDF si no está finalizado
            abort(403, 'La factura solo está disponible para pedidos completados. Finaliza la entrega primero.');
        }

        // Generar un código QR en base64 apuntando a la URL pública con la IP de la presentación
        $qrData = \Illuminate\Support\Facades\URL::signedRoute('invoice.public', ['id' => $orden->id]);
        $qrData = str_replace(['localhost', '127.0.0.1'], getHostByName(getHostName()), $qrData);
        
        $qrCode = base64_encode(QrCode::format('svg')->size(100)->generate($qrData));

        $tasa_actual = $orden->tasa_bcv_aplicada ?? bcv_rate();

        return view('factura', ['orden' => $orden, 'qrCode' => $qrCode, 'tasa_actual' => $tasa_actual]);
    }

    public function descargarFacturaPublica(\Illuminate\Http\Request $request, $id)
    {
        // La validación del hash ahora la hace automáticamente el middleware 'signed' de Laravel en las rutas
        
        $orden = Orden::with(['detalles.variante.producto', 'user'])->findOrFail($id);

        if (!in_array($orden->estado, ['pagado', 'procesando', 'completado', 'entregado'])) {
            abort(403, 'La factura no está lista para este pedido.');
        }

        // Reutilizamos la lógica del QR adaptado a la red WiFi viva
        $qrData = \Illuminate\Support\Facades\URL::signedRoute('invoice.public', ['id' => $orden->id]);
        $qrData = str_replace(['localhost', '127.0.0.1'], getHostByName(getHostName()), $qrData);
        $qrCode = base64_encode(QrCode::format('svg')->size(100)->generate($qrData));

        $tasa_actual = $orden->tasa_bcv_aplicada ?? bcv_rate();
        // Cambiado: Ahora la factura pública (la que se envía por WhatsApp) renderiza el diseño moderno nativo
        return view('factura', ['orden' => $orden, 'qrCode' => $qrCode, 'tasa_actual' => $tasa_actual]);
    }

    public function previewFacturaHTML($id)
    {
        $orden = Orden::with(['detalles.variante.producto', 'user'])->findOrFail($id);

        // Generamos la información del QR dinámicamente con la IP de esa red en específico
        $qrData = \Illuminate\Support\Facades\URL::signedRoute('invoice.public', ['id' => $orden->id]);
        $qrData = str_replace(['localhost', '127.0.0.1'], getHostByName(getHostName()), $qrData);
        $qrCode = base64_encode(QrCode::format('svg')->size(100)->generate($qrData));

        $tasa_actual = $orden->tasa_bcv_aplicada ?? bcv_rate();

        return view('factura', compact('orden', 'qrCode', 'tasa_actual'));
    }
}
