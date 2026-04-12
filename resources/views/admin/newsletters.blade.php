@extends('layouts.admin')
@section('title', 'Comunidad')
@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Comunidad y Newsletter</h2>
        <p class="text-slate-500 text-sm">Suscriptores al boletín de noticias</p>
    </div>
    <a href="{{ route('admin.comunidad.export') }}" class="bg-white text-slate-700 px-4 py-2 rounded-xl text-sm font-bold border border-slate-200 hover:bg-slate-50 hover:text-primary hover:border-primary/30 transition-all flex items-center gap-2 shadow-sm">
        <span class="material-symbols-outlined text-lg">download</span>
        Exportar CSV
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-fade-in">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-slate-50/80 text-slate-400 font-bold uppercase text-[10px] tracking-widest">
                    <th class="px-8 py-4">Email del Suscriptor</th>
                    <th class="px-8 py-4 text-right">Fecha de Suscripción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($suscritos as $sub)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3 text-slate-900 font-semibold">
                            <div class="size-8 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-sm">alternate_email</span>
                            </div>
                            {{ $sub->email }}
                        </div>
                    </td>
                    <td class="px-8 py-5 text-right text-slate-600 font-medium">
                        {{ $sub->created_at ? $sub->created_at->format('d/m/Y H:i') : 'N/A' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="px-8 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <span class="material-symbols-outlined text-5xl text-slate-200">mark_email_read</span>
                            <p class="text-slate-400 italic">No hay suscriptores todavía.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($suscritos->hasPages())
        <div class="px-8 py-5 border-t border-slate-100 bg-slate-50/30">
            {{ $suscritos->links() }}
        </div>
    @endif
</div>
@endsection
