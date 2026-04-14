@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@section('title', 'Checkout — Stitch & Co')

@section('content')

<div class="flex items-center gap-3 mb-8">
    <a href="{{ route('cart.index') }}" class="text-slate-400 hover:text-primary">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h1 class="text-2xl font-bold text-slate-900 flex items-center gap-3">
        <span class="h-8 w-8 bg-primary/20 text-primary rounded-full flex items-center justify-center text-sm font-bold">1</span>
        Información de Pago y Envío
    </h1>
</div>

@if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('checkout.process') }}" id="checkout-form">
    @csrf
    <input type="hidden" name="cart_payload" id="cart_payload" value="">
    <div class="grid lg:grid-cols-2 gap-12">

        {{-- Column 1: Shipping --}}
        <div class="space-y-8">
            


            {{-- Sección de Modalidad --}}
            <section>
                <div class="flex items-center gap-3 mb-5 border-b border-slate-100 pb-3">
                    <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-sm">local_shipping</span>
                    </div>
                    <h3 class="text-[15px] font-black uppercase tracking-wider text-slate-800">Método de Entrega</h3>
                </div>

                {{-- Banner de Zona Exclusiva --}}
                <div class="bg-gradient-to-r from-amber-50 to-orange-50/30 border border-amber-200/50 rounded-xl p-4 flex items-start gap-4 shadow-sm mb-6">
                    <div class="bg-white rounded-full p-1.5 shadow-sm shrink-0">
                        <span class="material-symbols-outlined text-amber-500 fill-current block text-lg">location_on</span>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-amber-900 text-sm mb-0.5">Operamos solo en la Parroquia Guanare</h4>
                        <p class="text-xs text-amber-800/80 font-medium leading-relaxed">Nuestros despachos (Delivery o Retiro) están limitados por el momento exclusivamente a la Parroquia Guanare, Portuguesa (Incluye la capital y zonas adyacentes).</p>
                    </div>
                </div>

                {{-- Opciones de Entrega Tipo Tarjeta Premium --}}
                <div class="grid grid-cols-2 gap-4" id="shipping-methods">
                    <label class="shipping-option relative flex flex-col items-center justify-center p-6 border-2 border-primary bg-primary/5 rounded-2xl cursor-pointer transition-all group hover:border-primary shadow-sm hover:shadow-md">
                        <input checked name="tipo_envio" type="radio" value="delivery" class="sr-only peer"/>
                        <span class="material-symbols-outlined text-primary text-4xl mb-3 icon transition-transform group-hover:-translate-y-1">two_wheeler</span>
                        <span class="font-black text-slate-900 text-sm block">Delivery</span>
                        
                        <div class="check-indicator absolute top-3 right-3 size-5 rounded-full border-2 border-primary bg-primary flex items-center justify-center text-white transition-opacity">
                            <span class="material-symbols-outlined text-[12px] font-bold">check</span>
                        </div>
                    </label>

                    <label class="shipping-option relative flex flex-col items-center justify-center p-6 border-2 border-slate-100 bg-white rounded-2xl cursor-pointer transition-all group hover:border-primary/50 shadow-sm hover:shadow-md">
                        <input name="tipo_envio" type="radio" value="retiro_tienda" class="sr-only peer"/>
                        <span class="material-symbols-outlined text-slate-400 text-4xl mb-3 icon transition-transform group-hover:-translate-y-1">storefront</span>
                        <span class="font-black text-slate-900 text-sm block mb-1">Retiro</span>
                        <span class="text-[11px] text-slate-700 font-bold uppercase tracking-wider">En tienda física</span>
                        
                        <div class="check-indicator absolute top-3 right-3 size-5 rounded-full border-2 border-slate-200 flex items-center justify-center text-transparent transition-opacity">
                            <span class="material-symbols-outlined text-[12px] font-bold opacity-0">check</span>
                        </div>
                    </label>
                </div>
            </section>

            {{-- Sección de Dirección --}}
            <section id="direccion-container" class="transition-all duration-300">
                <div class="flex items-center gap-3 mb-5 border-b border-slate-100 pb-3">
                    <div class="size-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-sm">home_pin</span>
                    </div>
                    <h3 class="text-[15px] font-black uppercase tracking-wider text-slate-800">Dirección de Entrega</h3>
                </div>

                @if(!empty($direcciones))
                    <div class="space-y-3 mb-6">
                        <p class="text-[13px] font-bold text-slate-600 uppercase tracking-wide">Mis direcciones guardadas</p>
                        @foreach($direcciones as $dir)
                        <label class="flex items-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-primary/50 transition-colors bg-white shadow-sm">
                            <input type="radio" name="direccion_id" value="{{ $dir['id'] ?? $loop->index }}"
                                   class="text-primary focus:ring-primary w-4 h-4 mr-4"/>
                            <span class="text-sm font-semibold text-slate-700">{{ $dir['calle'] ?? '' }}, {{ $dir['ciudad'] ?? '' }}</span>
                        </label>
                        @endforeach
                        <div class="pt-2">
                            <label class="inline-flex items-center gap-2 text-sm text-primary font-bold cursor-pointer hover:text-primary-dark transition-colors">
                                <input type="radio" name="direccion_id" value="new" checked class="text-primary focus:ring-primary w-4 h-4"/>
                                Usar una dirección nueva
                            </label>
                        </div>
                    </div>
                @endif

                <div class="space-y-5 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm" id="nueva-direccion">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-slate-800 mb-2">Ciudad de Destino *</label>
                        <div class="w-full bg-slate-50 border border-slate-100 rounded-xl p-3.5 flex items-center justify-between cursor-not-allowed select-none">
                            <div class="flex items-center gap-2.5 text-slate-900">
                                <span class="material-symbols-outlined text-lg text-slate-500">location_city</span>
                                <span class="font-bold text-sm">Guanare, Portuguesa</span>
                            </div>
                            <span class="bg-slate-200 text-slate-500 text-[10px] px-2 py-0.5 rounded-md font-black uppercase tracking-wider">FIJO</span>
                        </div>
                        <!-- Input real oculto -->
                        <input type="hidden" name="ciudad" value="Guanare" />
                    </div>

                    <div id="sector-container">
                        <label class="block text-xs font-bold uppercase tracking-wide text-slate-800 mb-2">Barrio / Urb. / Sector *</label>
                        <div class="relative">
                            <select name="sector" id="sector-select" required
                                   class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                                <option value="" data-zona="1">Selecciona tu ubicación...</option>
                                <optgroup label="📍 ZONA 1 (Delivery $1.00)">
                                    <option value="Casco Central" data-zona="1">Casco Central / Centro</option>
                                    <option value="Los Camellos" data-zona="1">Los Camellos (Plaza Bolívar)</option>
                                </optgroup>
                                <optgroup label="📍 ZONA 2 (Delivery $2.00)">
                                    <option value="Barrio Sucre" data-zona="2">Barrio Sucre</option>
                                    <option value="Colombia Norte o Sur" data-zona="2">Colombia Norte / Colombia Sur</option>
                                    <option value="San José" data-zona="2">Barrio San José</option>
                                    <option value="Las Flores" data-zona="2">Barrio Las Flores</option>
                                    <option value="San Rafael de la Colonia" data-zona="2">San Rafael de la Colonia</option>
                                    <option value="Falcón" data-zona="2">Falcón</option>
                                    <option value="El Milenio" data-zona="2">El Milenio</option>
                                    <option value="Santa Rita" data-zona="2">Santa Rita</option>
                                    <option value="Urb. La Granja" data-zona="2">Urb. La Granja</option>
                                    <option value="Urb. Los Pinos" data-zona="2">Urb. Los Pinos</option>
                                    <option value="Urb. Guanaguanare" data-zona="2">Urb. Guanaguanare</option>
                                    <option value="Urb. La Coromotana" data-zona="2">Urb. La Coromotana</option>
                                    <option value="Urb. San Francisco" data-zona="2">Urb. San Francisco</option>
                                    <option value="Urb. Italven" data-zona="2">Urb. Italven</option>
                                    <option value="Urb. El Nazareno" data-zona="2">Urb. El Nazareno</option>
                                    <option value="Urb. Mesetas de la Enriquera" data-zona="2">Urb. Mesetas de la Enriquera</option>
                                    <option value="Urb. La Ceiba" data-zona="2">Urb. La Ceiba</option>
                                    <option value="Urb. Divino Niño" data-zona="2">Urb. Divino Niño</option>
                                    <option value="Urb. Nuestro Guanare" data-zona="2">Urb. Nuestro Guanare</option>
                                    <option value="Urb. Cafi Café" data-zona="2">Urb. Cafi Café</option>
                                    <option value="Urb. Hato Modelo" data-zona="2">Urb. Hato Modelo</option>
                                    <option value="Barrio Buenos Aires" data-zona="2">Barrio Buenos Aires</option>
                                    <option value="Barrio Guaicaipuro" data-zona="2">Barrio Guaicaipuro</option>
                                    <option value="Barrio La Pastora" data-zona="2">Barrio La Pastora</option>
                                    <option value="Barrio 12 de Octubre" data-zona="2">Barrio 12 de Octubre</option>
                                    <option value="Barrio El Bolivariano" data-zona="2">Barrio El Bolivariano</option>
                                    <option value="Barrio San Antonio" data-zona="2">Barrio San Antonio</option>
                                    <option value="Barrio Las Américas" data-zona="2">Barrio Las Américas</option>
                                    <option value="Barrio Nuevas Brisas" data-zona="2">Barrio Nuevas Brisas</option>
                                    <option value="La Enriquera" data-zona="2">La Enriquera</option>
                                    <option value="Barrio Portugal" data-zona="2">Barrio Portugal</option>
                                    <option value="Barrio Temaca" data-zona="2">Barrio Temaca</option>
                                    <option value="Barrio El Bolsillo" data-zona="2">Barrio El Bolsillo</option>
                                    <option value="Barrio Los Canales" data-zona="2">Barrio Los Canales</option>
                                    <option value="Barrio La Arenosa" data-zona="2">Barrio La Arenosa</option>
                                    <option value="Sector Los Tanques" data-zona="2">Sector Los Tanques</option>
                                    <option value="Sector Los Guasimitos" data-zona="2">Sector Los Guasimitos</option>
                                    <option value="Sector Los Cocos" data-zona="2">Sector Los Cocos</option>
                                    <option value="Caserío Las Panelas" data-zona="2">Caserío Las Panelas</option>
                                    <option value="Caserío Las Cocuizas" data-zona="2">Caserío Las Cocuizas</option>
                                    <option value="Caserío San Rafael Guasduas" data-zona="2">Caserío San Rafael Guasduas</option>
                                    <option value="Caserío Quebrada del Mamón" data-zona="2">Caserío Quebrada del Mamón</option>
                                    <option value="Av. Los Próceres" data-zona="2">Av. Los Próceres</option>
                                    <option value="El Placer" data-zona="2">El Placer</option>
                                    <option value="Terminal de Pasajeros" data-zona="2">Terminal de Pasajeros</option>
                                    <option value="Garzas" data-zona="2">Garzas</option>
                                    <option value="4 de Febrero" data-zona="2">4 de Febrero (4F)</option>
                                    <option value="Sector Traki" data-zona="2">Sector Traki</option>
                                    <option value="El Progreso" data-zona="2">El Progreso</option>
                                    <option value="Otro (Adyacencias)" data-zona="2">Otro (Sujeto a verificación)</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <!-- Eliminamos el bloque duplicado de Calle, pero conservamos los inputs ocultos para que funcione el mapa y los cálculos -->
                    <input type="hidden" name="latitud" id="input-lat">
                    <input type="hidden" name="longitud" id="input-lng">
                    <input type="hidden" id="tarifa-zona" value="1">

                    <p id="geofencing-alert" class="hidden mt-2 text-xs text-rose-500 font-bold items-center gap-1">
                         <span class="material-symbols-outlined text-[14px]">not_listed_location</span>
                         Lo sentimos, nuestro delivery no llega a esta zona. Sugerimos <a href="#" onclick="document.querySelector('input[value=\'retiro_tienda\']').click(); document.querySelector(`input[value='retiro_tienda']`).closest('.shipping-option').scrollIntoView({behavior:'smooth'}); return false;" class="underline hover:text-rose-700">Retiro en Tienda</a>.
                    </p>

                    <div class="mt-4">
                        <label class="block text-xs font-bold uppercase tracking-wide text-slate-800 mb-2">Dirección Exacta y Referencia *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">share_location</span>
                            </div>
                            <!-- Cambiamos name="referencia" a "calle" para mantener compatibilidad con el backend requerido -->
                            <input name="calle" type="text" value="{{ old('calle', old('referencia')) }}" id="calle-input"
                                   placeholder="Ej: Carrera 5, Casa #12, Portón Negro..."
                                   class="w-full pl-10 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm" required/>
                        </div>
                        
                        <!-- Botón para invocar la API del Mapa -->
                        <button type="button" onclick="checkoutMap.openMapModal()" class="w-full mt-3 py-2.5 bg-indigo-50 hover:bg-primary/10 text-primary text-sm font-bold rounded-xl border border-indigo-200 transition-colors flex items-center justify-center gap-2 shadow-sm">
                            <span class="material-symbols-outlined text-[18px]">travel_explore</span> Usar mi ubicación o buscar en el Mapa
                        </button>
                    </div>

                </div>
            </section>

            {{-- Módulo Condicional de Pagos a Crédito (Layaway ERP) - Movido al espacio en blanco de la Columna 1 --}}
            <div id="abono-module" class="hidden mt-6 p-6 bg-primary/5 border-2 border-primary/20 rounded-2xl shadow-sm relative overflow-hidden transition-all duration-300">
                <div class="absolute top-0 right-0 bg-primary text-white text-[10px] font-black tracking-wider uppercase px-4 py-1.5 rounded-bl-xl shadow-sm">SISTEMA DE CRÉDITO</div>
                
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">payments</span> Pagos a Plazos
                </h3>

                <label class="flex items-start gap-3 cursor-pointer group mb-1">
                    <input type="checkbox" id="is_abono" name="is_abono" value="1" class="mt-1 w-5 h-5 text-primary border-slate-300 focus:ring-primary rounded transition-colors cursor-pointer">
                    <div>
                        <span class="font-bold text-slate-900 block group-hover:text-primary transition-colors">Deseo Apartar mi Pedido a Crédito</span>
                        <span class="text-xs text-slate-500 leading-relaxed block mt-0.5">Paga solo un abono inicial hoy y cancela el resto en <strong class="text-slate-700">7 días</strong>.</span>
                    </div>
                </label>

                <div id="abono-details" class="hidden mt-5 pt-5 border-t border-primary/20 space-y-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-primary mb-2">Monto inicial a pagar (Bs.) *</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-slate-400 font-bold">Bs.</span>
                            </div>
                            <input type="number" step="0.01" id="monto_abonar_bs" min="0" placeholder="0.00" class="w-full pl-10 px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                            <input type="hidden" id="monto_abonar" name="monto_abonar">
                        </div>
                        <div class="flex flex-col mt-2">
                            <p class="text-[11px] text-slate-500"><span class="material-symbols-outlined text-[11px] align-middle mr-0.5">info</span>Mínimo requerido: <strong class="text-slate-700" id="abono-min-req">30% del total</strong></p>
                            <p id="abono-ref-usd" class="text-[11px] font-bold text-slate-400 uppercase mt-0.5 tracking-wide"></p>
                            <p id="abono-error" class="text-[11px] text-rose-500 font-bold hidden bg-rose-50 px-2 py-0.5 rounded mt-1">El monto no cumple el mínimo requerido (30%).</p>
                        </div>
                    </div>
                    
                    <div class="bg-white p-4 rounded-xl border border-primary/20 flex items-center justify-between">
                        <span class="text-sm font-bold text-primary flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">contract</span> Contrato de Crédito
                        </span>
                        <button type="button" onclick="document.getElementById('modal-politicas-credito').classList.remove('hidden')" class="text-xs font-bold text-primary bg-primary/10 hover:bg-primary/20 px-3 py-1.5 rounded-lg transition-colors">Leer Políticas</button>
                    </div>
                    
                    <label class="flex items-start gap-3 cursor-pointer bg-white p-4 rounded-xl border border-slate-200 hover:border-primary/30 transition-all">
                        <input type="checkbox" id="terms_abono" name="terms_abono" value="1" class="mt-0.5 w-4 h-4 text-primary focus:ring-primary rounded cursor-pointer">
                        <span class="text-[11px] text-slate-600 leading-relaxed block flex-1">
                            <strong class="text-slate-800">Acepto las políticas de pago a crédito:</strong> La mercancía se entrega al cancelar el 100%. Cuento con 7 días continuos para el finiquito.
                        </span>
                    </label>
                </div>
            </div>

        </div>

        {{-- Column 2: Payment --}}
        <div class="space-y-6">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500">Método de Pago</h3>
            <div class="space-y-3" id="payment-methods">
                <label class="payment-option flex items-center p-4 border-2 border-primary bg-primary/5 rounded-xl cursor-pointer transition-colors" data-target="pago-movil-details">
                    <input checked name="metodo" type="radio" value="pago_movil" class="text-primary focus:ring-primary mr-4 peer"/>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary icon">phone_iphone</span>
                        <span class="font-bold text-slate-900">Pago Móvil</span>
                    </div>
                </label>
                <label class="payment-option flex items-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors" data-target="transferencia-details">
                    <input name="metodo" type="radio" value="transferencia" class="text-primary focus:ring-primary mr-4 peer"/>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-slate-500 icon">account_balance</span>
                        <span class="font-bold text-slate-900">Transferencia</span>
                    </div>
                </label>
            </div>

            <!-- Formularios desplegables -->
            <div class="mt-4">
                <!-- Pago Móvil Details -->
                <div id="pago-movil-details" class="payment-details hidden space-y-4 bg-primary/5 p-6 rounded-2xl border-2 border-primary/20 shadow-sm transition-all">
                    <div class="bg-white p-4 rounded-lg border border-slate-100 mb-4 shadow-sm text-sm">
                        <p class="font-bold text-slate-900 mb-2">Realiza tu pago a:</p>
                        <ul class="space-y-1 text-slate-800 font-medium">
                            <li><strong>Banco:</strong> Banesco (0134)</li>
                            <li><strong>Teléfono:</strong> 0424-5659154</li>
                            <li><strong>RIF:</strong> J-12345678-9</li>
                        </ul>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-slate-700">Banco Emisor *</label>
                        <select name="banco_pago" class="w-full bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary">
                            <option value="">Selecciona tu banco</option>
                            <option value="0102 - Banco de Venezuela">0102 - Banco de Venezuela</option>
                            <option value="0134 - Banesco">0134 - Banesco</option>
                            <option value="0105 - Mercantil">0105 - Mercantil</option>
                            <option value="0108 - Provincial">0108 - Provincial</option>
                            <option value="0191 - BNC">0191 - BNC</option>
                            <option value="0172 - Bancamiga">0172 - Bancamiga</option>
                            <option value="0114 - Bancaribe">0114 - Bancaribe</option>
                            <option value="0115 - Banco Exterior">0115 - Banco Exterior</option>
                            <option value="0104 - Venezolano de Crédito">0104 - Venezolano de Crédito</option>
                            <option value="0128 - Banco Caroní">0128 - Banco Caroní</option>
                            <option value="0138 - Banco Plaza">0138 - Banco Plaza</option>
                            <option value="0151 - BFC (Fondo Común)">0151 - BFC (Fondo Común)</option>
                            <option value="0157 - Del Sur">0157 - Del Sur</option>
                            <option value="0171 - Banco Activo">0171 - Banco Activo</option>
                            <option value="0174 - Banplus">0174 - Banplus</option>
                            <option value="0137 - Sofitasa">0137 - Sofitasa</option>
                            <option value="0163 - Banco del Tesoro">0163 - Banco del Tesoro</option>
                            <option value="0175 - Digital de Trabajadores">0175 - Digital de Trabajadores (Bicentenario)</option>
                            <option value="0177 - BANFANB">0177 - BANFANB</option>
                            <option value="0166 - Banco Agrícola">0166 - Banco Agrícola</option>
                            <option value="0178 - N58 Banco Digital">0178 - N58 Banco Digital</option>
                            <option value="0169 - R4 Banco (Mi Banco)">0169 - R4 Banco (Mi Banco)</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700">Teléfono Emisor *</label>
                            <div class="flex gap-2">
                                <select name="prefijo_telefono" class="w-[85px] bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary shrink-0 text-sm" required>
                                    <option value="0412">0412</option>
                                    <option value="0414">0414</option>
                                    <option value="0416">0416</option>
                                    <option value="0424">0424</option>
                                    <option value="0426">0426</option>
                                </select>
                                <input name="numero_telefono" type="text" pattern="\d{7}" maxlength="7" placeholder="1234567" class="w-full bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary text-sm tracking-widest" required oninput="this.value=this.value.replace(/\D/g,'')"/>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700">Ref. (Últimos 4/6) *</label>
                            <input name="referencia_pago" type="text" placeholder="Ej: 1234" class="w-full bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary"/>
                        </div>
                    </div>
                </div>

                <!-- Transferencia Details -->
                <div id="transferencia-details" class="payment-details hidden space-y-4 bg-primary/5 p-6 rounded-2xl border-2 border-primary/20 shadow-sm transition-all">
                    <div class="bg-white p-4 rounded-lg border border-slate-100 mb-4 shadow-sm text-sm">
                        <p class="font-bold text-slate-900 mb-2">Datos para transferencia:</p>
                        <ul class="space-y-1 text-slate-800 font-medium">
                            <li><strong>Banco:</strong> Banesco</li>
                            <li><strong>Cuenta:</strong> 0134-0123-12-1234567890</li>
                            <li><strong>Titular:</strong> Stitch & Co C.A.</li>
                            <li><strong>RIF:</strong> J-12345678-9</li>
                        </ul>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5 text-slate-700">Banco Emisor *</label>
                        <select name="banco_pago_transf" class="w-full bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary">
                            <option value="">Selecciona tu banco</option>
                            <option value="0102 - Banco de Venezuela">0102 - Banco de Venezuela</option>
                            <option value="0134 - Banesco">0134 - Banesco</option>
                            <option value="0105 - Mercantil">0105 - Mercantil</option>
                            <option value="0108 - Provincial">0108 - Provincial</option>
                            <option value="0191 - BNC">0191 - BNC</option>
                            <option value="0172 - Bancamiga">0172 - Bancamiga</option>
                            <option value="0114 - Bancaribe">0114 - Bancaribe</option>
                            <option value="0115 - Banco Exterior">0115 - Banco Exterior</option>
                            <option value="0104 - Venezolano de Crédito">0104 - Venezolano de Crédito</option>
                            <option value="0128 - Banco Caroní">0128 - Banco Caroní</option>
                            <option value="0138 - Banco Plaza">0138 - Banco Plaza</option>
                            <option value="0151 - BFC (Fondo Común)">0151 - BFC (Fondo Común)</option>
                            <option value="0157 - Del Sur">0157 - Del Sur</option>
                            <option value="0171 - Banco Activo">0171 - Banco Activo</option>
                            <option value="0174 - Banplus">0174 - Banplus</option>
                            <option value="0137 - Sofitasa">0137 - Sofitasa</option>
                            <option value="0163 - Banco del Tesoro">0163 - Banco del Tesoro</option>
                            <option value="0175 - Digital de Trabajadores">0175 - Digital de Trabajadores (Bicentenario)</option>
                            <option value="0177 - BANFANB">0177 - BANFANB</option>
                            <option value="0166 - Banco Agrícola">0166 - Banco Agrícola</option>
                            <option value="0178 - N58 Banco Digital">0178 - N58 Banco Digital</option>
                            <option value="0169 - R4 Banco (Mi Banco)">0169 - R4 Banco (Mi Banco)</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700">Teléfono Emisor *</label>
                            <div class="flex gap-2">
                                <select name="prefijo_telefono_transf" class="w-[85px] bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary shrink-0 text-sm" required>
                                    <option value="0412">0412</option>
                                    <option value="0414">0414</option>
                                    <option value="0416">0416</option>
                                    <option value="0424">0424</option>
                                    <option value="0426">0426</option>
                                </select>
                                <input name="numero_telefono_transf" type="text" pattern="\d{7}" maxlength="7" placeholder="1234567" class="w-full bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary text-sm tracking-widest" required oninput="this.value=this.value.replace(/\D/g,'')"/>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700">Número de Referencia *</label>
                            <input name="referencia_pago_transf" type="text" placeholder="Ej: 908172931" class="w-full bg-white border border-slate-200 rounded-lg p-2.5 focus:ring-primary focus:border-primary text-sm" required/>
                        </div>
                    </div>
                </div>

                <!-- Input Componente de Comprobante (Universal para pagos offline) -->
                <div id="comprobante-upload-details" class="payment-details hidden space-y-3 bg-white p-6 rounded-2xl border-2 border-dashed border-primary/40 hover:border-primary/60 transition-colors">
                    <label class="block text-sm font-bold text-primary text-center mb-1">Adjuntar Comprobante (Obligatorio)</label>
                    <p class="text-xs text-slate-500 text-center mb-3">Sube la captura de pantalla de la transferencia o pago móvil (JPG, PNG, PDF)</p>
                    <input type="file" name="comprobante" accept="image/*,.pdf" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer"/>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const radios = document.querySelectorAll('input[name="metodo"]');
                    const detailsBlocks = document.querySelectorAll('.payment-details');
                    
                    function updatePaymentUI() {
                        const selectedMethod = document.querySelector('input[name="metodo"]:checked').value;
                        
                        detailsBlocks.forEach(block => block.classList.add('hidden'));
                        document.querySelectorAll('.payment-option').forEach(option => {
                            option.classList.remove('border-2', 'border-primary', 'bg-primary/5');
                            option.classList.add('border', 'border-slate-200');
                            option.querySelector('.icon').classList.remove('text-primary');
                            option.querySelector('.icon').classList.add('text-slate-500');
                        });

                        let targetId = '';
                        if (selectedMethod === 'pago_movil') targetId = 'pago-movil-details';
                        else if (selectedMethod === 'transferencia' || selectedMethod === 'transferencia_p2p') targetId = 'transferencia-details';
                        
                        if (targetId) {
                            document.getElementById(targetId).classList.remove('hidden');
                            
                            if (targetId === 'pago-movil-details') {
                                document.querySelector('select[name="banco_pago"]').setAttribute('required', 'required');
                                document.querySelector('select[name="prefijo_telefono"]').setAttribute('required', 'required');
                                document.querySelector('input[name="numero_telefono"]').setAttribute('required', 'required');
                                document.querySelector('input[name="referencia_pago"]').setAttribute('required', 'required');
                                
                                document.querySelector('select[name="banco_pago_transf"]').removeAttribute('required');
                                document.querySelector('select[name="prefijo_telefono_transf"]').removeAttribute('required');
                                document.querySelector('input[name="numero_telefono_transf"]').removeAttribute('required');
                                document.querySelector('input[name="referencia_pago_transf"]').removeAttribute('required');
                            } else {
                                document.querySelector('select[name="banco_pago_transf"]').setAttribute('required', 'required');
                                document.querySelector('select[name="prefijo_telefono_transf"]').setAttribute('required', 'required');
                                document.querySelector('input[name="numero_telefono_transf"]').setAttribute('required', 'required');
                                document.querySelector('input[name="referencia_pago_transf"]').setAttribute('required', 'required');
                                
                                document.querySelector('select[name="banco_pago"]').removeAttribute('required');
                                document.querySelector('select[name="prefijo_telefono"]').removeAttribute('required');
                                document.querySelector('input[name="numero_telefono"]').removeAttribute('required');
                                document.querySelector('input[name="referencia_pago"]').removeAttribute('required');
                            }
                        }
                        
                        // Show file upload requirement for both
                        if (selectedMethod === 'pago_movil' || selectedMethod === 'transferencia' || selectedMethod === 'transferencia_p2p') {
                            document.getElementById('comprobante-upload-details').classList.remove('hidden');
                            document.querySelector('input[name="comprobante"]').setAttribute('required', 'required');
                        } else {
                            document.getElementById('comprobante-upload-details').classList.add('hidden');
                            document.querySelector('input[name="comprobante"]').removeAttribute('required');
                        }

                        const selectedOption = document.querySelector(`input[value="${selectedMethod}"]`).closest('.payment-option');
                        selectedOption.classList.remove('border', 'border-slate-200');
                        selectedOption.classList.add('border-2', 'border-primary', 'bg-primary/5');
                        selectedOption.querySelector('.icon').classList.remove('text-slate-500');
                        selectedOption.querySelector('.icon').classList.add('text-primary');
                    }

                    radios.forEach(radio => radio.addEventListener('change', updatePaymentUI));
                    updatePaymentUI();

                    // MÉTODOS DE ENVÍO UI
                    const shippingRadios = document.querySelectorAll('input[name="tipo_envio"]');
                    const direccionContainer = document.getElementById('direccion-container');
                    const calleInput = document.getElementById('calle-input');
                    const geofencingAlert = document.getElementById('geofencing-alert');

                    const sectorSelect = document.getElementById('sector-select');
                    const tarifaZonaInput = document.getElementById('tarifa-zona');
                    
                    window.forbiddenZones = /papel[oó]n|guanarito|ospino|boconoito|tucupido|biscucuy|acarigua|araure|tur[eé]n|mesa de cavacas|unellez|vicerrectorado|quebrada de la virgen|municipio sucre|san genaro|morita|pe[ñn]a|c[óo]rdova|la colonia|san jos[eé] de la monta[ñn]a|san juan de guanaguanare|virgen de coromoto/i;

                    // El cobro ahora es 100% regulado por el Select
                    sectorSelect.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        if (selectedOption && selectedOption.dataset.zona) {
                            tarifaZonaInput.value = selectedOption.dataset.zona;
                        } else {
                            tarifaZonaInput.value = '1';
                        }
                        renderCheckoutCart();
                    });

                    // Validar geofencing si el map API auto-rellena la calle
                    const forbiddenRegex = /papel[oó]n|guanarito|ospino|boconoito|tucupido|biscucuy|acarigua|araure|tur[eé]n|mesa de cavacas|unellez|vicerrectorado|quebrada de la virgen|municipio sucre|san genaro|morita|pe[ñn]a|c[óo]rdova|la colonia|san jos[eé] de la monta[ñn]a|san juan de guanaguanare|virgen de coromoto/i;
                    
                    calleInput.addEventListener('input', function() {
                        if (forbiddenRegex.test(this.value)) {
                            geofencingAlert.classList.remove('hidden');
                            geofencingAlert.classList.add('flex');
                            this.classList.add('border-rose-400', 'bg-rose-50');
                            this.setCustomValidity("Dirección fuera de la zona permitida para delivery (Solo válido en Guanare urbano y periurbano)");
                        } else {
                            geofencingAlert.classList.add('hidden');
                            geofencingAlert.classList.remove('flex');
                            this.classList.remove('border-rose-400', 'bg-rose-50');
                            this.setCustomValidity("");
                        }
                    });

                    function updateShippingUI() {
                        const selectedMethod = document.querySelector('input[name="tipo_envio"]:checked').value;
                        
                        document.querySelectorAll('.shipping-option').forEach(option => {
                            option.classList.remove('border-primary', 'bg-primary/5');
                            option.classList.add('border-slate-100', 'bg-white');
                            option.querySelector('.icon').classList.remove('text-primary');
                            option.querySelector('.icon').classList.add('text-slate-400');
                            
                            const indicator = option.querySelector('.check-indicator');
                            if(indicator) {
                                indicator.classList.remove('border-primary', 'bg-primary');
                                indicator.classList.add('border-slate-200');
                                indicator.querySelector('span').classList.remove('opacity-100');
                                indicator.querySelector('span').classList.add('opacity-0');
                            }
                        });

                        const selectedOption = document.querySelector(`input[value="${selectedMethod}"]`).closest('.shipping-option');
                        selectedOption.classList.remove('border-slate-100', 'bg-white');
                        selectedOption.classList.add('border-primary', 'bg-primary/5');
                        selectedOption.querySelector('.icon').classList.remove('text-slate-400');
                        selectedOption.querySelector('.icon').classList.add('text-primary');

                        const activeIndicator = selectedOption.querySelector('.check-indicator');
                        if (activeIndicator) {
                            activeIndicator.classList.remove('border-slate-200');
                            activeIndicator.classList.add('border-primary', 'bg-primary');
                            activeIndicator.querySelector('span').classList.remove('opacity-0');
                            activeIndicator.querySelector('span').classList.add('opacity-100');
                        }

                        // Mostrar u ocultar Dirección de envío y Módulo de Abonos
                        if (selectedMethod === 'retiro_tienda') {
                            direccionContainer.classList.add('hidden');
                            document.querySelector('input[name="calle"]').removeAttribute('required');
                            document.getElementById('sector-select').removeAttribute('required');
                            // Reseteamos errores visuales de Geofencing para que no bloqueen retirar en tienda
                            geofencingAlert.classList.add('hidden');
                            calleInput.classList.remove('border-rose-400', 'bg-rose-50');
                            calleInput.setCustomValidity("");
                            
                            // Mostrar opción de abono solo en retiro en tienda
                            if (document.getElementById('abono-module')) {
                                document.getElementById('abono-module').classList.remove('hidden');
                            }
                        } else {
                            direccionContainer.classList.remove('hidden');
                            document.querySelector('input[name="calle"]').setAttribute('required', 'required');
                            document.getElementById('sector-select').setAttribute('required', 'required');
                            // Re-validar por seguridad si volvemos a delivery
                            if (forbiddenRegex.test(calleInput.value)) {
                                calleInput.setCustomValidity("Dirección fuera de la zona permitida para delivery (Solo válido en Parroquia Guanare)");
                            }
                            
                            // Ocultar opción de abono si no es retiro en tienda (no se fía a domicilio)
                            if (document.getElementById('abono-module')) {
                                document.getElementById('abono-module').classList.add('hidden');
                                document.getElementById('is_abono').checked = false;
                                if (document.getElementById('abono-details')) {
                                    document.getElementById('abono-details').classList.add('hidden');
                                    document.getElementById('monto_abonar').value = '';
                                    if(document.getElementById('terms_abono')) document.getElementById('terms_abono').checked = false;
                                }
                            }
                        }
                    }

                    shippingRadios.forEach(radio => radio.addEventListener('change', () => {
                        updateShippingUI();
                        renderCheckoutCart(); // Trigger dynamic recalculation of delivery fee
                    }));
                    updateShippingUI();

                });
            </script>

            {{-- Order summary --}}
            <div class="mt-8 pt-6 border-t border-slate-100">
                <h4 class="font-bold text-slate-900 mb-4">Resumen</h4>
                
                <div id="checkout-summary-list"></div>
                
                <div class="flex justify-between flex-row items-end mt-4 pt-4 border-t border-slate-200">
                    <span class="text-slate-900 font-black text-lg mb-1">Total a pagar</span>
                    <div id="checkout-total-price" class="text-right flex flex-col items-end">
                        <span class="text-2xl font-black text-primary">Bs. 0.00</span>
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-amber-50 border border-amber-100/60 rounded-xl flex gap-3 text-xs text-amber-700">
                    <span class="material-symbols-outlined text-amber-500 text-lg mt-0.5">info</span>
                    <p>Total calculado a la tasa BCV oficial: <strong class="font-black">Bs. {{ number_format(bcv_rate(), 2, ',', '.') }}</strong><br>Esta tasa quedará registrada en su factura al momento de presionar Finalizar Compra.</p>
                </div>


                <button type="submit"
                        class="w-full py-4 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/30 transition-all active:scale-95 mt-6">
                    Finalizar compra
                </button>
                
                <button type="button" onclick="cancelTransaction()" class="w-full py-4 mt-3 bg-red-50 hover:bg-red-100 text-red-600 font-bold rounded-xl transition-all">
                    Cancelar Compra
                </button>

                <p class="text-[10px] text-center text-slate-400 mt-4">
                    Al hacer clic, aceptas nuestros <a class="underline" href="#">Términos y Condiciones</a>
                </p>
            </div>
        </div>
    </div>
</form>

<!-- Modal Políticas de Pago a Crédito (Sistema de Apartado) -->
<div id="modal-politicas-credito" class="fixed inset-0 z-[100] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center hidden transition-opacity">
    <div class="bg-white w-full max-w-lg mx-4 rounded-2xl shadow-2xl p-6 relative max-h-[90vh] flex flex-col">
        <button type="button" onclick="document.getElementById('modal-politicas-credito').classList.add('hidden')" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition-colors">
            <span class="material-symbols-outlined text-sm font-bold">close</span>
        </button>
        <div class="flex items-center gap-3 mb-4 shrink-0 border-b border-slate-100 pb-3">
            <div class="size-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                <span class="material-symbols-outlined">receipt_long</span>
            </div>
            <div>
                <h3 class="font-black text-slate-900 text-lg leading-tight">Políticas de Crédito y Apartado</h3>
                <p class="text-xs text-slate-500 font-medium">Contrato Simplificado de Compra a Plazos</p>
            </div>
        </div>
        <div class="overflow-y-auto pr-2 custom-scrollbar text-sm text-slate-700 space-y-4">
            <p>Al utilizar el <strong>Sistema de Pago a Crédito (Apartado)</strong> de Stitch & Co, usted acepta de manera irrevocable los siguientes términos operacionales:</p>
            
            <div class="space-y-3">
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <h4 class="font-bold text-slate-900 mb-1 flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> 1. Reserva y Conservación</h4>
                    <p class="text-[13px] leading-relaxed">Con la recepción del primer pago (mínimo exigido del 30% del importe total de la orden), nosotros apartaremos del inventario la mercancía por su seguridad. Su pedido estará asegurado y resguardado en almacén.</p>
                </div>
                
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <h4 class="font-bold text-slate-900 mb-1 flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> 2. Obligación de Entrega</h4>
                    <p class="text-[13px] leading-relaxed text-rose-700 font-medium bg-rose-50/50 p-2 rounded-lg mb-1">📌 La mercancía bajo ninguna circunstancia será despachada o entregada hasta que la orden no conste del 100% pagado.</p>
                    <p class="text-[13px] leading-relaxed">El pago del saldo restante podrá hacerlo en cualquier momento dentro del periodo de gracia o presencialmente el día del retiro en tienda.</p>
                </div>
                
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <h4 class="font-bold text-slate-900 mb-1 flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> 3. Expiración de Reserva</h4>
                    <p class="text-[13px] leading-relaxed">Usted dispone de <strong>Siete (7) Días Calendario</strong> desde hoy para saldar la deuda principal. Si no culmina el pago luego de este tiempo estipulado, la reserva se considera nula y los artículos retornarán al piso de venta.</p>
                </div>
                
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <h4 class="font-bold text-slate-900 mb-1 flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> 4. Emisión Fiscal</h4>
                    <p class="text-[13px] leading-relaxed">Durante la fase de apartado, la plataforma emitirá un recibo provisional contable. La <strong>Factura Fiscal (Seniat)</strong> oficial solo se emite el día de la liquidación y retiro del producto cobrado en su totalidad.</p>
                </div>
            </div>
            <p class="text-[11px] text-slate-400 text-center font-bold uppercase tracking-wider mt-4">Dpto. de Administración - Stitch & Co.</p>
        </div>
        <div class="mt-5 pt-4 border-t border-slate-100 shrink-0">
            <button type="button" onclick="document.getElementById('terms_abono').checked = true; document.getElementById('modal-politicas-credito').classList.add('hidden');" class="w-full py-3.5 bg-indigo-50 border border-indigo-200 text-indigo-700 font-bold rounded-xl hover:bg-indigo-100 transition-colors uppercase tracking-wide text-sm active:scale-95">
                Estoy de acuerdo, cerrar
            </button>
        </div>
    </div>
</div>

<!-- Modal del Mapa de Antigravity -->
<div id="map-modal" class="fixed inset-0 z-[100] bg-slate-900/60 backdrop-blur-md flex items-center justify-center hidden opacity-0 transition-opacity">
    <div class="bg-white w-full max-w-lg mx-4 rounded-2xl shadow-2xl overflow-hidden flex flex-col transform scale-95 transition-transform" id="map-modal-content">
        <div class="p-4 flex items-center justify-between border-b border-slate-100 bg-slate-50">
            <div>
                <h3 class="font-bold text-slate-900 flex items-center gap-2"><span class="material-symbols-outlined text-primary text-[20px]">map</span> Ubicación de Envío</h3>
                <p class="text-xs text-slate-500 font-medium">Buscando en la Parroquia Guanare</p>
            </div>
            <!-- Botón Cerrar (X) Real -->
            <button type="button" onclick="checkoutMap.closeMapModal()" class="w-8 h-8 rounded-full bg-slate-200 hover:bg-slate-300 flex items-center justify-center text-slate-600 font-bold transition-colors z-[600]" title="Cerrar ventana (Esc)">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>
        <!-- Contenedor del mapa (Forzando explicitamente ambas alturas para evitar colapsos visuales) -->
        <div class="relative w-full bg-slate-100" style="height: 450px; min-height: 400px;">
            <div id="leaflet-map" class="z-10" style="width: 100%; height: 100%; min-height: 400px;"></div>
            
            <div id="map-loading" class="absolute inset-0 z-[500] bg-white/80 hidden items-center justify-center backdrop-blur-sm">
                <div class="bg-white p-4 rounded-xl shadow-lg border border-slate-100 flex flex-col items-center">
                    <span class="material-symbols-outlined animate-spin text-primary text-4xl mb-2">refresh</span>
                    <span class="text-sm font-bold text-slate-600">Calculando...</span>
                </div>
            </div>
        </div>
        <div class="p-5 bg-white">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400 mb-2">Dirección Acumulada</p>
            <div class="bg-slate-50 p-3 rounded-lg border border-slate-200 text-sm text-slate-800 font-medium min-h-[48px] flex items-center break-words" id="map-address-preview">
                Mueve el pin por el mapa o haz clic para detectar el nombre de la calle.
            </div>
            <div class="grid grid-cols-2 gap-3 mt-4">
                <button type="button" onclick="checkoutMap.locateMe()" class="w-full py-3.5 bg-indigo-50 border border-indigo-200 text-primary font-bold rounded-xl hover:bg-indigo-100 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">my_location</span> Usar GPS
                </button>
                <button type="button" onclick="checkoutMap.confirmLocation()" class="w-full py-3.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/30 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed" id="btn-confirm-map" disabled>
                    Fijar Dirección
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    const checkoutMap = {
        map: null,
        marker: null,
        lat: 9.0418,
        lng: -69.7421,
        zoom: 14,
        currentAddress: '',
        
        init() {
            if (this.map) return;
            
            this.map = L.map('leaflet-map', {zoomControl: false}).setView([this.lat, this.lng], this.zoom);
            L.control.zoom({position: 'topright'}).addTo(this.map);
            
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap',
                maxZoom: 19
            }).addTo(this.map);

            const customIcon = L.divIcon({
                className: 'custom-pin',
                html: '<div style="margin-top:-36px;margin-left:-18px;font-size:36px;color:#6366f1;filter:drop-shadow(0 4px 6px rgba(0,0,0,0.3));" class="material-symbols-outlined">location_on</div>',
                iconSize: [36, 36],
                iconAnchor: [18, 36]
            });

            this.marker = L.marker([this.lat, this.lng], {
                draggable: true,
                icon: customIcon
            }).addTo(this.map);

            this.marker.on('dragend', () => {
                const position = this.marker.getLatLng();
                this.reverseGeocode(position.lat, position.lng);
            });

            this.map.on('click', (e) => {
                this.marker.setLatLng(e.latlng);
                this.reverseGeocode(e.latlng.lat, e.latlng.lng);
            });
        },

        openMapModal() {
            const modal = document.getElementById('map-modal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                document.getElementById('map-modal-content').classList.remove('scale-95');
                
                if(!this.map) {
                    this.init();
                }
                
                // FIX: Forzar al mapa a recalcular sus dimensiones después de que la animación CSS del modal termina.
                setTimeout(() => {
                    if (this.map) {
                        this.map.invalidateSize();
                        this.map.setView([this.lat, this.lng], this.zoom);
                    }
                }, 300);
            }, 10);
        },

        closeMapModal() {
            const modal = document.getElementById('map-modal');
            modal.classList.add('opacity-0');
            document.getElementById('map-modal-content').classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        },

        locateMe() {
            if (!navigator.geolocation) {
                alert('Tu navegador no soporta geolocalización.');
                return;
            }

            const loader = document.getElementById('map-loading');
            loader.classList.replace('hidden', 'flex');

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    this.map.setView([lat, lng], 16);
                    this.marker.setLatLng([lat, lng]);
                    this.reverseGeocode(lat, lng);
                    loader.classList.replace('flex', 'hidden');
                },
                (error) => {
                    loader.classList.replace('flex', 'hidden');
                    alert('No pudimos acceder a tu GPS. Por favor arrastra el pin manualmente en el mapa.');
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        },

        async reverseGeocode(lat, lng) {
            this.lat = lat;
            this.lng = lng;
            
            const preview = document.getElementById('map-address-preview');
            const btn = document.getElementById('btn-confirm-map');
            
            preview.innerHTML = '<span class="material-symbols-outlined animate-spin mr-2">refresh</span> Rastreando dirección exacta...';
            btn.disabled = true;

            try {
                const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`;
                const response = await fetch(url, { headers: { 'Accept-Language': 'es' }});
                const data = await response.json();
                
                if (data && data.address) {
                    let text = '';
                    if(data.address.road) text += data.address.road;
                    if(data.address.suburb) text += (text ? ', ' : '') + data.address.suburb;
                    if(data.address.neighbourhood) text += (text ? ', ' : '') + data.address.neighbourhood;
                    
                    if(!text) text = data.display_name.split(',')[0]; 
                    
                    this.currentAddress = text;
                    
                    // Geofencing directo en el Mapa
                    const fullAddressStr = data.display_name || '';
                    const fZones = /papel[oó]n|guanarito|ospino|boconoito|tucupido|biscucuy|acarigua|araure|tur[eé]n|mesa de cavacas|unellez|vicerrectorado|quebrada de la virgen|municipio sucre|san genaro|morita|pe[ñn]a|c[óo]rdova|la colonia|san jos[eé] de la monta[ñn]a|san juan de guanaguanare|virgen de coromoto/i;
                    const z2Zones = /sucre|colombia|san jos[eé]|las flores|san rafael|falc[oó]n|milenio|santa rita|pr[oó]ceres|coromotana|guanaguanare|italven|san francisco|enriquera|el placer|arenosa|terminal|garzas|4 de febrero|4f|traki|granja|ceiba|pinos|hato modelo|progreso|nazareno|divino ni[ñn]o|nuestro guanare|cafi caf[eé]|buenos aires|guaicaipuro|pastora|12 de octubre|bolivariano|san antonio|am[eé]ricas|brisas|portugal|temaca|bolsillo|canales|tanques|guasimitos|cocos|panelas|cocuizas|quebrada del mam[oó]n/i;
                    
                    const isForbidden = fZones.test(fullAddressStr) || fZones.test(text);
                    const isPortuguesa = fullAddressStr.includes('Portuguesa');
                    const isGuanare = fullAddressStr.includes('Guanare');
                    const isZone2 = z2Zones.test(fullAddressStr) || z2Zones.test(text);

                    if (isForbidden || !isPortuguesa || !isGuanare) {
                        preview.innerHTML = '<span class="text-rose-500 font-bold flex items-center"><span class="material-symbols-outlined mr-2">block</span> Fuera de zona de cobertura (Mesa de Cavacas, etc. excluidos)</span>';
                        btn.disabled = true; // Forzar bloqueo
                        this.currentAddress = '';
                        this.isZone2 = false;
                    } else {
                        const zoneLabel = isZone2 ? '<span class="px-2 py-0.5 ml-2 bg-indigo-100 text-primary text-[10px] font-black rounded-lg uppercase">ZONA 2 ($2.00)</span>' : '<span class="px-2 py-0.5 ml-2 bg-emerald-100 text-emerald-700 text-[10px] font-black rounded-lg uppercase">ZONA 1 CENTRO ($1.00)</span>';
                        preview.innerHTML = text + zoneLabel;
                        btn.disabled = false;
                        this.isZone2 = isZone2;
                    }
                } else {
                    this.currentAddress = '';
                    this.isZone2 = false;
                    preview.innerHTML = '<span class="text-rose-500 font-bold flex items-center"><span class="material-symbols-outlined mr-2">block</span> Dirección no reconocida en la jurisdicción.</span>';
                    btn.disabled = true;
                }
            } catch(e) {
                this.currentAddress = 'Error de validación (Probar de forma manual)';
                this.isZone2 = false;
                preview.textContent = this.currentAddress;
                btn.disabled = true;
            }
        },

        confirmLocation() {
            const inputCalle = document.getElementById('calle-input');
            const tarifaZonaInput = document.getElementById('tarifa-zona');
            // Solo sobreescribir si está vacío o aceptan. Generalmente es mejor concatenar o reescribir.
            inputCalle.value = this.currentAddress ? this.currentAddress : 'Ubicación seleccionada con GPS';
            
            document.getElementById('input-lat').value = this.lat;
            document.getElementById('input-lng').value = this.lng;
            
            // Actualizar la zona automáticamente según haya detectado el Mapa
            if (this.currentAddress) {
                tarifaZonaInput.value = this.isZone2 ? '2' : '1';
                // Cambiar el select a "Otro" si la ubicación dictamina automáticamente
                const sectorSelect = document.getElementById('sector-select');
                for(let i=0; i<sectorSelect.options.length; i++) {
                    if(sectorSelect.options[i].value.startsWith('Otro')) {
                        sectorSelect.selectedIndex = i;
                        break;
                    }
                }
                if (typeof renderCheckoutCart === 'function') {
                    renderCheckoutCart();
                }
            }

            inputCalle.dispatchEvent(new Event('input')); // dispara la validación JS de geofencing si queda algo
            this.closeMapModal();
        }
    };
</script>
<script>
    function renderCheckoutCart() {
        if (typeof Cart === 'undefined') {
            setTimeout(renderCheckoutCart, 100);
            return;
        }
        
        const items = Cart.getItems();
        if (items.length === 0) {
            window.location.href = '/carrito';
            return;
        }

        const list = document.getElementById('checkout-summary-list');
        list.innerHTML = '';
        
        const bcvRate = {{ bcv_rate() }};

        items.forEach(item => {
            const priceToUse = parseFloat(item.en_oferta ? item.precio_con_descuento : item.precio);
            const subtotalUsd = priceToUse * item.cantidad;
            const subtotalBs = subtotalUsd * bcvRate;
            
            const html = `
                <div class="flex justify-between text-sm text-slate-900 font-medium mb-3">
                    <span class="flex-1 pr-4"><a href="/producto/${item.id}" class="hover:text-primary hover:underline transition-colors block" title="Ver producto">${item.nombre}</a> <span class="text-xs text-slate-600 font-bold">× ${item.cantidad}</span></span>
                    <div class="text-right">
                        <span class="block font-bold text-slate-900 tracking-tight">Bs. ${subtotalBs.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                        <span class="text-[10px] text-slate-600 font-bold">Ref: $${subtotalUsd.toFixed(2)}</span>
                    </div>
                </div>
            `;
            list.insertAdjacentHTML('beforeend', html);
        });

        const subtotalUsd = Cart.getTotal();
        const subtotalBs = subtotalUsd * bcvRate;
        
        const isDelivery = document.querySelector('input[name="tipo_envio"]:checked')?.value === 'delivery';
        const tarifaZone = document.getElementById('tarifa-zona') ? document.getElementById('tarifa-zona').value : '1';
        
        let deliveryUsd = 0.00;
        let deliveryLabel = '';
        if (isDelivery) {
            deliveryUsd = tarifaZone === '2' ? 2.00 : 1.00;
            deliveryLabel = tarifaZone === '2' ? 'Delivery (ZONA 2)' : 'Delivery (Céntrico)';
        }

        const deliveryBs = deliveryUsd * bcvRate;

        const ivaUsd = subtotalUsd * 0.16;
        const ivaBs = ivaUsd * bcvRate;

        const totalUsd = subtotalUsd + ivaUsd + deliveryUsd;
        const totalBs = subtotalBs + ivaBs + deliveryBs;
        
        document.getElementById('checkout-total-price').innerHTML = `
            <div class="text-right w-full pt-2">
                <div class="flex justify-between text-sm text-slate-900 font-semibold mb-1 w-full gap-8">
                    <span>Subtotal:</span>
                    <span>$${subtotalUsd.toFixed(2)}</span>
                </div>
                <div class="flex justify-between text-sm text-slate-900 font-semibold mb-1 w-full gap-8">
                    <span>IVA (16%):</span>
                    <span>$${ivaUsd.toFixed(2)}</span>
                </div>
                <div class="flex justify-between text-sm text-slate-900 border-b border-slate-200 pb-2 mb-2 w-full gap-8 font-bold" style="display: ${isDelivery ? 'flex' : 'none'};">
                    <span class="text-primary">${deliveryLabel}:</span>
                    <span class="text-primary">+$${deliveryUsd.toFixed(2)}</span>
                </div>
                <div class="flex flex-col items-end pt-1">
                    <span class="block text-2xl font-black text-primary">Bs. ${totalBs.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                    <span class="block text-[11px] font-bold text-slate-400 mt-0.5 uppercase tracking-wide">Ref: $${totalUsd.toFixed(2)}</span>
                </div>
            </div>
        `;
        
        // Populate Hidden Input
        document.getElementById('cart_payload').value = JSON.stringify(items);

        // Lógica Estricta de Abonos (ERP)
        const isRetiro = document.querySelector('input[name="tipo_envio"]:checked')?.value === 'retiro_tienda';
        const abonoModule = document.getElementById('abono-module');
        const abonoCheckbox = document.getElementById('is_abono');
        const abonoDetails = document.getElementById('abono-details');
        const inputMontoBs = document.getElementById('monto_abonar_bs');
        const inputMontoHidden = document.getElementById('monto_abonar');
        const termsCheckbox = document.getElementById('terms_abono');
        const errorAbono = document.getElementById('abono-error');
        const refUsdLabel = document.getElementById('abono-ref-usd');
        const minReqLabel = document.getElementById('abono-min-req');

        // Mínimo estricto y solo retiro en tienda
        if (totalUsd > 30 && isRetiro) {
            abonoModule.classList.remove('hidden');
            const minRequiredUsd = (totalUsd * 0.30).toFixed(2);
            const minRequiredBs = (totalUsd * 0.30 * bcvRate).toFixed(2);
            
            inputMontoBs.min = minRequiredBs;
            inputMontoBs.placeholder = `Mín: Bs. ${minRequiredBs}`;
            
            minReqLabel.innerText = `Bs. ${minRequiredBs}`;
            refUsdLabel.innerText = `Equivalente Mínimo: $${minRequiredUsd} (30%)`;
        } else {
            abonoModule.classList.add('hidden');
            abonoCheckbox.checked = false;
            abonoDetails.classList.add('hidden');
            inputMontoBs.value = '';
            inputMontoHidden.value = '';
            termsCheckbox.checked = false;
            errorAbono.classList.add('hidden');
        }

        // Detectar interacción del usuario
        abonoCheckbox.onchange = function() {
            if (this.checked) {
                abonoDetails.classList.remove('hidden');
                termsCheckbox.required = true;
                inputMontoBs.required = true;
            } else {
                abonoDetails.classList.add('hidden');
                termsCheckbox.required = false;
                inputMontoBs.required = false;
                errorAbono.classList.add('hidden');
            }
        };

        // Validar el 30% en local antes del envío y calcular ref USD local
        inputMontoBs.oninput = function() {
            const minRequiredBs = totalUsd * 0.30 * bcvRate;
            const currentBs = parseFloat(this.value);
            
            if (!isNaN(currentBs)) {
                const refUsd = currentBs / bcvRate;
                inputMontoHidden.value = refUsd.toFixed(2);
                refUsdLabel.innerText = `Está Apartando: $${refUsd.toFixed(2)} USD`;
                
                if (currentBs < minRequiredBs) {
                    errorAbono.classList.remove('hidden');
                } else {
                    errorAbono.classList.add('hidden');
                }
            } else {
                inputMontoHidden.value = '';
                const minReqU = (totalUsd * 0.30).toFixed(2);
                refUsdLabel.innerText = `Equivalente Mínimo: $${minReqU} (30%)`;
                errorAbono.classList.add('hidden');
            }
        };
    }

    function cancelTransaction() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Cancelar compra?',
                text: '¿Estás seguro que deseas cancelar y vaciar tu carrito?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'Volver',
                customClass: {
                    title: 'text-xl font-bold text-slate-800',
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg font-bold px-6 outline-none',
                    cancelButton: 'rounded-lg font-bold px-6 outline-none'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (typeof Cart !== 'undefined') Cart.clear();
                    else localStorage.removeItem('stitch_cart');
                    window.location.href = '/';
                }
            });
        } else {
            if(confirm('¿Estás seguro que deseas cancelar y vaciar tu carrito?')) {
                if (typeof Cart !== 'undefined') Cart.clear();
                else localStorage.removeItem('stitch_cart');
                window.location.href = '/';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderCheckoutCart();

        const form = document.getElementById('checkout-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Validando Stock...';
                submitBtn.disabled = true;

                // Sync the latest payload explicitly before submit
                const items = typeof Cart !== 'undefined' ? Cart.getItems() : JSON.parse(localStorage.getItem('stitch_cart') || '[]');
                document.getElementById('cart_payload').value = JSON.stringify(items);

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        // Laravel validation errors (422) or our custom throw
                        throw new Error(data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Error en la validación'));
                    }
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        if (typeof Cart !== 'undefined') Cart.clear();
                        else localStorage.removeItem('stitch_cart');
                        
                        window.location.href = data.redirect || '/';
                    } else {
                        throw new Error(data.message || 'Error desconocido.');
                    }
                })
                .catch(error => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Pago No Procesado',
                            text: error.message,
                            icon: 'error',
                            confirmButtonColor: '#334155',
                            confirmButtonText: 'Aceptar',
                            customClass: {
                                title: 'text-xl font-bold text-slate-800',
                                popup: 'rounded-2xl',
                                confirmButton: 'rounded-lg font-bold px-8 outline-none bg-slate-800 hover:bg-slate-900'
                            }
                        });
                    } else {
                        alert(error.message);
                    }
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        }
    });
</script>
@endpush
