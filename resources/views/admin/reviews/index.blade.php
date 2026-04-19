@extends('layouts.admin')

@section('title', 'Moderación de Reseñas')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">rate_review</span>
            Moderación de Reseñas
        </h2>
        <p class="text-sm text-slate-500 mt-1">
            Aprueba, responde o elimina las reseñas de clientes antes de publicarlas.
        </p>
    </div>
    @if($pendientes > 0)
    <span class="animate-pulse inline-flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-700 text-sm font-bold px-4 py-2 rounded-xl">
        <span class="material-symbols-outlined text-lg">pending</span>
        {{ $pendientes }} pendiente{{ $pendientes > 1 ? 's' : '' }} de revisión
    </span>
    @endif
</div>

{{-- Alertas de sesión --}}
@if(session('success'))
<div class="mb-4 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-3 text-sm font-semibold">
    <span class="material-symbols-outlined">check_circle</span> {{ session('success') }}
</div>
@endif

{{-- Filtros --}}
<form method="GET" class="flex flex-wrap gap-3 mb-6">
    <select name="estado" onchange="this.form.submit()"
            class="rounded-xl border-slate-200 text-sm font-semibold focus:ring-primary focus:border-primary py-2 pl-3 pr-8">
        <option value="" {{ !request('estado') ? 'selected' : '' }}>Todas</option>
        <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>⏳ Pendientes</option>
        <option value="aprobado" {{ request('estado') === 'aprobado' ? 'selected' : '' }}>✅ Aprobadas</option>
    </select>

    <select name="calificacion" onchange="this.form.submit()"
            class="rounded-xl border-slate-200 text-sm font-semibold focus:ring-primary focus:border-primary py-2 pl-3 pr-8">
        <option value="">Todas las estrellas</option>
        @for($s=5; $s>=1; $s--)
            <option value="{{ $s }}" {{ request('calificacion') == $s ? 'selected' : '' }}>
                {{ str_repeat('★', $s) }}{{ str_repeat('☆', 5-$s) }} ({{ $s }})
            </option>
        @endfor
    </select>
</form>

{{-- Tabla --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-bold uppercase text-[11px] tracking-wider">
                    <th class="px-5 py-4">Producto</th>
                    <th class="px-5 py-4">Cliente</th>
                    <th class="px-5 py-4">Reseña</th>
                    <th class="px-5 py-4 text-center">Estado</th>
                    <th class="px-5 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($reviews as $review)
                <tr class="hover:bg-slate-50 transition-colors {{ !$review->aprobado ? 'bg-amber-50/40' : '' }}">

                    {{-- Producto --}}
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @php $img = $review->producto?->variantes?->first()?->imagen; @endphp
                            <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shrink-0 flex items-center justify-center">
                                @if($img)
                                    <img src="{{ asset($img) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-outlined text-slate-300 text-xl">image</span>
                                @endif
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-sm leading-tight">{{ $review->producto?->nombre ?? '—' }}</p>
                                <p class="text-[11px] text-slate-400">{{ $review->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Cliente --}}
                    <td class="px-5 py-4">
                        <p class="font-semibold text-slate-900 text-sm">{{ $review->user?->name ?? '—' }}</p>
                        <p class="text-[11px] text-slate-400">{{ $review->user?->email }}</p>
                        @if($review->verified_purchase)
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded-full mt-1">
                                <span class="material-symbols-outlined text-[10px]">verified</span> Compra verificada
                            </span>
                        @endif
                    </td>

                    {{-- Contenido de la reseña --}}
                    <td class="px-5 py-4 max-w-xs">
                        {{-- Estrellas --}}
                        <div class="flex items-center gap-0.5 mb-1 text-amber-400">
                            @for($i=1; $i<=5; $i++)
                                <span class="material-symbols-outlined text-[13px]" style="font-variation-settings: 'FILL' {{ $i <= $review->calificacion ? '1' : '0' }}">star</span>
                            @endfor
                        </div>
                        @if($review->titulo)
                            <p class="font-bold text-slate-900 text-sm leading-snug">{{ $review->titulo }}</p>
                        @endif
                        <p class="text-slate-600 text-sm leading-relaxed line-clamp-3">{{ $review->comentario }}</p>

                        {{-- Respuesta existente --}}
                        @if($review->respuesta_admin)
                            <div class="mt-2 pl-3 border-l-2 border-primary/40 bg-primary/5 rounded-r-lg py-1.5 pr-2">
                                <p class="text-[10px] font-black text-primary uppercase tracking-wider mb-0.5">Tu respuesta</p>
                                <p class="text-[11px] text-slate-600 line-clamp-2">{{ $review->respuesta_admin }}</p>
                            </div>
                        @endif
                    </td>

                    {{-- Estado --}}
                    <td class="px-5 py-4 text-center">
                        @if($review->aprobado)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold uppercase">
                                <span class="material-symbols-outlined text-[12px]">check_circle</span> Publicada
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-[10px] font-bold uppercase">
                                <span class="material-symbols-outlined text-[12px]">pending</span> Pendiente
                            </span>
                        @endif
                    </td>

                    {{-- Acciones --}}
                    <td class="px-5 py-4">
                        <div class="flex flex-col items-end gap-2">

                            {{-- Aprobar --}}
                            @if(!$review->aprobado)
                            <form method="POST" action="{{ route('admin.reviews.approve', $review->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="flex items-center gap-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span> Aprobar
                                </button>
                            </form>
                            @endif

                            {{-- Responder --}}
                            <button type="button"
                                    onclick="toggleResponder({{ $review->id }})"
                                    class="flex items-center gap-1 bg-primary/10 hover:bg-primary/20 text-primary border border-primary/20 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                <span class="material-symbols-outlined text-[14px]">reply</span>
                                {{ $review->respuesta_admin ? 'Editar resp.' : 'Responder' }}
                            </button>

                            {{-- Eliminar --}}
                            <form method="POST" action="{{ route('admin.reviews.reject', $review->id) }}"
                                  onsubmit="return confirm('¿Eliminar esta reseña? Esta acción no se puede deshacer.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="flex items-center gap-1 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                    <span class="material-symbols-outlined text-[14px]">delete</span> Eliminar
                                </button>
                            </form>
                        </div>

                        {{-- Panel de respuesta (oculto por defecto) --}}
                        <div id="responder-{{ $review->id }}" class="hidden mt-3">
                            <form method="POST" action="{{ route('admin.reviews.respond', $review->id) }}" class="space-y-2">
                                @csrf @method('PATCH')
                                <textarea name="respuesta_admin" rows="3" required
                                          class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary text-sm py-2 px-3 resize-none"
                                          placeholder="Escribe tu respuesta pública...">{{ $review->respuesta_admin }}</textarea>
                                <div class="flex gap-2 justify-end">
                                    <button type="button" onclick="toggleResponder({{ $review->id }})"
                                            class="text-xs text-slate-500 hover:text-slate-700 font-semibold px-2">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                            class="bg-primary text-white text-xs font-bold px-4 py-1.5 rounded-lg hover:bg-primary-dark transition-colors">
                                        Publicar respuesta
                                    </button>
                                </div>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                        <span class="material-symbols-outlined text-5xl block mb-3 opacity-40">rate_review</span>
                        <p class="font-semibold">No hay reseñas que coincidan con los filtros.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($reviews->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $reviews->links() }}
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    function toggleResponder(id) {
        const panel = document.getElementById('responder-' + id);
        panel.classList.toggle('hidden');
        if (!panel.classList.contains('hidden')) {
            panel.querySelector('textarea').focus();
        }
    }
</script>
@endpush
