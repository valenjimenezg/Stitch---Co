<style>
/* Quick View Sephora Hardcoded Overrides (Vite JIT Fallback) */
.qv-hover-wrapper { position: relative; display: block; overflow: hidden; }
.qv-hover-btn {
    position: absolute; bottom: 16px; left: 50%; transform: translate(-50%, 10px);
    background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px);
    color: #0f172a; font-size: 10px; font-weight: 900; letter-spacing: 0.1em; text-transform: uppercase;
    padding: 10px 24px; border-radius: 50px; opacity: 0; transition: all 0.3s ease-in-out;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12); z-index: 40; cursor: pointer; border: none; white-space: nowrap;
}
.qv-hover-wrapper:hover .qv-hover-btn { opacity: 1; transform: translate(-50%, 0); }
.qv-hover-btn:hover { background-color: #0f172a; color: white; }
#quick-view-modal { z-index: 10000 !important; }
</style>

<div id="quick-view-modal" class="fixed inset-0 z-[100] hidden items-center justify-center px-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Backdrop oscuro --}}
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0" id="qv-backdrop" onclick="closeQuickView()"></div>

    {{-- Contenedor del Modal --}}
    <div class="relative bg-white rounded-2xl shadow-2xl overflow-hidden max-w-4xl w-full mx-auto transform scale-95 opacity-0 transition-all duration-300 flex flex-col md:flex-row max-h-[90vh]" id="qv-card">
        
        {{-- Botón Cerrar (X) móvil / escritorio --}}
        <button onclick="closeQuickView()" class="absolute top-4 right-4 z-20 w-10 h-10 bg-white/80 backdrop-blur-md rounded-full flex items-center justify-center text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors shadow-sm">
            <span class="material-symbols-outlined">close</span>
        </button>

        {{-- Loader Spinner --}}
        <div id="qv-loader" class="absolute inset-0 z-30 bg-white/90 backdrop-blur-sm flex flex-col items-center justify-center transition-opacity duration-300">
            <span class="material-symbols-outlined animate-spin text-4xl text-primary mb-2">progress_activity</span>
            <span class="text-sm text-slate-500 font-medium tracking-widest uppercase">Cargando...</span>
        </div>

        {{-- Columna Izquierda: Imagen --}}
        <div class="w-full md:w-1/2 bg-slate-50 relative aspect-[4/5] md:aspect-auto">
            <img id="qv-image" src="" alt="Producto" class="absolute inset-0 w-full h-full object-cover">
            <div id="qv-image-placeholder" class="absolute inset-0 flex items-center justify-center text-slate-300 bg-slate-100 hidden">
                <span class="material-symbols-outlined text-6xl">image</span>
            </div>
            <span id="qv-badge" class="absolute top-4 left-4 bg-red-500 text-white text-[10px] font-black px-2 py-1 rounded uppercase shadow-md hidden">Oferta</span>
        </div>

        {{-- Columna Derecha: Información --}}
        <div class="w-full md:w-1/2 p-6 md:p-10 lg:p-12 overflow-y-auto flex flex-col justify-center">
            
            <p id="qv-marca" class="text-[10px] font-bold text-primary uppercase tracking-widest mb-2 hidden"></p>
            <h2 id="qv-title" class="text-2xl md:text-3xl font-black text-slate-900 leading-tight mb-2"></h2>
            
            <p id="qv-subtitle" class="text-sm text-slate-500 font-medium mb-4 flex items-center gap-1 hidden">
                <span class="material-symbols-outlined text-[16px]">straighten</span> <span id="qv-subtitle-text"></span>
            </p>

            <div class="flex items-end gap-3 mb-6">
                <span id="qv-price" class="text-2xl font-black text-slate-900"></span>
                <span id="qv-old-price" class="text-sm text-slate-400 line-through mb-1 hidden"></span>
            </div>

            <p id="qv-desc" class="text-slate-600 text-sm leading-relaxed mb-8 line-clamp-3"></p>

            {{-- Ficha del carrito --}}
            <div class="mt-auto space-y-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="font-medium text-slate-900">Cantidad</span>
                    <span id="qv-stock" class="text-slate-500"></span>
                </div>
                
                <div id="qv-action-area" class="flex gap-4">
                    {{-- Selector de cantidad genérico --}}
                    <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl max-w-[120px]">
                        <button type="button" onclick="qvUpdateQty(-1)" class="w-10 h-12 flex items-center justify-center text-slate-400 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">remove</span>
                        </button>
                        <input type="number" id="qv-qty-input" value="1" min="1" max="1" class="w-full h-12 bg-transparent text-center font-bold text-slate-900 border-none focus:ring-0 p-0 text-lg" readonly>
                        <button type="button" onclick="qvUpdateQty(1)" class="w-10 h-12 flex items-center justify-center text-slate-400 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">add</span>
                        </button>
                    </div>

                    <button id="qv-add-btn" onclick="qvAddToCart()" class="flex-1 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/30 hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">shopping_bag</span>
                        Añadir al Carrito
                    </button>
                </div>
                
                <a id="qv-full-link" href="#" class="block text-center text-xs text-primary font-bold hover:underline mt-4 tracking-widest uppercase">
                    Ver Detalles Completos
                </a>
            </div>
            
        </div>
    </div>
</div>

<script>
    let qvCurrentItem = null;

    function openQuickView(id) {
        const modal = document.getElementById('quick-view-modal');
        const backdrop = document.getElementById('qv-backdrop');
        const card = document.getElementById('qv-card');
        const loader = document.getElementById('qv-loader');
        
        // Show modal and backdrop
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Trigger animations
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            card.classList.remove('opacity-0', 'scale-95');
            card.classList.add('opacity-100', 'scale-100');
        }, 10);
        
        // Show loader, hide stuff temporarily
        loader.classList.remove('opacity-0', 'pointer-events-none');
        
        // Fetch API
        const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
        fetch(`${baseUrl}/api/producto/${id}`)
            .then(response => response.json())
            .then(data => {
                qvCurrentItem = data;
                populateQuickView(data);
                
                // Hide loader
                loader.classList.add('opacity-0', 'pointer-events-none');
            })
            .catch(err => {
                console.error("Error al cargar vista rápida", err);
                closeQuickView();
                alert("No se pudo cargar la información del producto.");
            });
    }

    function populateQuickView(data) {
        document.getElementById('qv-title').innerHTML = data.nombre + (data.color ? `<span class="text-slate-400 font-normal text-xl block md:inline md:ml-2">- ${data.color}</span>` : '');
        
        // Image
        const imgEl = document.getElementById('qv-image');
        const placeholder = document.getElementById('qv-image-placeholder');
        if (data.imagen) {
            imgEl.src = data.imagen;
            imgEl.classList.remove('hidden');
            placeholder.classList.add('hidden');
        } else {
            imgEl.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }

        // Marca
        const marca = document.getElementById('qv-marca');
        if (data.marca) {
            marca.textContent = data.marca;
            marca.classList.remove('hidden');
        } else {
            marca.classList.add('hidden');
        }

        // Subtitle (Talla / Grosor)
        let unidadStr = '';
        const cat = data.categoria ? data.categoria.toLowerCase() : '';
        if (['hilos', 'hilo', 'lanas', 'lana', 'estambres'].includes(cat)) {
            unidadStr = data.grosor ? 'Grosor: ' + data.grosor : (data.cm ? data.cm + 'cm' : 'Unidad/Cono');
        } else if (['kits', 'kit'].includes(cat)) {
            unidadStr = 'Kit completo con accesorios';
        }

        const subtitle = document.getElementById('qv-subtitle');
        if (unidadStr) {
            document.getElementById('qv-subtitle-text').textContent = unidadStr;
            subtitle.classList.remove('hidden');
        } else {
            subtitle.classList.add('hidden');
        }

        // Prices
        const priceEl = document.getElementById('qv-price');
        const oldPriceEl = document.getElementById('qv-old-price');
        const badge = document.getElementById('qv-badge');
        
        priceEl.textContent = `Bs. ${Number(data.precio_con_descuento || data.precio).toFixed(2)}`;
        
        if (data.en_oferta) {
            oldPriceEl.textContent = `Bs. ${Number(data.precio).toFixed(2)}`;
            oldPriceEl.classList.remove('hidden');
            badge.classList.remove('hidden');
        } else {
            oldPriceEl.classList.add('hidden');
            badge.classList.add('hidden');
        }

        // Description
        document.getElementById('qv-desc').textContent = data.descripcion || 'Sin descripción detallada.';
        
        // Stock & Actions
        const stockEl = document.getElementById('qv-stock');
        const inputQty = document.getElementById('qv-qty-input');
        const btnAdd = document.getElementById('qv-add-btn');
        
        inputQty.value = 1;
        inputQty.max = data.stock;
        
        if (data.stock > 0) {
            stockEl.textContent = `Disponibles: ${data.stock} unidades`;
            stockEl.classList.remove('text-red-500');
            stockEl.classList.add('text-slate-500');
            btnAdd.disabled = false;
            btnAdd.innerHTML = `<span class="material-symbols-outlined">shopping_bag</span> Añadir al Carrito`;
            btnAdd.classList.remove('bg-slate-300', 'cursor-not-allowed');
            btnAdd.classList.add('bg-primary', 'hover:shadow-xl', 'hover:-translate-y-0.5');
        } else {
            stockEl.textContent = `Agotado`;
            stockEl.classList.remove('text-slate-500');
            stockEl.classList.add('text-red-500');
            btnAdd.disabled = true;
            btnAdd.innerHTML = `Producto Agotado`;
            btnAdd.classList.add('bg-slate-300', 'cursor-not-allowed');
            btnAdd.classList.remove('bg-primary', 'hover:shadow-xl', 'hover:-translate-y-0.5');
        }

        // Full Link
        document.getElementById('qv-full-link').href = `/producto/${data.id}`;
    }

    function qvUpdateQty(change) {
        const input = document.getElementById('qv-qty-input');
        const max = parseInt(input.max);
        let current = parseInt(input.value);
        if (isNaN(current)) current = 1;
        
        const newVal = current + change;
        if (newVal >= 1 && newVal <= max) {
            input.value = newVal;
        }
    }

    function qvAddToCart() {
        if (!qvCurrentItem || qvCurrentItem.stock <= 0) return;
        
        const qty = parseInt(document.getElementById('qv-qty-input').value) || 1;
        
        if (typeof Cart !== 'undefined') {
            Cart.add(qvCurrentItem.id, qty).then(success => {
                if(success) {
                    window.location.href = '/carrito';
                }
            });
        } else {
            // Fallback para form native en caso de que cart.js no esté.
            // Genero un fomulario dinámico y lo submiteo
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/carrito/agregar';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            form.appendChild(csrfInput);

            const varInput = document.createElement('input');
            varInput.type = 'hidden';
            varInput.name = 'variante_id';
            varInput.value = qvCurrentItem.id;
            form.appendChild(varInput);

            const qtyInput = document.createElement('input');
            qtyInput.type = 'hidden';
            qtyInput.name = 'cantidad';
            qtyInput.value = qty;
            form.appendChild(qtyInput);

            document.body.appendChild(form);
            form.submit();
        }
    }

    function closeQuickView() {
        const modal = document.getElementById('quick-view-modal');
        const backdrop = document.getElementById('qv-backdrop');
        const card = document.getElementById('qv-card');
        
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        card.classList.remove('opacity-100', 'scale-100');
        card.classList.add('opacity-0', 'scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            qvCurrentItem = null;
        }, 300);
    }
</script>
