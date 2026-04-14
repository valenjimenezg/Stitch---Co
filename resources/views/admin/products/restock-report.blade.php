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

                <div class="p-4 bg-slate-50 border-t border-slate-100 grid grid-cols-2 lg:grid-cols-4 gap-3">
                    {{-- Generar PDF Button --}}
                    <a href="{{ route('admin.proveedores.pdf', $prov->id) }}" target="_blank" class="flex flex-col items-center justify-center p-2 rounded-lg bg-white border border-slate-200 shadow-sm text-slate-700 hover:text-red-600 hover:border-red-200 hover:bg-red-50 transition-colors text-center group">
                        <span class="material-symbols-outlined text-2xl mb-1 group-hover:scale-110 transition-transform">picture_as_pdf</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Orden (PDF)</span>
                    </a>

                    {{-- WhatsApp API Button --}}
                    @php
                        // Limpiar número para pasarlo por WhatsApp API (Quitar +, espacios, etc)
                        $phoneFormat = preg_replace('/[^0-9]/', '', $prov->telefono);
                        if(str_starts_with($phoneFormat, '0')) {
                            $phoneFormat = '58' . substr($phoneFormat, 1); // Asumiendo formato venezolano
                        }
                        
                        $mensajeRaw = "Hola {$prov->nombre}, te habla el departamento de Compras de Stitch & Co. Te escribo porque tenemos varios productos agotados (o en merma) que necesitamos reponer con urgencia. Te acabo de enviar (o estoy por adjuntar) el reporte PDF de nuestra requisición.\n\nPor favor, indícanos disponibilidad y cotización actual. ¡Quedo atento!";
                        $encodedMsg = urlencode($mensajeRaw);
                    @endphp
                    
                    @if($phoneFormat)
                        <a href="https://wa.me/{{ $phoneFormat }}?text={{ $encodedMsg }}" target="_blank" class="flex flex-col items-center justify-center p-2 rounded-lg bg-green-50 border border-green-200 shadow-sm text-green-700 hover:bg-green-100 hover:border-green-300 transition-colors text-center group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-whatsapp mb-1 group-hover:scale-110 transition-transform" viewBox="0 0 16 16">
                                <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                            </svg>
                            <span class="text-[10px] font-bold uppercase tracking-wider">WhatsApp</span>
                        </a>
                    @else
                        <div class="flex flex-col items-center justify-center p-2 rounded-lg bg-slate-100 text-slate-400 border border-slate-200 text-center" title="El proveedor no tiene teléfono registrado">
                            <span class="material-symbols-outlined text-2xl mb-1">phonelink_erase</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider">Sin Número</span>
                        </div>
                    @endif

                    {{-- Correo al Proveedor --}}
                    @php
                        $pdfUrl = route('admin.proveedores.pdf', $prov->id);
                        $emailSubject = rawurlencode("Orden de Reposición Requerida - Stitch & Co. / {$prov->nombre}");
                        $emailBody = rawurlencode("Estimado proveedor {$prov->nombre},\n\nNos dirigimos a su departamento de ventas de parte de Stitch & Co. \n\nDebido al alto volumen de ventas recientes, solicitamos la revisión urgente de disponibilidad y cotización. Puede revisar los detalles precisos (cantidades, variaciones y colores agotados) descargando nuestra Orden de Requisición oficial desde nuestro portal administrativo seguro en este enlace:\n\n{$pdfUrl}\n\nAgradecemos su pronta respuesta para proceder con la orden.\n\nAtentamente,\nCompras Stitch & Co.");
                    @endphp
                    @if($prov->email)
                        <a href="mailto:{{ $prov->email }}?subject={{ $emailSubject }}&body={{ $emailBody }}" class="flex flex-col items-center justify-center p-2 rounded-lg bg-orange-50 border border-orange-200 shadow-sm text-orange-700 hover:bg-orange-100 hover:border-orange-300 transition-colors text-center group" title="Enviar correo a {{ $prov->email }}">
                            <span class="material-symbols-outlined text-2xl mb-1 group-hover:scale-110 transition-transform">mail</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider">Correo</span>
                        </a>
                    @else
                        <div class="flex flex-col items-center justify-center p-2 rounded-lg bg-slate-100 text-slate-400 border border-slate-200 text-center" title="El proveedor no tiene correo registrado">
                            <span class="material-symbols-outlined text-2xl mb-1">alternate_email</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider">Sin Correo</span>
                        </div>
                    @endif

                    {{-- WhatsApp Al Dueño --}}
                    @php
                        $ownerPhone = '584120000000'; // Fijo para pruebas
                        $pdfUrl = route('admin.proveedores.pdf', $prov->id);
                        $ownerMsg = urlencode("Hola Gerencia, tenemos productos críticos listos para quiebre de stock del proveedor *{$prov->nombre}*. Revisa la requisición en PDF aquí para imprimir de ser necesario: {$pdfUrl} ¿Autorizas procesar la orden y contactar al proveedor?");
                    @endphp
                    <a href="https://wa.me/{{ $ownerPhone }}?text={{ $ownerMsg }}" target="_blank" class="flex flex-col items-center justify-center p-2 rounded-lg bg-blue-50 border border-blue-200 shadow-sm text-blue-700 hover:bg-blue-100 hover:border-blue-300 transition-colors text-center group" title="Notificar a Gerencia">
                        <span class="material-symbols-outlined text-2xl mb-1 group-hover:scale-110 transition-transform">admin_panel_settings</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Dueño</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
