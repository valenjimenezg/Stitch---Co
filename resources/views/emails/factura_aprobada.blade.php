<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
        <h2 style="color: #6a1b9a;">¡Hola, {{ $venta->user->nombre ?? 'Cliente' }}!</h2>
        
        <p>Queremos informarte que <strong>tu pago ha sido verificado y aprobado exitosamente</strong>.</p>
        
        <p>Tu orden <strong>#STR-{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</strong> se encuentra ahora en estado: <span style="color: #d97706; font-weight: bold;">Procesando</span>.</p>
        
        <p>Adjuntamos a este correo la factura en formato PDF con todos los detalles de tu compra.</p>
        
        <p>Agradecemos tu confianza en <strong>Stitch & Co</strong>. Estaremos procesando y enviando tu pedido lo antes posible.</p>
        
        <br>
        <p style="font-size: 0.9em; color: #777;">Saludos cordiales,<br>El equipo de Stitch & Co.</p>
    </div>
</body>
</html>
