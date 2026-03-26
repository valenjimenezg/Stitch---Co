@extends('layouts.app')

@section('title', 'Preguntas Frecuentes — Stitch & Co')

@section('content')

<section class="mt-8 relative overflow-hidden rounded-3xl min-h-[300px] flex items-center px-12">
    <div class="absolute inset-0 bg-gradient-to-br from-primary/20 via-primary/5 to-transparent backdrop-blur-3xl z-0"></div>
    <div class="absolute -top-24 -right-24 size-96 bg-primary/20 rounded-full blur-3xl z-0"></div>
    <div class="absolute top-1/2 -left-24 size-64 bg-secondary/20 rounded-full blur-3xl z-0"></div>
    
    <div class="relative z-10 w-full flex flex-col justify-center text-center">
        <div class="inline-flex justify-center mb-6">
            <span class="bg-white/60 text-primary px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest border border-primary/20 backdrop-blur-md shadow-sm">Ayuda y Soporte</span>
        </div>
        <h1 class="text-5xl font-black text-slate-900 mb-4 tracking-tight">Preguntas Frecuentes</h1>
        <p class="text-slate-600 text-lg max-w-2xl mx-auto font-medium">Todo lo que necesitas saber sobre compras, envíos y devoluciones.</p>
    </div>
</section>

<section class="mt-20 max-w-3xl mx-auto mb-20 space-y-6">
    
    <div class="bg-gradient-to-br from-primary/5 to-white/50 rounded-3xl border border-primary/20 p-8 shadow-sm backdrop-blur-xl relative overflow-hidden group hover:border-primary/40 transition-colors">
        <div class="absolute -right-8 -top-8 size-32 bg-primary/10 rounded-full blur-2xl group-hover:bg-primary/20 transition-colors"></div>
        <h3 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-3">
            <span class="material-symbols-outlined text-primary">local_shipping</span> 
            ¿Hacen envíos a domicilio?
        </h3>
        <p class="text-slate-600 leading-relaxed">
            Actualmente solo realizamos envíos y entregas de forma local dentro de Guanare, Venezuela. Contamos con servicio de delivery a domicilio o puedes retirar tu pedido directamente en nuestra tienda física.
        </p>
    </div>

    <div class="bg-gradient-to-br from-primary/5 to-white/50 rounded-3xl border border-primary/20 p-8 shadow-sm backdrop-blur-xl relative overflow-hidden group hover:border-primary/40 transition-colors">
        <div class="absolute -right-8 -top-8 size-32 bg-primary/10 rounded-full blur-2xl group-hover:bg-primary/20 transition-colors"></div>
        <h3 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-3">
            <span class="material-symbols-outlined text-primary">account_balance_wallet</span> 
            ¿Qué métodos de pago aceptan?
        </h3>
        <p class="text-slate-600 leading-relaxed">
            Aceptamos transferencias bancarias nacionales (Banco de Venezuela, Banesco, Mercantil), Pago Móvil, Binance Pay y pagos en efectivo (dólares o bolívares) si visitas nuestra tienda física en Guanare.
        </p>
    </div>

    <div class="bg-gradient-to-br from-primary/5 to-white/50 rounded-3xl border border-primary/20 p-8 shadow-sm backdrop-blur-xl relative overflow-hidden group hover:border-primary/40 transition-colors">
        <div class="absolute -right-8 -top-8 size-32 bg-primary/10 rounded-full blur-2xl group-hover:bg-primary/20 transition-colors"></div>
        <h3 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-3">
            <span class="material-symbols-outlined text-primary">store</span> 
            ¿Tienen tienda física?
        </h3>
        <p class="text-slate-600 leading-relaxed">
            ¡Así es! Estamos ubicados en el corazón de Guanare, Venezuela. En Avenida 23 entre calles 15 y 16. Nuestro horario es de Lunes a Viernes de 8:00 AM a 6:30 PM.
        </p>
    </div>

    <div class="bg-gradient-to-br from-primary/5 to-white/50 rounded-3xl border border-primary/20 p-8 shadow-sm backdrop-blur-xl relative overflow-hidden group hover:border-primary/40 transition-colors">
        <div class="absolute -right-8 -top-8 size-32 bg-primary/10 rounded-full blur-2xl group-hover:bg-primary/20 transition-colors"></div>
        <h3 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-3">
            <span class="material-symbols-outlined text-primary">inventory_2</span> 
            ¿Puedo apartar productos y retirarlos luego?
        </h3>
        <p class="text-slate-600 leading-relaxed">
            Sí, puedes realizar tu compra por la página web, marcar la opción "Retiro en tienda", realizar tu pago vía transferencia o Pago Móvil, y pasaremos tu pedido al área de entregas para que lo retires cuando gustes en nuestro horario laboral.
        </p>
    </div>

    <div class="bg-gradient-to-br from-primary/5 to-white/50 rounded-3xl border border-primary/20 p-8 shadow-sm backdrop-blur-xl relative overflow-hidden group hover:border-primary/40 transition-colors">
        <div class="absolute -right-8 -top-8 size-32 bg-primary/10 rounded-full blur-2xl group-hover:bg-primary/20 transition-colors"></div>
        <h3 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-3">
            <span class="material-symbols-outlined text-primary">sync</span> 
            ¿Se aceptan devoluciones?
        </h3>
        <p class="text-slate-600 leading-relaxed">
            Aceptamos cambios únicamente por defectos de fábrica en un plazo máximo de 3 días tras haber recibido la mercancía. No realizamos devoluciones de dinero ni cambios en telas o cintas una vez cortadas.
        </p>
    </div>

</section>

@endsection
