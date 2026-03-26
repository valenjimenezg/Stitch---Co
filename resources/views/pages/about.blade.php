@extends('layouts.app')

@section('title', 'Sobre Nosotros')

@section('content')

{{-- Hero --}}
<section class="mt-8 relative overflow-hidden rounded-3xl min-h-[360px] flex items-center px-6 py-12">
    <div class="absolute inset-0 bg-gradient-to-br from-primary/20 via-primary/5 to-transparent backdrop-blur-3xl"></div>
    <div class="absolute -top-24 -right-24 size-96 bg-primary/20 rounded-full blur-3xl z-0"></div>
    <div class="absolute top-1/2 -left-24 size-64 bg-secondary/20 rounded-full blur-3xl z-0"></div>
    
    <div class="relative z-10 w-full flex flex-col items-center justify-center text-center">
        {{-- Circular Logo Wrapper --}}
        <div class="w-32 h-32 rounded-full flex items-center justify-center bg-primary text-white shadow-xl mb-6 flex-shrink-0 border-4 border-white">
            <span class="material-symbols-outlined text-[64px]">architecture</span>
        </div>

        <div class="inline-flex justify-center mb-6">
            <span class="bg-white/60 text-primary px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest border border-primary/20 backdrop-blur-md shadow-sm">Nuestra Historia</span>
        </div>
        <h1 class="text-5xl font-black text-slate-900 mb-4 tracking-tight">Sobre Nosotros</h1>
        <p class="text-slate-600 text-lg max-w-2xl mx-auto font-medium">Más de 15 años creando sueños, una puntada a la vez</p>
    </div>
</section>

{{-- Nuestra Historia --}}
<section class="mt-20 max-w-4xl mx-auto">
    <div class="text-center mb-12">
        <span class="text-primary font-black uppercase tracking-widest text-xs">Stitch & Co</span>
        <h2 class="text-3xl font-black text-slate-900 mt-2 mb-6">Nuestra Historia</h2>
        <div class="w-16 h-1 bg-primary rounded-full mx-auto"></div>
    </div>
    <div class="prose prose-lg max-w-none text-slate-600 leading-relaxed space-y-6">
        <p><strong class="text-slate-800">Stitch & Co</strong> nació en el corazón de <strong class="text-slate-800">Guanare, Venezuela</strong>, con una visión clara: ser el aliado perfecto para diseñadores, costureras, artesanas y mentes creativas de toda Venezuela.</p>
        <p>Desde nuestros inicios, nos hemos dedicado a ofrecer los mejores insumos de mercería, textiles de alta calidad y materiales para manualidades. Cada hilo, cada tela y cada botón que seleccionamos pasa por un riguroso proceso de curación para garantizar que nuestros clientes siempre tengan acceso a lo mejor del mercado.</p>
        <p>Hoy, gracias al apoyo de nuestra comunidad, hemos dado el salto al mundo digital con esta tienda en línea, llevando la experiencia <strong class="text-slate-800">Stitch & Co</strong> a toda la ciudad de Guanare y sus alrededores. Nuestro compromiso sigue siendo el mismo: <em>calidad, pasión y servicio excepcional.</em></p>
    </div>
</section>

{{-- Valores --}}
<section class="mt-24">
    <div class="text-center mb-12">
        <span class="text-primary font-black uppercase tracking-widest text-xs">Lo que nos define</span>
        <h2 class="text-3xl font-black text-slate-900 mt-2 mb-6">Nuestros Valores</h2>
        <div class="w-16 h-1 bg-primary rounded-full mx-auto"></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @php
            $valores = [
                ['icon' => 'verified', 'titulo' => 'Calidad Premium', 'desc' => 'Seleccionamos cuidadosamente cada producto. Trabajamos con los mejores proveedores nacionales e importados para garantizar materiales de primera.'],
                ['icon' => 'favorite', 'titulo' => 'Pasión por la Costura', 'desc' => 'No solo vendemos materiales, vivimos y respiramos el mundo del tejido, la costura y las manualidades. Entendemos lo que necesitan nuestros clientes.'],
                ['icon' => 'handshake', 'titulo' => 'Atención Personalizada', 'desc' => 'Cada cliente es parte de nuestra familia. Ofrecemos asesoría experta para que encuentres exactamente lo que tu proyecto necesita.'],
            ];
        @endphp
        @foreach($valores as $valor)
            <div class="bg-white rounded-2xl border border-slate-100 p-8 text-center hover:shadow-xl hover:border-primary/20 transition-all duration-300 group">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-6 group-hover:bg-primary/20 transition-colors">
                    <span class="material-symbols-outlined text-3xl text-primary">{{ $valor['icon'] }}</span>
                </div>
                <h3 class="text-lg font-black text-slate-900 mb-3">{{ $valor['titulo'] }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed">{{ $valor['desc'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- Misión y Visión --}}
<section class="mt-24">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Misión --}}
        <div class="bg-gradient-to-br from-primary/5 to-primary/10 rounded-2xl p-10 border border-primary/10 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-primary/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            <div class="relative">
                <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center mb-6 shadow-sm">
                    <span class="material-symbols-outlined text-2xl text-primary">rocket_launch</span>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-4">Nuestra Misión</h3>
                <p class="text-slate-600 leading-relaxed">Proveer a la comunidad creativa venezolana con insumos de mercería y textiles de la más alta calidad, facilitando el acceso a materiales premium a través de una experiencia de compra moderna, confiable y personalizada, tanto en nuestra tienda física como en línea.</p>
            </div>
        </div>
        {{-- Visión --}}
        <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl p-10 border border-slate-200 relative overflow-hidden">
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-slate-200 rounded-full blur-3xl translate-y-1/2 -translate-x-1/3"></div>
            <div class="relative">
                <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center mb-6 shadow-sm">
                    <span class="material-symbols-outlined text-2xl text-primary">visibility</span>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-4">Nuestra Visión</h3>
                <p class="text-slate-600 leading-relaxed">Ser la mercería de referencia en nuestra región, reconocida por la excelencia en la calidad de nuestros productos, la innovación en nuestro servicio digital y el compromiso genuino con el crecimiento de cada artesana, diseñador y emprendedor creativo.</p>
            </div>
        </div>
    </div>
</section>

{{-- Datos / Stats --}}
<section class="mt-24 bg-gradient-to-br from-primary/5 to-white/50 rounded-3xl p-12 border border-primary/20 shadow-sm relative overflow-hidden backdrop-blur-xl">
    <div class="absolute -top-24 -left-24 size-72 bg-primary/20 rounded-full blur-3xl z-0"></div>
    <div class="absolute -bottom-24 -right-24 size-72 bg-secondary/20 rounded-full blur-3xl z-0"></div>
    <div class="relative grid grid-cols-2 lg:grid-cols-4 gap-6 text-center z-10">
        @php
            $stats = [
                ['num' => '15+', 'label' => 'Años de Experiencia'],
                ['num' => '2,000+', 'label' => 'Productos Disponibles'],
                ['num' => '5,000+', 'label' => 'Clientes Satisfechos'],
                ['num' => '100%', 'label' => 'Pasión Venezolana'],
            ];
        @endphp
        @foreach($stats as $stat)
            <div class="flex flex-col items-center justify-center p-8 rounded-2xl bg-white/70 border border-white/50 shadow-sm hover:-translate-y-1 transition-transform backdrop-blur-md">
                <div class="text-5xl font-black text-primary mb-3 drop-shadow-sm">{{ $stat['num'] }}</div>
                <div class="text-[11px] text-slate-600 font-black uppercase tracking-[0.15em] leading-relaxed">{{ $stat['label'] }}</div>
            </div>
        @endforeach
    </div>
</section>

{{-- Ubicación --}}
<section class="mt-24 mb-8">
    <div class="text-center mb-12">
        <span class="text-primary font-black uppercase tracking-widest text-xs">Visítanos</span>
        <h2 class="text-3xl font-black text-slate-900 mt-2 mb-6">Nuestra Tienda</h2>
        <div class="w-16 h-1 bg-primary rounded-full mx-auto"></div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="p-10 flex flex-col justify-center">
                <h3 class="text-xl font-black text-slate-900 mb-6">Stitch & Co Guanare</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-0.5">location_on</span>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Dirección</p>
                            <p class="text-slate-500 text-sm">Av. 23 e/ Calles 15 y 16, Guanare, Venezuela 3350</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-0.5">schedule</span>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Horario</p>
                            <p class="text-slate-500 text-sm">Lunes a Viernes: 8:00 AM – 6:30 PM</p>
                            <p class="text-slate-500 text-sm">Sábados: 8:00 AM – 1:00 PM</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-0.5">call</span>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Teléfono</p>
                            <p class="text-slate-500 text-sm">+58 424 565 9154</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary mt-0.5">mail</span>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Correo</p>
                            <p class="text-slate-500 text-sm">info@stitchandco.com.ve</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-100 min-h-[320px] flex items-center justify-center">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15677.4!2d-69.7490!3d9.0421!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e7c5fcf07b03d6d%3A0x30f0d84e!2sGuanare%2C+Portuguesa!5e0!3m2!1ses!2sve!4v1710000000000!5m2!1ses!2sve" 
                    width="100%" 
                    height="100%" 
                    style="border:0; min-height: 320px;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</section>

@endsection
