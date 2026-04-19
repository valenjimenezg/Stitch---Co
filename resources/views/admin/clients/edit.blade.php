@extends('layouts.admin')

@section('title', 'Editar Cliente')

@section('content')

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.clients.show', $cliente->id) }}" class="text-slate-400 hover:text-primary">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h2 class="text-2xl font-bold text-slate-900">Editar Cliente: <span class="text-primary">{{ $cliente->nombre }} {{ $cliente->apellido }}</span></h2>
</div>

@if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.clients.update', $cliente->id) }}">
    @csrf @method('PUT')
    
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-3xl">
        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">person</span> Datos Personales
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Apellido *</label>
                <input type="text" name="apellido" value="{{ old('apellido', $cliente->apellido) }}" required class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Correo Electrónico *</label>
                <input type="email" name="email" value="{{ old('email', $cliente->email) }}" required class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" placeholder="Ej: 0414-1234567" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
            <div class="col-span-1 sm:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Documento de Identidad</label>
                <div class="flex">
                    <select name="tipo_documento" class="rounded-l-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4 border-r-0 bg-slate-50">
                        <option value="V" {{ old('tipo_documento', $cliente->tipo_documento) == 'V' ? 'selected' : '' }}>V</option>
                        <option value="E" {{ old('tipo_documento', $cliente->tipo_documento) == 'E' ? 'selected' : '' }}>E</option>
                        <option value="J" {{ old('tipo_documento', $cliente->tipo_documento) == 'J' ? 'selected' : '' }}>J</option>
                        <option value="P" {{ old('tipo_documento', $cliente->tipo_documento) == 'P' ? 'selected' : '' }}>P</option>
                        <option value="G" {{ old('tipo_documento', $cliente->tipo_documento) == 'G' ? 'selected' : '' }}>G</option>
                    </select>
                    <input type="text" name="documento_identidad" value="{{ old('documento_identidad', $cliente->documento_identidad) }}" class="flex-1 rounded-r-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4">
                </div>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-6 flex justify-end gap-3">
            <a href="{{ route('admin.clients.show', $cliente->id) }}" class="px-6 py-2.5 rounded-lg font-bold text-slate-600 hover:bg-slate-50 border border-slate-200 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-8 py-2.5 rounded-lg font-bold shadow-md transition-all active:scale-95">
                Guardar Cambios
            </button>
        </div>
    </div>
</form>

@endsection
