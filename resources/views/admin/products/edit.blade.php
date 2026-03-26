@extends('layouts.admin')

@section('title', 'Editar Variante')

@section('content')

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.products.index') }}" class="text-slate-400 hover:text-primary">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h2 class="text-2xl font-bold text-slate-900">Editar Variante</h2>
    <span class="text-slate-400">—</span>
    <span class="text-primary font-semibold">{{ $variante->producto->nombre ?? '' }}</span>
</div>

@if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.products.update', $variante->id) }}" enctype="multipart/form-data">
    @csrf @method('PATCH')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Main fields --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">palette</span> Detalles de la Variante
                </h3>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Grosor</label>
                        <input name="grosor" type="text" value="{{ old('grosor', $variante->grosor) }}"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4"/>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Color</label>
                        <input name="color" type="text" value="{{ old('color', $variante->color) }}"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4"/>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Marca</label>
                        <input name="marca" type="text" value="{{ old('marca', $variante->marca) }}"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4"/>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Medida cm</label>
                        <input name="cm" type="number" step="0.1" value="{{ old('cm', $variante->cm) }}"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4"/>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Precio Base ($ USD) *</label>
                        <input name="precio_usd" type="number" step="0.01" value="{{ old('precio_usd', $variante->precio_usd) }}" placeholder="0.00"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4" required/>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Stock *</label>
                        <input name="stock" type="number" min="0" value="{{ old('stock', $variante->stock) }}"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4" required/>
                    </div>
                    <div class="col-span-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input name="en_oferta" type="checkbox" value="1" {{ old('en_oferta', $variante->en_oferta) ? 'checked' : '' }}
                                   class="rounded border-slate-300 text-primary focus:ring-primary h-5 w-5"/>
                            <span class="text-sm font-semibold text-slate-700">En oferta</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Descuento (%)</label>
                        <input name="descuento_porcentaje" type="number" min="0" max="100" value="{{ old('descuento_porcentaje', $variante->descuento_porcentaje) }}"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4"/>
                    </div>
                </div>
            </div>
        </div>

        {{-- Image & Actions --}}
        <div class="space-y-6">

            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">image</span> Imagen
                </h3>

                @if($variante->imagen)
                    <img src="{{ asset('storage/' . $variante->imagen) }}" class="w-full rounded-lg object-cover max-h-48 mb-4"/>
                @endif

                <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-primary transition-colors">
                    <span class="material-symbols-outlined text-3xl text-slate-300 mb-2 block">upload_file</span>
                    <label class="cursor-pointer bg-primary/10 text-primary font-semibold text-sm px-4 py-2 rounded-lg hover:bg-primary/20 transition-all">
                        Cambiar imagen
                        <input type="file" name="imagen" accept="image/*" class="hidden" onchange="previewImage(this)"/>
                    </label>
                </div>
                <img id="preview" src="#" alt="Preview" class="mt-4 rounded-lg w-full object-cover hidden max-h-48"/>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-lg hover:bg-primary-dark transition-all shadow-lg shadow-primary/20 mb-3">
                    Guardar Cambios
                </button>
                <a href="{{ route('admin.products.index') }}"
                   class="w-full flex items-center justify-center text-slate-500 font-semibold py-2 rounded-lg hover:bg-slate-50 transition-all text-sm">
                    Cancelar
                </a>
            </div>

        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.getElementById('preview');
                img.src = e.target.result;
                img.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
