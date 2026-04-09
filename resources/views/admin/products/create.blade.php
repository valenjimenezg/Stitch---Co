@extends('layouts.admin')

@section('title', 'Nueva Variante')

@section('content')

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.products.index') }}" class="text-slate-400 hover:text-primary">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h2 class="text-2xl font-bold text-slate-900">Agregar Variante de Producto</h2>
</div>

@if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Main fields --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">info</span> Información General
                </h3>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Categoría *</label>
                        <select id="categoria-select" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4 mb-2">
                            <option value="">— Crear nueva categoría —</option>
                            @foreach($productos->pluck('categoria')->unique()->sort() as $cat)
                                @if(trim($cat) !== '')
                                    <option value="{{ strtolower(trim($cat)) }}" {{ old('categoria') == strtolower(trim($cat)) ? 'selected' : '' }}>
                                        {{ ucfirst(trim($cat)) }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <input name="categoria" id="categoria-input" type="text" value="{{ old('categoria') }}" placeholder="Escribe el nombre de la nueva categoría..."
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4" required/>
                    </div>

                    <div id="nuevo-producto-fields">
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre del Nuevo Producto</label>
                            <input name="nombre" type="text" value="{{ old('nombre') }}" placeholder="Ej: Hilo de Algodón Premium"
                                   class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4"/>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Descripción</label>
                            <textarea name="descripcion" rows="3" placeholder="Descripción del producto..."
                                      class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4 resize-none">{{ old('descripcion') }}</textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Producto Existente (opcional)</label>
                        <select name="producto_id" id="producto_id_select" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5">
                            <option value="">— Crear nuevo producto —</option>
                            @foreach($productos as $p)
                                <option value="{{ $p->id }}" data-category="{{ strtolower(trim($p->categoria)) }}" {{ old('producto_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nombre }} ({{ $p->categoria }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">palette</span> Detalles de la Variante
                </h3>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Grosor (opcional)</label>
                        <input name="grosor" type="text" value="{{ old('grosor') }}" placeholder="Ej: 4mm, fino, grueso"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4"/>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Color (opcional)</label>
                        <input name="color" type="text" value="{{ old('color') }}" placeholder="Ej: Rojo, Azul, Natural"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4"/>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Marca (opcional)</label>
                        <input name="marca" type="text" value="{{ old('marca') }}" placeholder="Ej: Coats, Lion Brand"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4"/>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Medida cm (opcional)</label>
                        <input name="cm" type="number" step="0.1" value="{{ old('cm') }}" placeholder="Ej: 50"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4"/>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Unidad Medida</label>
                        <select name="unidad_medida" id="unidad_medida" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4">
                            <option value="Ninguna">Ninguna</option>
                        </select>
                        <input type="hidden" name="unidad_nombre" id="unidad_nombre" value="Ninguna">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Factor de Conversión</label>
                        <input name="factor_conversion" id="factor_conversion" type="number" step="1" value="{{ old('factor_conversion', 1) }}"
                               class="w-full rounded-lg border-slate-200 bg-slate-50 focus:border-primary focus:ring-primary py-2.5 px-4 text-slate-500" readonly/>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2 flex justify-between">
                            <span>Precio Base (Ref. USD) *</span>
                            <span id="label-precio-calculado" class="text-primary font-black hidden"></span>
                        </label>
                        <input name="precio_usd" id="precio_usd" type="number" step="0.01" value="{{ old('precio_usd') }}" placeholder="0.00"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4" required/>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Stock *</label>
                        <input name="stock" type="number" min="0" value="{{ old('stock', 0) }}"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4" required/>
                    </div>
                    <div class="col-span-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input name="en_oferta" type="checkbox" value="1" {{ old('en_oferta') ? 'checked' : '' }}
                                   class="rounded border-slate-300 text-primary focus:ring-primary h-5 w-5"/>
                            <span class="text-sm font-semibold text-slate-700">En oferta</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Descuento (%)</label>
                        <input name="descuento_porcentaje" type="number" min="0" max="100" value="{{ old('descuento_porcentaje', 0) }}"
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
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:border-primary transition-colors">
                    <span class="material-symbols-outlined text-4xl text-slate-300 mb-3 block">upload_file</span>
                    <p class="text-sm text-slate-500 mb-3">Arrastra una imagen o</p>
                    <label class="cursor-pointer bg-primary/10 text-primary font-semibold text-sm px-4 py-2 rounded-lg hover:bg-primary/20 transition-all">
                        Seleccionar archivo
                        <input type="file" name="imagen" accept="image/*" class="hidden" onchange="previewImage(this)"/>
                    </label>
                </div>
                <img id="preview" src="#" alt="Preview" class="mt-4 rounded-lg w-full object-cover hidden max-h-48"/>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-lg hover:bg-primary-dark transition-all shadow-lg shadow-primary/20 mb-3">
                    Crear Variante
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

    // Show/hide new product fields based on selection
    const productSelect = document.querySelector('[name="producto_id"]');
    const newProductFields = document.getElementById('nuevo-producto-fields');
    const catInput = document.getElementById('categoria-input');
    
    // Dynamic Units JS Map
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

    const unidadSelect = document.getElementById('unidad_medida');
    const factorInput = document.getElementById('factor_conversion');
    const hiddenUnidadNombre = document.getElementById('unidad_nombre');
    const precioBaseInput = document.getElementById('precio_usd');
    const labelPrecioCalculado = document.getElementById('label-precio-calculado');

    function updateCategoryUnits() {
        let currentCategory = '';
        if (productSelect.value) {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            currentCategory = selectedOption.getAttribute('data-category') || '';
        } else {
            currentCategory = catInput.value.toLowerCase().trim();
        }

        // Buscar coincidencia en el map o usar default
        let unitsObj = categoryUnits['default'];
        for (const [key, value] of Object.entries(categoryUnits)) {
            if (currentCategory.includes(key)) {
                unitsObj = value;
                break;
            }
        }

        // Vaciar Select
        unidadSelect.innerHTML = '';
        for (const [unitName, factorValue] of Object.entries(unitsObj)) {
            const option = document.createElement('option');
            option.value = factorValue; // Visualmente guardamos temporal el factor
            option.text = unitName;
            option.setAttribute('data-name', unitName);
            unidadSelect.appendChild(option);
        }
        
        updateFactorValue();
    }

    function updateFactorValue() {
        if(unidadSelect.selectedIndex >= 0) {
            const option = unidadSelect.options[unidadSelect.selectedIndex];
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

    productSelect.addEventListener('change', function() {
        newProductFields.style.display = this.value ? 'none' : 'block';
        if (this.value) {
            catInput.removeAttribute('required');
            // Auto rellenar la categoría padre si se eligió un producto
            const c = this.options[this.selectedIndex].getAttribute('data-category');
            if(c) catInput.value = c;
        } else {
            catInput.setAttribute('required', 'required');
        }
        updateCategoryUnits();
    });

    catInput.addEventListener('input', function() {
        updateCategoryUnits();
        
        // Dynamic Filter: Ocultar/Mostrar productos existentes según la categoría tecleada
        const typedCat = this.value.toLowerCase().trim();
        const options = productSelect.options;
        for(let i = 1; i < options.length; i++) {
            const optCat = options[i].getAttribute('data-category');
            if(!typedCat || optCat.includes(typedCat) || typedCat.includes(optCat)) {
                options[i].style.display = '';
            } else {
                options[i].style.display = 'none';
            }
        }
        // Deseleccionar si el producto elegido desapareció por filtro
        if(productSelect.selectedIndex > 0 && options[productSelect.selectedIndex].style.display === 'none') {
            productSelect.selectedIndex = 0;
            productSelect.dispatchEvent(new Event('change'));
        }
    });

    const catSelect = document.getElementById('categoria-select');
    catSelect.addEventListener('change', function() {
        if(this.value) {
            catInput.value = this.value;
            catInput.style.display = 'none';
        } else {
            catInput.value = '';
            catInput.style.display = 'block';
        }
        catInput.dispatchEvent(new Event('input'));
    });
    
    // Set initial state
    if(catSelect.value) {
        catInput.style.display = 'none';
    }
    unidadSelect.addEventListener('change', updateFactorValue);
    precioBaseInput.addEventListener('input', calculatePrice);

    // Initialize on load
    updateCategoryUnits();
</script>
@endpush
