<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Tu producto ha vuelto!</title>
    <style>
        body { margin: 0; padding: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #1e293b; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05); }
        .header { background-color: #8b52ff; padding: 40px 30px; text-align: center; }
        .logo { font-size: 28px; font-weight: 900; color: #ffffff; letter-spacing: -0.5px; margin: 0; }
        .logo span { font-weight: 300; opacity: 0.8; }
        .content { padding: 40px 30px; }
        h1 { margin-top: 0; font-size: 24px; color: #0f172a; font-weight: 800; }
        p { font-size: 16px; line-height: 1.6; color: #475569; margin-bottom: 24px; }
        .product-card { border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 30px; background-color: #f8fafc; }
        .product-img { width: 150px; height: 150px; border-radius: 8px; object-fit: cover; background-color: #e2e8f0; margin: 0 auto 20px auto; display: block; }
        .product-name { font-size: 20px; font-weight: bold; color: #0f172a; margin: 0 0 10px 0; }
        .product-variant { font-size: 14px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin: 0; }
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
            <h1>¡Lo que esperabas ya está aquí! 🎉</h1>
            <p>Hola, te escribimos porque nos pediste que te avisáramos en cuanto repusiéramos inventario. Tenemos excelentes noticias: ¡tu producto ha vuelto a la tienda y está listo para encargar!</p>
            
            <!-- Detalles del Producto -->
            <div class="product-card">
                @if($variante->imagen && file_exists(public_path($variante->imagen)))
                    <img src="{{ $message->embed(public_path($variante->imagen)) }}" alt="{{ $variante->producto->nombre ?? 'Producto' }}" class="product-img">
                @elseif($variante->imagen)
                    <img src="{{ url($variante->imagen) }}" alt="Producto" class="product-img">
                @endif
                <h3 class="product-name">{{ $variante->producto->nombre ?? 'Tu producto favorito' }}</h3>
                <p class="product-variant">
                    @if($variante->color) Color: {{ $variante->color }} @endif
                    @if($variante->grosor) | Medida: {{ $variante->grosor }} @endif
                </p>
            </div>

            <p style="text-align: center;">El stock vuela rápido, así que te recomendamos asegurar el tuyo antes de que se agote nuevamente.</p>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('products.show', $variante->id) }}" class="cta-button">Ver Producto y Comprar Ahora</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Has recibido este correo porque te suscribiste a las alertas de stock de Stitch & Co.</p>
            <p>&copy; {{ date('Y') }} Stitch & Co. Haberdashery. Todos los derechos reservados.</p>
            <p><a href="#">Visita nuestra tienda</a> | <a href="#">Darte de baja de estas alertas</a></p>
        </div>
    </div>
</body>
</html>
