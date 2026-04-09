@extends('layouts.admin')

@section('title', 'Editar Variante')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>

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
    @csrf @method('PUT')

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
                        <input name="grosor" id="grosor_select" type="text" value="{{ old('grosor', $variante->grosor) }}" placeholder="Ej: 4mm, fino..." class="w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Color</label>
                        <input name="color" id="color_select" type="text" value="{{ old('color', $variante->color) }}" placeholder="Ej: Rojo, Azul..." class="w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Marca</label>
                        <input name="marca" id="marca_select" type="text" value="{{ old('marca', $variante->marca) }}" placeholder="Ej: Coats, Lion Brand..." class="w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Medida cm</label>
                        <input name="cm" type="number" step="0.1" value="{{ old('cm', $variante->cm) }}"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4"/>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Unidad Medida</label>
                        <select name="unidad_medida" id="unidad_medida" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4">
                            <option value="">Ninguna</option>
                            <option value="Unidad" {{ old('unidad_medida', $variante->unidad_medida) == 'Unidad' ? 'selected' : '' }}>Unidad</option>
                            <option value="Docena" {{ old('unidad_medida', $variante->unidad_medida) == 'Docena' ? 'selected' : '' }}>Docena</option>
                            <option value="Caja" {{ old('unidad_medida', $variante->unidad_medida) == 'Caja' ? 'selected' : '' }}>Caja</option>
                            <option value="Metro" {{ old('unidad_medida', $variante->unidad_medida) == 'Metro' ? 'selected' : '' }}>Metro</option>
                            <option value="Bulto" {{ old('unidad_medida', $variante->unidad_medida) == 'Bulto' ? 'selected' : '' }}>Bulto</option>
                            <option value="Rollo" {{ old('unidad_medida', $variante->unidad_medida) == 'Rollo' ? 'selected' : '' }}>Rollo</option>
                            <option value="cm" {{ old('unidad_medida', $variante->unidad_medida) == 'cm' ? 'selected' : '' }}>Centímetro (cm)</option>
                            <option value="Medida" {{ old('unidad_medida', $variante->unidad_medida) == 'Medida' ? 'selected' : '' }}>Medida</option>
                        </select>
                    </div>
                    <div>
                        <label id="lbl_precio" class="block text-sm font-semibold text-slate-700 mb-2">Precio Base (Ref. USD) *</label>
                        <input name="precio" type="number" step="0.01" value="{{ old('precio', $variante->precio) }}" placeholder="0.00"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4" required/>
                    </div>
                    <div>
                        <label id="lbl_stock" class="block text-sm font-semibold text-slate-700 mb-2">Stock *</label>
                        <input name="stock" type="number" step="0.01" min="0" value="{{ old('stock', $variante->stock) }}"
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
                    <img src="{{ asset($variante->imagen) }}" class="w-full rounded-lg object-cover max-h-48 mb-4"/>
                @endif

                <label class="block cursor-pointer border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-primary hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-3xl text-slate-400 mb-2 block">upload_file</span>
                    <span class="bg-primary/10 text-primary font-semibold text-sm px-4 py-2 rounded-lg inline-block">
                        Cambiar imagen
                    </span>
                    <input type="file" name="imagen" accept="image/*" class="hidden" onchange="previewImage(this)"/>
                </label>
                <img id="preview" src="#" alt="Preview" class="mt-4 rounded-lg w-full object-cover hidden max-h-48"/>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <button type="submit" style="background-color: #9333ea; color: white; border: none; outline: none; box-shadow: 0 10px 15px -3px rgba(147, 51, 234, 0.2);" class="w-full font-bold py-3 rounded-lg hover:opacity-90 transition-all mb-3">
                    Guardar
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
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Init Choices.js
        const baseConfig = { 
            removeItemButton: true,
            maxItemCount: 1, // Limitar a un solo tag
            searchEnabled: true,
            addItems: true,
            allowHTML: false,
            noResultsText: 'Presiona Enter para agregar nuevo tag',
            noChoicesText: 'Sin opciones sugeridas',
            itemSelectText: 'Presiona para seleccionar',
            addItemText: (value) => {
              return `Presiona Enter para agregar <b>"${value}"</b>`;
            },
        };

        const dbColores = @json($colores);
        const dbGrosores = @json($grosores);
        const dbMarcas = @json($marcas);

        const choiceColor = new Choices('#color_select', {
            ...baseConfig, 
            choices: dbColores.map(c => ({value: c, label: c}))
        });

        const choiceGrosor = new Choices('#grosor_select', {
            ...baseConfig, 
            choices: dbGrosores.map(g => ({value: g, label: g}))
        });

        const choiceMarca = new Choices('#marca_select', {
            ...baseConfig,
            choices: dbMarcas.map(m => ({value: m, label: m}))
        });

        // Dinamismo Mercería Labels
        const selUnidad = document.getElementById('unidad_medida');
        const lblPrecio = document.getElementById('lbl_precio');
        const lblStock = document.getElementById('lbl_stock');
        
        function updateLabels() {
            let u = selUnidad.value.toLowerCase();
            if(!u || u === 'ninguna') {
                lblPrecio.innerText = 'Precio Base (Ref. USD) *';
                lblStock.innerText = 'Unidades en Stock *';
                return;
            }
            lblPrecio.innerText = 'Precio por ' + u.charAt(0).toUpperCase() + u.slice(1) + ' (Ref. USD) *';
            
            if(['metro', 'centímetro', 'cm'].includes(u)) {
                lblStock.innerText = u.charAt(0).toUpperCase() + u.slice(1) + 's en Stock * (Admite decimales)';
            } else {
                lblStock.innerText = u.charAt(0).toUpperCase() + u.slice(1) + 's en Stock *';
            }
        }
        
        selUnidad.addEventListener('change', updateLabels);
        updateLabels(); // run on start
    });

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
