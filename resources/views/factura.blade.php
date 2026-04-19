<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de Compra - Stitch & Co</title>
    
    <!-- WhatsApp / Open Graph Meta Tags -->
    <meta property="og:title" content="Factura #{{ str_pad($orden->id, 6, '0', STR_PAD_LEFT) }} - Stitch & Co">
    <meta property="og:description" content="Visualiza tu factura digital o recibo de compra en nuestra tienda.">
    <meta property="og:image" content="{{ asset('img/logo.jpg') }}">
    <meta property="og:type" content="website">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body { 
                background-color: white !important; 
                -webkit-print-color-adjust: exact !important; 
                print-color-adjust: exact !important;
                margin: 0;
                padding: 0;
                min-height: auto !important;
            }
            .no-print { display: none !important; }
            .print-break-inside-avoid { break-inside: avoid; }
            .print-shadow-none { box-shadow: none !important; }
            main { 
                border: none !important; 
                margin: 0 !important; 
                padding: 1rem !important; 
                max-width: 100% !important;
            }
        }
        .invoice-pattern {
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased font-sans min-h-screen py-0 sm:py-10 print:py-0 print:bg-white invoice-pattern">

    <main class="max-w-4xl mx-auto bg-white p-6 sm:p-12 sm:border border-slate-200 sm:rounded-sm sm:shadow-2xl w-full print:border-none print:shadow-none print:rounded-none relative overflow-hidden print:p-4 print:m-0">
        
        {{-- Deco superior (Banda de color) --}}
        <div class="absolute top-0 left-0 right-0 h-2 bg-slate-900 print:hidden"></div>

        {{-- Cabecera --}}
        <header class="flex flex-col md:flex-row justify-between items-center md:items-start gap-6 pb-8 border-b-2 border-slate-900 mt-2">
            <div class="text-center md:text-left flex flex-col md:flex-row items-center md:items-start gap-5">
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-slate-900 uppercase">Stitch & Co.</h1>
                    <p class="text-sm text-slate-500 font-medium tracking-wide uppercase">Mercería Online</p>
                    <div class="mt-3 text-xs text-slate-500 space-y-1">
                        <p><strong class="text-slate-700">RIF:</strong> J-12345678-9</p>
                        <p>Guanare, Portuguesa, Venezuela</p>
                        <p>contacto@stitchandco.com</p>
                    </div>
                </div>
            </div>
            
            <div class="text-center md:text-right mt-4 md:mt-0">
                <h2 class="text-4xl font-black text-slate-200 uppercase tracking-tighter mix-blend-multiply">FACTURA</h2>
                <p class="text-lg font-bold text-slate-900">#{{ str_pad($orden->id, 6, '0', STR_PAD_LEFT) }}</p>
                <div class="mt-4 text-xs text-slate-600 space-y-1 text-right">
                    <p class="flex justify-between md:justify-end gap-4"><span class="font-bold text-slate-400 uppercase tracking-wider">Fecha:</span> <span>{{ $orden->created_at->format('d/m/Y') }}</span></p>
                    <p class="flex justify-between md:justify-end gap-4"><span class="font-bold text-slate-400 uppercase tracking-wider">Hora:</span> <span>{{ $orden->created_at->format('h:i A') }}</span></p>
                    <p class="flex justify-between md:justify-end gap-4 items-center">
                        <span class="font-bold text-slate-400 uppercase tracking-wider">Estado:</span> 
                        @if($orden->estado == 'entregado' || $orden->estado == 'completado')
                            <span class="inline-block px-2 py-0.5 bg-emerald-100 text-emerald-800 font-bold border border-emerald-200">PAGADA</span>
                        @else
                            <span class="inline-block px-2 py-0.5 bg-amber-100 text-amber-800 font-bold border border-amber-200">{{ strtoupper($orden->estado) }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </header>

        {{-- Datos del Cliente --}}
        <section class="my-8 flex flex-col md:flex-row justify-between gap-8">
            <div class="flex-1">
                <h3 class="text-[10px] font-bold uppercase text-slate-400 tracking-widest mb-2 border-b border-slate-100 pb-1">Facturar A</h3>
                <p class="text-base font-bold text-slate-900 uppercase">{{ $orden->user->nombre ?? 'Cliente' }} {{ $orden->user->apellido ?? '' }}</p>
                <div class="mt-2 text-sm text-slate-600 space-y-1">
                    <p><strong class="font-semibold text-slate-800">Doc/RIF:</strong> {{ $orden->user->document_number ?? 'No registrado' }}</p>
                    <p><strong class="font-semibold text-slate-800">Teléfono:</strong> {{ $orden->user->phone ?? 'No registrado' }}</p>
                    <p>{{ $orden->user->email ?? 'Correo no especificado' }}</p>
                </div>
            </div>
            
            @if($orden->delivery_method == 'delivery')
            <div class="flex-1">
                <h3 class="text-[10px] font-bold uppercase text-slate-400 tracking-widest mb-2 border-b border-slate-100 pb-1">Dirección de Envío</h3>
                <p class="text-sm font-bold text-slate-900">{{ $orden->ciudad_envio }}, {{ $orden->estado_envio }}</p>
                <p class="text-sm text-slate-600 mt-1 leading-relaxed">{{ $orden->calle_envio }}</p>
            </div>
            @endif
        </section>

        {{-- Tabla de Productos --}}
        <section class="mb-10">
            {{-- Encabezados para PC --}}
            <div class="hidden md:grid grid-cols-12 gap-4 pb-2 border-b border-slate-900 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                <div class="col-span-6">Descripción del Producto</div>
                <div class="col-span-2 text-center">Cant</div>
                <div class="col-span-2 text-right">Precio Unitario</div>
                <div class="col-span-2 text-right">Total</div>
            </div>

            {{-- Filas de la Tabla --}}
            <div class="divide-y divide-slate-100 border-b border-slate-200">
                @foreach($orden->detalles as $detalle)
                <div class="grid grid-cols-1 md:grid-cols-12 gap-y-1 md:gap-4 py-3 md:py-4 items-center print-break-inside-avoid hover:bg-slate-50 transition-colors">
                    {{-- Información Producto --}}
                    <div class="col-span-1 md:col-span-6">
                        <p class="font-bold text-slate-900 text-sm uppercase">{{ $detalle->variante->producto->nombre ?? 'Producto Desconocido' }}</p>
                        @if($detalle->variante->color || $detalle->variante->talla)
                        <p class="text-[11px] text-slate-500 mt-0.5 uppercase tracking-wide">
                            Variante: {{ $detalle->variante->color }} {{ $detalle->variante->talla ? ' | ' . $detalle->variante->talla : '' }}
                        </p>
                        @endif
                    </div>
                    
                    {{-- Cantidad --}}
                    <div class="col-span-1 md:col-span-2 md:text-center flex justify-between md:block text-sm mt-2 md:mt-0">
                        <span class="md:hidden text-slate-400 text-[10px] font-bold uppercase tracking-wider">Cant:</span>
                        <span class="text-slate-800 font-bold bg-slate-100 md:bg-transparent px-2 md:px-0 py-0.5 rounded">{{ $detalle->cantidad }}</span>
                    </div>
                    
                    {{-- Precio Unitario --}}
                    <div class="col-span-1 md:col-span-2 md:text-right flex justify-between md:block text-sm">
                        <span class="md:hidden text-slate-400 text-[10px] font-bold uppercase tracking-wider">Precio U.:</span>
                        <div class="text-right">
                            <p class="font-semibold text-slate-800">${{ number_format($detalle->precio_unitario, 2) }}</p>
                            <p class="text-[10px] text-slate-500 font-medium">Bs. {{ number_format($detalle->precio_unitario * $tasa_actual, 2, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Subtotal --}}
                    <div class="col-span-1 md:col-span-2 md:text-right flex justify-between md:block text-sm mt-1 md:mt-0 font-bold">
                        <span class="md:hidden text-slate-400 text-[10px] font-bold uppercase tracking-wider self-center">Total:</span>
                        <div class="text-right">
                            <p class="text-slate-900">${{ number_format($detalle->subtotal, 2) }}</p>
                            <p class="text-[10px] text-slate-500 font-medium">Bs. {{ number_format($detalle->subtotal * $tasa_actual, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- Sección Inferior (Totales Reales) --}}
        <section class="flex flex-col-reverse md:flex-row justify-between items-end gap-10 print-break-inside-avoid">
            
            {{-- Notas Adicionales --}}
            <div class="w-full md:w-1/2">
                <h3 class="text-[10px] font-bold uppercase text-slate-400 tracking-widest mb-2 border-b border-slate-100 pb-1">Método de Pago</h3>
                <p class="text-sm font-semibold text-slate-800 uppercase">{{ str_replace('_', ' ', $orden->metodo_pago) }}</p>
                <div class="mt-2 text-xs text-slate-500 flex flex-col gap-1">
                    <p><strong class="font-bold text-slate-700">Ref:</strong> {{ $orden->referencia_pago ?? 'N/A' }}</p>
                    <p><strong class="font-bold text-slate-700">Banco:</strong> {{ $orden->banco_pago ?? 'N/A' }}</p>
                </div>
            </div>

            {{-- Resumen Financiero --}}
            <div class="w-full md:w-80">
                <div class="bg-slate-50 p-5 border border-slate-200">
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-bold uppercase tracking-wider text-[11px]">Subtotal</span>
                            <span class="font-mono text-slate-700 font-medium">${{ number_format($orden->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-bold uppercase tracking-wider text-[11px]">IVA (16%)</span>
                            <span class="font-mono text-slate-700 font-medium">${{ number_format($orden->iva_amount, 2) }}</span>
                        </div>
                        
                        @if($orden->delivery_method == 'delivery')
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 font-bold uppercase tracking-wider text-[11px]">Delivery</span>
                            <span class="font-mono text-slate-700 font-medium">${{ number_format($orden->delivery_fee, 2) }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="pt-3 mt-3 border-t border-slate-300">
                        <div class="flex justify-between items-end">
                            <span class="text-sm font-black text-slate-900 uppercase tracking-widest">TOTAL</span>
                            <div class="text-right">
                                <span class="block text-2xl font-black text-slate-900 tracking-tighter leading-none">${{ number_format($orden->total_amount, 2) }}</span>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-dashed border-slate-300 text-right">
                            <p class="text-sm font-bold text-slate-800">Bs. {{ number_format($orden->total_amount * $tasa_actual, 2, ',', '.') }}</p>
                            <p class="text-[9px] font-bold text-slate-400 mt-0.5 uppercase tracking-wider">Tasa de Cambio: Bs. {{ number_format($tasa_actual, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </section>

        {{-- Footer QR y Firmas --}}
        <footer class="mt-10 print:mt-6 pt-6 print:pt-4 border-t-2 border-slate-900 flex justify-between items-end print-break-inside-avoid">
            <div class="flex items-center gap-4">
                <div class="p-1.5 bg-white border-2 border-slate-900">
                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="Código QR" class="w-20 h-20">
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-900 uppercase tracking-widest">Código de Verificación</p>
                    <p class="text-[9px] text-slate-500 mt-0.5 max-w-[150px]">Válido como comprobante fiscal y comercial. Escanee para auditar.</p>
                </div>
            </div>

            <div class="text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Autorizado Por</p>
                <div class="w-32 h-px bg-slate-400 mx-auto mt-6 mb-1 rounded-full"></div>
                <p class="text-xs font-bold text-slate-900">Gerencia Operativa</p>
            </div>
        </footer>

        {{-- Botón para imprimir (se oculta al imprimir) --}}
        <div class="mt-12 text-center no-print">
            <button onclick="window.print()" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold tracking-wider uppercase transition-colors rounded-sm shadow-md">
                <span class="material-symbols-outlined text-[18px]">print</span> Imprimir / Descargar
            </button>
        </div>

    </main>

</body>
</html>
