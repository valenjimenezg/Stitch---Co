<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura Pedido #{{ $venta->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; background: white; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans p-10 min-h-screen flex justify-center">

    <div class="w-full max-w-3xl bg-white p-12 shadow-2xl rounded-sm relative border-t-8 border-purple-600">
        <!-- Header -->
        <div class="flex justify-between items-start mb-12">
            <div>
                <h1 class="text-4xl font-black text-purple-600 tracking-tighter mb-2">STITCH & CO</h1>
                <p class="text-slate-500 text-sm">C.C. Los Ilustres, Local 4, Guanare, VE</p>
                <p class="text-slate-500 text-sm">RIF: J-12345678-9 | Tel: +58 424 5659154</p>
            </div>
            <div class="text-right">
                <h2 class="text-3xl font-black text-slate-200 uppercase tracking-widest mb-2">Factura</h2>
                <p class="font-bold text-slate-700">ORD-{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p class="text-sm text-slate-500">Fecha: {{ $venta->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Bill To -->
        <div class="mb-10 bg-slate-50 p-6 rounded-lg border border-slate-100 flex justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Facturar a:</p>
                <p class="font-bold text-slate-800 text-lg">{{ $venta->user->nombre }} {{ $venta->user->apellido }}</p>
                <p class="text-slate-500 text-sm">{{ $venta->user->email }}</p>
                @if($venta->user->telefono) <p class="text-slate-500 text-sm">{{ $venta->user->telefono }}</p> @endif
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Método de Pago:</p>
                <p class="font-bold text-slate-800">{{ strtoupper(str_replace('_', ' ', $venta->metodo_pago)) }}</p>
                @if($venta->referencia_pago)
                    <p class="text-slate-500 text-sm font-mono mt-1">Ref: {{ $venta->referencia_pago }}</p>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table class="w-full mb-10 text-left">
            <thead>
                <tr class="border-b-2 border-slate-200">
                    <th class="py-3 text-slate-600 font-bold text-sm uppercase">Producto</th>
                    <th class="py-3 text-slate-600 font-bold text-sm uppercase text-center w-24">Cant</th>
                    <th class="py-3 text-slate-600 font-bold text-sm uppercase text-right w-32">Precio</th>
                    <th class="py-3 text-slate-600 font-bold text-sm uppercase text-right w-32">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($venta->detalles as $detalle)
                <tr>
                    <td class="py-4">
                        <p class="font-bold text-slate-800">{{ $detalle->variante->producto->nombre ?? 'Producto' }}</p>
                        @if($detalle->variante->color || $detalle->variante->talla)
                            <p class="text-xs text-slate-500">Color: {{ $detalle->variante->color }} | Talla: {{ $detalle->variante->talla }}</p>
                        @endif
                    </td>
                    <td class="py-4 text-center text-slate-600">{{ $detalle->cantidad }}</td>
                    <td class="py-4 text-right text-slate-600">Bs. {{ number_format((float)($detalle->precio_unitario ?? 0), 2) }}</td>
                    <td class="py-4 text-right font-bold text-slate-800">Bs. {{ number_format((float)($detalle->subtotal ?? 0), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="flex justify-end mb-16">
            <div class="w-64">
                <div class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500 font-bold">Subtotal</span>
                    <span class="text-slate-800 font-bold">{{ bs($venta->total_venta ?? 0, false, $venta->tasa_bcv_aplicada) }}</span>
                </div>
                <div class="flex justify-between py-3">
                    <span class="text-slate-800 font-black text-xl">TOTAL</span>
                    <span class="text-purple-600 font-black text-xl">{{ bs($venta->total_venta ?? 0, false, $venta->tasa_bcv_aplicada) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-slate-400 text-xs mt-16 pt-8 border-t border-slate-100">
            <p class="mb-1 font-bold">¡Gracias por tu compra en Stitch & Co!</p>
            <p>Este documento es un comprobante de pago digital válido.</p>
        </div>

        <!-- Action Button -->
        <div class="absolute top-12 right-12 no-print">
            <button onclick="window.print()" class="bg-slate-900 text-white px-6 py-2 rounded-lg font-bold shadow-lg hover:bg-slate-800 transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">print</span> Imprimir / PDF
            </button>
        </div>
    </div>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

</body>
</html>
