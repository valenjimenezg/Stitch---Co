@extends('layouts.admin')

@section('title', 'CRM - Proveedores')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-black text-slate-900 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary">contact_phone</span>
        Centro de Contacto a Proveedores
    </h2>
    <p class="text-slate-500 text-sm mt-1">
        Proveedores con mercancía agotada o en estado crítico (Merma / Quiebre). 
        Genera su orden en PDF y notifícales directamente por WhatsApp.
    </p>
</div>

@if($proveedores->isEmpty())
    <div class="bg-emerald-50 border border-emerald-200 p-8 rounded-2xl text-center">
        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-3xl">check_circle</span>
        </div>
        <h3 class="text-lg font-bold text-emerald-800">¡Inventario Saludable!</h3>
        <p class="text-emerald-600 font-medium">No hay productos en quiebre de stock en este momento. Excelente trabajo.</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($proveedores as $prov)
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col overflow-hidden relative">
                
                {{-- Decorative Line --}}
                <div class="h-2 w-full bg-gradient-to-r from-primary to-blue-500"></div>
                
                <div class="p-6 flex-1">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-lg text-slate-900 leading-tight">{{ $prov->nombre }}</h3>
                            <p class="text-xs text-slate-500 uppercase tracking-widest font-semibold">{{ $prov->productoVariantes->count() }} ítems críticos</p>
                        </div>
                        <div class="bg-red-100 text-red-600 size-10 rounded-full flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined">warning</span>
                        </div>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-3 text-sm text-slate-600">
                            <img src="https://i.pravatar.cc/150?u={{ $prov->id }}" alt="Avatar" class="w-8 h-8 rounded-full border border-slate-200 shadow-sm object-cover">
                            <span class="font-bold">Contacto: {{ $prov->contacto ?? 'Asesor (General)' }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-600">
                            <span class="material-symbols-outlined text-[18px] text-green-600">phone_iphone</span>
                            <span class="font-bold">{{ $prov->telefono ?? 'Sin Teléfono' }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 mb-6">
                        <p class="text-xs text-slate-500 font-semibold mb-2">Vista previa de productos:</p>
                        <ul class="text-xs text-slate-700 space-y-1">
                            @foreach($prov->productoVariantes->take(3) as $var)
                                <li class="flex items-center gap-2 truncate">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> 
                                    {{ $var->producto->nombre }} ({{ $var->color }})
                                </li>
                            @endforeach
                            @if($prov->productoVariantes->count() > 3)
                                <li class="text-slate-400 text-[10px] pl-3 italic">+ {{ $prov->productoVariantes->count() - 3 }} más...</li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 border-t border-slate-100 flex flex-wrap gap-2 justify-between">
                    @php
                        $rawPdfUrl = route('admin.proveedores.pdf', $prov->id);
                        $publicPdfUrl = str_replace(['localhost', '127.0.0.1'], getHostByName(getHostName()), $rawPdfUrl);
                    @endphp

                    {{-- Generar PDF Button --}}
                    <a href="{{ $rawPdfUrl }}" target="_blank" class="flex-1 flex flex-col items-center justify-center p-2 rounded-lg bg-white border border-slate-200 shadow-sm text-slate-700 hover:text-red-600 hover:bg-red-50 transition-colors text-center group">
                        <span class="material-symbols-outlined text-xl mb-1 mt-0.5 group-hover:scale-110 transition-transform">picture_as_pdf</span>
                        <span class="text-[9px] font-bold uppercase tracking-wider">PDF</span>
                    </a>

                    {{-- WhatsApp Proveedor --}}
                    @php
                        $phoneFormat = preg_replace('/[^0-9]/', '', $prov->telefono);
                        if(str_starts_with($phoneFormat, '0')) $phoneFormat = '58' . substr($phoneFormat, 1);
                        $mensajeRaw = "Hola {$prov->nombre}, te habla Compras de Stitch & Co. Necesitamos reponer mercancía. Adjunto el enlace a nuestra Orden de Requisición oficial para revisar disponibilidad:\n\n{$publicPdfUrl}\n\n¡Quedo atento!";
                        $encodedMsg = urlencode($mensajeRaw);
                    @endphp
                    @if($phoneFormat)
                        <a href="https://wa.me/{{ $phoneFormat }}?text={{ $encodedMsg }}" target="_blank" class="flex-1 flex flex-col items-center justify-center p-2 rounded-lg bg-green-50 border border-green-200 shadow-sm text-green-700 hover:bg-green-100 transition-colors text-center group">
                            <span class="material-symbols-outlined text-xl mb-1 mt-0.5 group-hover:scale-110 transition-transform">chat</span>
                            <span class="text-[9px] font-bold uppercase tracking-wider">Prov (WA)</span>
                        </a>
                    @else
                        <div class="flex-1 flex flex-col items-center justify-center p-2 rounded-lg bg-slate-100 text-slate-400 border border-slate-200 text-center" title="Sin Número">
                            <span class="material-symbols-outlined text-xl mb-1 mt-0.5">phonelink_erase</span>
                            <span class="text-[9px] font-bold uppercase tracking-wider">No WA</span>
                        </div>
                    @endif

                    {{-- Correo al Proveedor --}}
                    @php
                        $emailSubject = rawurlencode("Orden de Reposición Requerida - Stitch & Co. / {$prov->nombre}");
                        $emailBody = rawurlencode("Estimado proveedor {$prov->nombre},\n\nSolicitamos la revisión urgente de disponibilidad y cotización. Puede revisar los detalles precisos descargando nuestra Orden de Requisición oficial desde nuestro portal administrativo seguro en este enlace:\n\n{$publicPdfUrl}\n\nAgradecemos su pronta respuesta para proceder con la orden.\n\nAtentamente,\nCompras Stitch & Co.");
                    @endphp
                    @if($prov->email)
                        <a href="mailto:{{ $prov->email }}?subject={{ $emailSubject }}&body={{ $emailBody }}" class="flex-1 flex flex-col items-center justify-center p-2 rounded-lg bg-orange-50 border border-orange-200 shadow-sm text-orange-700 hover:bg-orange-100 transition-colors text-center group">
                            <span class="material-symbols-outlined text-xl mb-1 mt-0.5 group-hover:scale-110 transition-transform">mail</span>
                            <span class="text-[9px] font-bold uppercase tracking-wider">Prov (@)</span>
                        </a>
                    @else
                        <div class="flex-1 flex flex-col items-center justify-center p-2 rounded-lg bg-slate-100 text-slate-400 border border-slate-200 text-center">
                            <span class="material-symbols-outlined text-xl mb-1 mt-0.5">alternate_email</span>
                            <span class="text-[9px] font-bold uppercase tracking-wider">Sin email</span>
                        </div>
                    @endif

                    {{-- WhatsApp Al Dueño --}}
                    @php
                        $ownerPhone = '584245659154'; // Dueño
                        $ownerMsg = urlencode("Hola Gerencia, tenemos productos críticos listos para quiebre de stock del proveedor *{$prov->nombre}*. Requisición PDF aquí para tu revisión o impresión: {$publicPdfUrl} ¿Autorizas contactar al proveedor?");
                    @endphp
                    <a href="https://wa.me/{{ $ownerPhone }}?text={{ $ownerMsg }}" target="_blank" class="flex-1 flex flex-col items-center justify-center p-2 rounded-lg bg-blue-50 border border-blue-200 shadow-sm text-blue-700 hover:bg-blue-100 transition-colors text-center group" title="Notificar a Gerencia">
                        <span class="material-symbols-outlined text-xl mb-1 mt-0.5 group-hover:scale-110 transition-transform">admin_panel_settings</span>
                        <span class="text-[9px] font-bold uppercase tracking-wider">Ger. (WA)</span>
                    </a>

                    {{-- Correo al Dueño --}}
                    @php
                        $ownerEmail = 'gerencia@stitchandco.com.ve'; 
                        $ownerEmailSubject = rawurlencode("Aprobación de Requisición - {$prov->nombre}");
                        $ownerEmailBody = rawurlencode("Estimada Gerencia,\n\nSolicito aprobación para procesar la requisición de inventario en estado crítico del proveedor {$prov->nombre}.\n\nPuedes revisar y descargar nuestra Orden de Requisición oficial para imprimir aquí:\n\n{$publicPdfUrl}\n\nQuedo a la espera de autorización.\n\nAtentamente,\nPanel Administrativo");
                    @endphp
                    <a href="mailto:{{ $ownerEmail }}?subject={{ $ownerEmailSubject }}&body={{ $ownerEmailBody }}" class="flex-1 flex flex-col items-center justify-center p-2 rounded-lg bg-indigo-50 border border-indigo-200 shadow-sm text-indigo-700 hover:bg-indigo-100 transition-colors text-center group">
                        <span class="material-symbols-outlined text-xl mb-1 mt-0.5 group-hover:scale-110 transition-transform">mail_lock</span>
                        <span class="text-[9px] font-bold uppercase tracking-wider">Ger. (@)</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
