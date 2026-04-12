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

            <div class="bg-white rounded-xl p-6 shadow-sm border-2 border-primary/20 bg-primary/5">
                <h3 class="text-lg font-bold text-slate-900 mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">info</span> Información General
                </h3>
                <p class="text-xs text-slate-500 mb-5 flex items-center gap-1.5 font-medium"><span class="material-symbols-outlined text-[13px] text-amber-500">warning</span> Editar el nombre, categoría o descripción afectará a todas las variantes adjuntas a este producto.</p>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Producto de la Variante *</label>
                        <select name="producto_id" id="producto_id_select" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4 mb-4">
                            <option value="">— Separar a un producto nuevo —</option>
                            @foreach($productos as $p)
                                <option value="{{ $p->id }}" data-category="{{ strtolower(trim($p->categoria->nombre ?? '')) }}" {{ old('producto_id', $variante->producto_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nombre }} ({{ $p->categoria->nombre ?? 'Sin Categoría' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="product-edit-fields">
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre del Producto *</label>
                            <input name="nombre" id="nombre-input" type="text" value="{{ old('nombre', $variante->producto->nombre) }}" 
                                   class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4" required/>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Categoría *</label>
                            <select id="categoria-select" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4 mb-2">
                                <option value="">— Escribir una nueva —</option>
                                @foreach(\App\Models\Categoria::orderBy('nombre')->get() as $cat)
                                    @if(trim($cat->nombre) !== '')
                                        <option value="{{ strtolower(trim($cat->nombre)) }}" {{ strtolower(trim(old('categoria', $variante->producto->categoria->nombre ?? ''))) == strtolower(trim($cat->nombre)) ? 'selected' : '' }}>
                                            {{ ucfirst(trim($cat->nombre)) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <input name="categoria" id="categoria-input" type="text" value="{{ old('categoria', $variante->producto->categoria->nombre ?? '') }}" 
                                   class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4 {{ old('categoria', $variante->producto->categoria->nombre ?? '') ? 'hidden' : '' }}" {{ old('categoria', $variante->producto->categoria->nombre ?? '') ? 'style="display:none;"' : '' }} required/>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Descripción</label>
                            <textarea name="descripcion" id="descripcion-input" rows="3"
                                      class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4 resize-none">{{ old('descripcion', $variante->producto->descripcion) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

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
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Unidad Medida *</label>
                        <select name="unidad_medida" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4" required>
                            <option value="Unidad" {{ old('unidad_medida', $variante->unidad_medida) == 'Unidad' ? 'selected' : '' }}>Unidad (Botones, Cierres, Agujas...)</option>
                            <option value="Metro" {{ old('unidad_medida', $variante->unidad_medida) == 'Metro' ? 'selected' : '' }}>Metro (Telas, Cierres continuos...)</option>
                            <option value="Rollo" {{ old('unidad_medida', $variante->unidad_medida) == 'Rollo' ? 'selected' : '' }}>Rollo (Hilos, Cintas, Elásticos...)</option>
                            <option value="Madeja" {{ old('unidad_medida', $variante->unidad_medida) == 'Madeja' ? 'selected' : '' }}>Madeja (Lanas...)</option>
                            <option value="Ovillo" {{ old('unidad_medida', $variante->unidad_medida) == 'Ovillo' ? 'selected' : '' }}>Ovillo (Lanas...)</option>
                            <option value="Tubino" {{ old('unidad_medida', $variante->unidad_medida) == 'Tubino' ? 'selected' : '' }}>Tubino (Hilos...)</option>
                            <option value="Blíster" {{ old('unidad_medida', $variante->unidad_medida) == 'Blíster' ? 'selected' : '' }}>Blíster (Agujas...)</option>
                            <option value="Pieza" {{ old('unidad_medida', $variante->unidad_medida) == 'Pieza' ? 'selected' : '' }}>Pieza general</option>
                            <option value="Ninguna" {{ old('unidad_medida', $variante->unidad_medida) == 'Ninguna' ? 'selected' : '' }}>Ninguna</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Precio Base ($) *</label>
                        <input name="precio" type="number" step="0.01" min="0" value="{{ old('precio', $variante->precio) }}"
                               class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4" required/>
                    </div>
                    <div class="col-span-2 border border-slate-200 rounded-lg p-4 bg-slate-50 shadow-inner">
                        <h4 class="font-bold text-slate-700 mb-4 flex items-center gap-2"><span class="material-symbols-outlined text-primary">dynamic_feed</span> Presentaciones de Venta</h4>
                        <div id="empaques-container" class="space-y-3">
                            @if($variante->empaques && $variante->empaques->count() > 0)
                                @foreach($variante->empaques as $index => $empaque)
                                <div class="flex items-end gap-3 empaque-row relative">
                                    <div class="flex-1">
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">Empaque</label>
                                        <input type="text" name="empaques[{{$index}}][nombre]" value="{{ $empaque->unidad_medida }}" class="w-full rounded-lg border-slate-300 py-2 px-3 text-sm focus:border-primary focus:ring-primary" required>
                                    </div>
                                    <div class="w-24">
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">Trae (Cant)</label>
                                        <input type="number" name="empaques[{{$index}}][factor]" value="{{ $empaque->factor_conversion }}" min="1" class="w-full rounded-lg border-slate-300 py-2 px-3 text-sm focus:border-primary focus:ring-primary" required>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">Precio Compra ($)</label>
                                        <input type="number" step="0.01" name="empaques[{{$index}}][precio]" value="{{ $empaque->precio }}" class="w-full rounded-lg border-slate-300 py-2 px-3 text-sm focus:border-primary focus:ring-primary" required>
                                    </div>
                                    <button type="button" onclick="if(document.querySelectorAll('.empaque-row').length > 1) this.closest('.empaque-row').remove()" class="text-rose-400 hover:text-rose-600 mb-1.5 p-1.5 rounded border border-transparent hover:border-rose-200 hover:bg-rose-50 transition-all">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" onclick="addEmpaqueRow('', 1, false)" class="mt-4 text-primary font-bold text-sm flex items-center gap-1 hover:bg-primary/10 px-3 py-1.5 rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-[18px]">add_circle</span> Añadir otra presentación
                        </button>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Proveedor</label>
                        <select name="proveedor_id" class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary py-2.5 px-4 mb-2">
                            <option value="">— Sin proveedor asignado —</option>
                            @foreach($proveedores as $prov)
                                <option value="{{ $prov->id }}" {{ old('proveedor_id', $variante->proveedor_id) == $prov->id ? 'selected' : '' }}>
                                    {{ $prov->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label id="lbl_stock" class="block text-sm font-semibold text-slate-700 mb-2">Stock Base*</label>
                        <input name="stock_base" type="number" step="0.01" min="0" value="{{ old('stock_base', $variante->stock_base) }}"
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

        let empaqueIndex = {{ ($variante->empaques && $variante->empaques->count() > 0) ? $variante->empaques->count() : 0 }};
        const container = document.getElementById('empaques-container');
        
        window.addEmpaqueRow = function(nombre = '', factor = 1, isRequired = false) {
            const reqStr = isRequired ? 'required' : '';
            const html = `
            <div class="flex items-end gap-3 empaque-row relative">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Empaque</label>
                    <input type="text" name="empaques[${empaqueIndex}][nombre]" value="${nombre}" placeholder="Ej: Docena" class="w-full rounded-lg border-slate-300 py-2 px-3 text-sm focus:border-primary focus:ring-primary" ${reqStr}>
                </div>
                <div class="w-24">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Trae (Cant)</label>
                    <input type="number" name="empaques[${empaqueIndex}][factor]" value="${factor}" min="1" class="w-full rounded-lg border-slate-300 py-2 px-3 text-sm focus:border-primary focus:ring-primary" ${reqStr}>
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Precio Compra ($)</label>
                    <input type="number" step="0.01" name="empaques[${empaqueIndex}][precio]" placeholder="0.00" class="w-full rounded-lg border-slate-300 py-2 px-3 text-sm focus:border-primary focus:ring-primary" required>
                </div>
                <button type="button" onclick="if(document.querySelectorAll('.empaque-row').length > 1) this.closest('.empaque-row').remove()" class="text-rose-400 hover:text-rose-600 mb-1.5 p-1.5 rounded border border-transparent hover:border-rose-200 hover:bg-rose-50 transition-all">
                    <span class="material-symbols-outlined text-[20px]">delete</span>
                </button>
            </div>`;
            container.insertAdjacentHTML('beforeend', html);
            empaqueIndex++;
        };

        const catInput = document.getElementById('categoria-input');

        function updateCategoryUnits() {
            if (container.children.length > 0) return;

            let categoryData = (catInput ? catInput.value : '').toLowerCase().trim();
            let unitsObj = categoryUnits['default'];
            for (const [key, value] of Object.entries(categoryUnits)) {
                if (categoryData.includes(key)) {
                    unitsObj = value;
                    break;
                }
            }

            container.innerHTML = '';
            empaqueIndex = 0;
            let isFirst = true;

            for (const [unitName, factorValue] of Object.entries(unitsObj)) {
                addEmpaqueRow(unitName, factorValue, isFirst);
                isFirst = false;
            }
        }

        const catSelect = document.getElementById('categoria-select');
        const productSelect = document.getElementById('producto_id_select');
        const editFields = document.getElementById('product-edit-fields');
        const currentProductId = "{{ $variante->producto_id }}";
        const nombreInput = document.getElementById('nombre-input');
        const descInput = document.getElementById('descripcion-input');

        function toggleProductFields() {
            if (productSelect.value && productSelect.value !== currentProductId) {
                editFields.style.display = 'none';
                nombreInput.removeAttribute('required');
                catInput.removeAttribute('required');
                
                // Actualizar las unidades según el producto destino
                const c = productSelect.options[productSelect.selectedIndex].getAttribute('data-category');
                if(c) {
                    catInput.value = c;
                    updateCategoryUnits();
                }
            } else {
                editFields.style.display = 'block';
                nombreInput.setAttribute('required', 'required');
                if (!catSelect.value) {
                    catInput.setAttribute('required', 'required');
                }
                updateCategoryUnits(); // based on current cat input
            }
        }
        
        productSelect.addEventListener('change', toggleProductFields);

        catSelect.addEventListener('change', function() {
            if(this.value) {
                catInput.value = this.value;
                catInput.classList.add('hidden');
                catInput.style.display = 'none';
                catInput.removeAttribute('required');
            } else {
                catInput.value = '';
                catInput.classList.remove('hidden');
                catInput.style.display = 'block';
                if (!productSelect.value || productSelect.value === currentProductId) {
                    catInput.setAttribute('required', 'required');
                }
            }
            catInput.dispatchEvent(new Event('input'));
        });

        // Set initial state for Category Input
        if(catSelect.value) {
            catInput.classList.add('hidden');
            catInput.style.display = 'none';
            catInput.removeAttribute('required');
        }


        catInput.addEventListener('input', updateCategoryUnits);
        
        // initialize
        updateCategoryUnits();
        toggleProductFields();
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
