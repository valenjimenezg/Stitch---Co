@extends('layouts.admin')

@section('title', 'Proveedores')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Directorio de Proveedores</h2>
        <p class="text-slate-500 text-sm mt-1">Gestión de contactos de los fabricantes y distribuidores de material.</p>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 text-sm font-bold text-emerald-700 bg-emerald-50 px-4 py-3 border border-emerald-200 rounded-lg flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">check_circle</span>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 text-sm font-bold text-rose-700 bg-rose-50 px-4 py-3 border border-rose-200 rounded-lg flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">error</span>
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">
    <button type="button" onclick="document.getElementById('add-provider-form').classList.toggle('hidden')" class="w-full text-left outline-none">
        <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-primary text-[18px]">add_circle</span> Añadir Nuevo Proveedor 
            <span class="text-[11px] text-slate-400 font-normal ml-2 bg-slate-100 px-2 py-0.5 rounded-full border border-slate-200">Haz clic para expandir / cerrar</span>
        </h3>
    </button>
    
    <div id="add-provider-form" class="mt-4 pt-4 border-t border-slate-100 {{ $errors->any() ? '' : 'hidden' }} transition-all">
        <form method="POST" action="{{ route('admin.proveedores.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 mb-1">Nombre Comercial / Empresa / Contacto <span class="text-rose-500">*</span></label>
                <input type="text" name="nombre" placeholder="Ej: Importadora Andina C.A" required value="{{ old('nombre') }}" class="w-full rounded-lg border-slate-300 py-2.5 px-4 text-sm focus:border-primary focus:ring-primary shadow-sm outline-none transition-all">
                @error('nombre') <p class="text-xs text-rose-500 font-bold mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">error</span> {{ $message }}</p> @enderror
            </div>
            
            <div class="flex gap-2">
                <div class="w-24">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Tipo</label>
                    <select name="tipo_documento" class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm focus:border-primary focus:ring-primary shadow-sm">
                        <option value="J" {{ old('tipo_documento') == 'J' ? 'selected' : '' }}>J</option>
                        <option value="V" {{ old('tipo_documento') == 'V' ? 'selected' : '' }}>V</option>
                        <option value="E" {{ old('tipo_documento') == 'E' ? 'selected' : '' }}>E</option>
                        <option value="G" {{ old('tipo_documento') == 'G' ? 'selected' : '' }}>G</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Documento / RIF</label>
                    <input type="text" name="documento_identidad" placeholder="Ej: 123456789" value="{{ old('documento_identidad') }}" class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm focus:border-primary focus:ring-primary shadow-sm outline-none">
                    @error('documento_identidad') <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Teléfono (WhatsApp)</label>
                <input type="text" name="telefono" placeholder="Ej: 04141234567" value="{{ old('telefono') }}" class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm focus:border-primary focus:ring-primary shadow-sm outline-none bg-[url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' height=\'24px\' viewBox=\'0 0 24 24\' width=\'24px\' fill=\'%2394a3b8\'%3E%3Cpath d=\'M0 0h24v24H0V0z\' fill=\'none\'/%3E%3Cpath d=\'M20.01 15.38c-1.23 0-2.42-.2-3.53-.56-.35-.12-.74-.03-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.03 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z\'/%3E%3C/svg%3E')] bg-[length:16px] bg-[position:right_12px_center] bg-no-repeat">
                @error('telefono') <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Correo Electrónico</label>
                <input type="email" name="email" placeholder="Ej: ventas@empresa.com" value="{{ old('email') }}" class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm focus:border-primary focus:ring-primary shadow-sm outline-none bg-[url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' height=\'24px\' viewBox=\'0 0 24 24\' width=\'24px\' fill=\'%2394a3b8\'%3E%3Cpath d=\'M0 0h24v24H0V0z\' fill=\'none\'/%3E%3Cpath d=\'M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6zm-2 0l-8 5-8-5h16zm0 12H4V8l8 5 8-5v10z\'/%3E%3C/svg%3E')] bg-[length:16px] bg-[position:right_12px_center] bg-no-repeat">
                @error('email') <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Dirección Física</label>
                <input type="text" name="direccion" placeholder="Ej: Av. Bolívar, Local 4..." value="{{ old('direccion') }}" class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm focus:border-primary focus:ring-primary shadow-sm outline-none">
                @error('direccion') <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
            </div>
            
            <!-- Nuevos Campos -->
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Contacto Alternativo</label>
                <input type="text" name="contacto_alternativo" placeholder="Ej: Juan Pérez (Ventas)" value="{{ old('contacto_alternativo') }}" class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm focus:border-primary focus:ring-primary shadow-sm outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Redes Sociales / Web</label>
                <input type="text" name="redes_sociales" placeholder="Ej: @distribuidora_ve" value="{{ old('redes_sociales') }}" class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm focus:border-primary focus:ring-primary shadow-sm outline-none bg-[url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' height=\'24px\' viewBox=\'0 0 24 24\' width=\'24px\' fill=\'%2394a3b8\'%3E%3Cpath d=\'M0 0h24v24H0V0z\' fill=\'none\'/%3E%3Cpath d=\'M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zm6.93 6h-2.95c-.32-1.25-.78-2.45-1.38-3.56 1.84.63 3.37 1.91 4.33 3.56zM12 4.04c.83 1.2 1.48 2.53 1.91 3.96h-3.82c.43-1.43 1.08-2.76 1.91-3.96zM4.26 14C4.1 13.36 4 12.69 4 12s.1-1.36.26-2h3.38c-.08.66-.14 1.32-.14 2 0 .68.06 1.34.14 2H4.26zm.82 2h2.95c.32 1.25.78 2.45 1.38 3.56-1.84-.63-3.37-1.9-4.33-3.56zm2.95-8H5.08c.96-1.66 2.49-2.93 4.33-3.56C8.81 5.55 8.35 6.75 8.03 8zM12 19.96c-.83-1.2-1.48-2.53-1.91-3.96h3.82c-.43 1.43-1.08 2.76-1.91 3.96zM14.34 14H9.66c-.09-.66-.16-1.32-.16-2 0-.68.07-1.35.16-2h4.68c.09.65.16 1.32.16 2 0 .68-.07 1.34-.16 2zm.25 5.56c.6-1.11 1.06-2.31 1.38-3.56h2.95c-.96 1.65-2.49 2.93-4.33 3.56zM16.36 14c.08-.66.14-1.32.14-2 0-.68-.06-1.34-.14-2h3.38c.16.64.26 1.31.26 2s-.1 1.36-.26 2h-3.38z\'/%3E%3C/svg%3E')] bg-[length:16px] bg-[position:right_12px_center] bg-no-repeat">
            </div>

            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Cuenta Bancaria / Pago Móvil</label>
                    <textarea name="cuenta_bancaria" rows="2" placeholder="Ej: Banesco 0134... Pago Móvil:..." class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm focus:border-primary focus:ring-primary shadow-sm outline-none resize-none">{{ old('cuenta_bancaria') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Notas / Condiciones de Compra</label>
                    <textarea name="notas" rows="2" placeholder="Ej: Envían por MRW, pedido mínimo de 50$..." class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm focus:border-primary focus:ring-primary shadow-sm outline-none resize-none">{{ old('notas') }}</textarea>
                </div>
            </div>
            
            <div class="md:col-span-2 flex justify-end mt-2 border-t border-slate-100 pt-4">
                <button type="submit" class="bg-primary hover:opacity-90 text-white font-bold py-2.5 px-6 rounded-lg text-sm transition-all shadow-md shadow-primary/20 flex items-center gap-2 border-none outline-none">
                    <span class="material-symbols-outlined text-[18px]">save</span> Guardar Proveedor Completo
                </button>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                    <th class="px-6 py-4">Proveedor / Empresa</th>
                    <th class="px-6 py-4">Contacto Principal</th>
                    <th class="px-6 py-4">Dirección</th>
                    <th class="px-6 py-4 text-center">Variantes Surtidas</th>
                    <th class="px-6 py-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($proveedores as $proveedor)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <div class="size-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 border border-orange-200">
                            <span class="material-symbols-outlined text-xl">factory</span>
                        </div>
                        <div>
                            <div class="font-bold text-slate-800">{{ $proveedor->nombre }}</div>
                            <div class="text-[11px] font-bold text-slate-400 mt-0.5 tracking-wider">{{ $proveedor->tipo_documento }}-{{ $proveedor->documento_identidad }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            @if($proveedor->telefono)
                                @php
                                    $phoneFormat = preg_replace('/[^0-9]/', '', $proveedor->telefono);
                                    if(str_starts_with($phoneFormat, '0')) $phoneFormat = '58' . substr($phoneFormat, 1);
                                    $msg = urlencode("Hola {$proveedor->nombre}, te saluda el departamento de Compras de Stitch & Co. Te contacto para ");
                                @endphp
                                <a href="https://wa.me/{{ $phoneFormat }}?text={{ $msg }}" target="_blank" title="Enviar WhatsApp a {{ $proveedor->telefono }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-50 border border-green-200 text-green-700 hover:bg-green-100 transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">chat</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Info</span>
                                </a>
                            @endif

                            @if($proveedor->email)
                                @php
                                    $emailSubject = rawurlencode("Revisión de Inventario / Catálogo - Stitch & Co.");
                                    $emailBody = rawurlencode("Estimado proveedor {$proveedor->nombre},\n\nNos dirigimos a usted desde Administración para...\n\nAtentamente,\nCompras Stitch & Co.");
                                @endphp
                                <a href="mailto:{{ $proveedor->email }}?subject={{ $emailSubject }}&body={{ $emailBody }}" title="Enviar Correo a {{ $proveedor->email }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-orange-50 border border-orange-200 text-orange-700 hover:bg-orange-100 transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">mail</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Mail</span>
                                </a>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-xs text-slate-500 leading-tight">
                        {{ $proveedor->direccion ?? 'Sin dirección registrada' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center bg-slate-100 text-slate-700 font-black text-xs px-3 py-1 rounded-lg border border-slate-200" title="Total de productos bajo este proveedor">
                            {{ $proveedor->producto_variantes_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <form method="POST" action="{{ route('admin.proveedores.destroy', $proveedor->id) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este proveedor? Solo se puede si no tiene productos activos.');" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-400 hover:text-rose-500 transition-colors p-2 hover:bg-rose-50 rounded-lg inline-flex items-center justify-center outline-none border-none" title="Eliminar Proveedor">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                        <div class="size-16 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-3">
                            <span class="material-symbols-outlined text-3xl text-slate-300">factory</span>
                        </div>
                        <p class="font-medium">No hay proveedores registrados en el sistema.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($proveedores->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $proveedores->links() }}
        </div>
    @endif
</div>

@endsection
