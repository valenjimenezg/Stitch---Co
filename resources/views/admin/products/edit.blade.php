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
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Medida cm (opcional)</label>
                        <input name="cm" type="number" step="0.1" value="{{ old('cm', $variante->cm) }}" placeholder="Ej: 50"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4"/>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Unidad Medida</label>
                        <select name="unidad_medida" id="unidad_medida" data-legacy="{{ $variante->unidad_medida }}" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4">
                            <option value="{{ $variante->factor_conversion ?? 1 }}">{{ $variante->unidad_nombre ?? $variante->unidad_medida ?? 'Ninguna' }}</option>
                        </select>
                        <input type="hidden" name="unidad_nombre" id="unidad_nombre" value="{{ $variante->unidad_nombre ?? $variante->unidad_medida ?? 'Ninguna' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Factor de Conversión</label>
                        <input name="factor_conversion" id="factor_conversion" type="number" step="1" value="{{ old('factor_conversion', $variante->factor_conversion ?? 1) }}"
                               class="w-full rounded-lg border-slate-200 bg-slate-50 focus:border-primary focus:ring-primary py-2.5 px-4 text-slate-500" readonly/>
                    </div>
                    <div id="hidden-category-data" data-category="{{ strtolower(trim($variante->producto->categoria)) }}"></div>
                    <div>
                        <label id="lbl_precio" class="block text-sm font-semibold text-slate-700 mb-2 flex justify-between">
                            <span>Precio Base (Ref. USD) *</span>
                            <span id="label-precio-calculado" class="text-primary font-black hidden"></span>
                        </label>
                        <input name="precio" id="precio_usd" type="number" step="0.01" value="{{ old('precio', $variante->precio_usd ?? $variante->precio) }}" placeholder="0.00"
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

        const categoryUnits = {
            'boton': { 'Unidad': 1, 'Docena': 12, 'Gruesa': 144 },
            'tela': { 'Metro': 1, 'Yardas': 1, 'Rollo': 50 },
            'aguja': { 'Sobre': 1, 'Blíster': 1, 'Paquete': 10 },
            'hilo': { 'Rollo': 1, 'Tubino': 1, 'Bulto': 12 },
            'lana': { 'Estambre': 1, 'Madeja': 10, 'Ovillo': 10 },
            'alfiler': { 'Cajita': 1, 'Rueda': 1, 'Paquete': 12 },
            'cierre': { 'Unidad': 1, 'Metro': 1, 'Docena': 12, 'Rollo': 50 },
            'llave': { 'Unidad': 1, 'Bolsa': 100, 'Caja': 100 },
            'herramienta': { 'Unidad': 1, 'Kit': 1 },
            'default': { 'Ninguna': 1, 'Unidad': 1, 'Rollo': 1, 'Docena': 12, 'Caja': 24, 'Bulto': 100 }
        };

        const selUnidad = document.getElementById('unidad_medida');
        const factorInput = document.getElementById('factor_conversion');
        const hiddenUnidadNombre = document.getElementById('unidad_nombre');
        const precioBaseInput = document.getElementById('precio_usd');
        const labelPrecioCalculado = document.getElementById('label-precio-calculado');
        const categoryData = document.getElementById('hidden-category-data').getAttribute('data-category') || '';
        const legacyUnitData = "<?php echo ($variante->unidad_nombre ?? $variante->unidad_medida ?? 'Ninguna') ?>";
        const legacyFactorData = "<?php echo ($variante->factor_conversion ?? 1) ?>";

        function updateCategoryUnits() {
            let unitsObj = categoryUnits['default'];
            for (const [key, value] of Object.entries(categoryUnits)) {
                if (categoryData.includes(key)) {
                    unitsObj = value;
                    break;
                }
            }

            selUnidad.innerHTML = '';
            let injectedLegacy = false;

            for (const [unitName, factorValue] of Object.entries(unitsObj)) {
                const option = document.createElement('option');
                option.value = factorValue;
                option.text = unitName;
                option.setAttribute('data-name', unitName);
                if(unitName.toLowerCase() === legacyUnitData.toLowerCase() || (factorValue == legacyFactorData && legacyUnitData == unitName)){
                    option.selected = true;
                    injectedLegacy = true;
                }
                selUnidad.appendChild(option);
            }

            if(!injectedLegacy) {
                 const option = document.createElement('option');
                 option.value = legacyFactorData;
                 option.text = legacyUnitData;
                 option.selected = true;
                 option.setAttribute('data-name', legacyUnitData);
                 selUnidad.appendChild(option);
            }
            
            updateFactorValue();
        }

        function updateFactorValue() {
            if(selUnidad.selectedIndex >= 0) {
                const option = selUnidad.options[selUnidad.selectedIndex];
                factorInput.value = option.value;
                hiddenUnidadNombre.value = option.getAttribute('data-name');
            }
            calculatePrice();
        }

        function calculatePrice() {
            let base = parseFloat(precioBaseInput.value) || 0;
            let factor = parseInt(factorInput.value) || 1;
            let unitName = hiddenUnidadNombre.value;
            
            let total = base * factor;
            if(total > 0 && factor > 1) {
                labelPrecioCalculado.textContent = `Total (Por ${unitName}): $${total.toFixed(2)}`;
                labelPrecioCalculado.classList.remove('hidden');
            } else {
                labelPrecioCalculado.classList.add('hidden');
            }
        }

        selUnidad.addEventListener('change', updateFactorValue);
        precioBaseInput.addEventListener('input', calculatePrice);
        
        // initialize
        updateCategoryUnits();
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
