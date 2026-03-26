@extends('layouts.auth')

@section('title', 'Acceso')

@section('content')
<div class="w-full max-w-md">
    <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-primary/5">

        {{-- Tabs --}}
        <div class="flex border-b border-primary/10">
            <button id="tab-login" onclick="switchTab('login')"
                    class="flex-1 py-4 text-sm font-bold border-b-2 transition-colors">
                Iniciar Sesión
            </button>
            <button id="tab-registro" onclick="switchTab('registro')"
                    class="flex-1 py-4 text-sm font-bold border-b-2 transition-colors">
                Registrarse
            </button>
        </div>

        <div class="p-8">

            {{-- Flash Information Message --}}
            @if(session('info'))
                <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl flex items-start gap-3 shadow-sm">
                    <span class="material-symbols-outlined mt-0.5 text-blue-500">info</span>
                    <p class="text-sm font-medium">{{ session('info') }}</p>
                </div>
            @endif

            {{-- ===== FORM LOGIN ===== --}}
            <div id="panel-login">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-slate-900">¡Bienvenida de nuevo!</h1>
                    <p class="text-slate-500 mt-2">Accede a tu cuenta de Stitch &amp; Co</p>
                </div>

                @if($errors->any() && old('_form') === 'login')
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="_form" value="login">
                    <input type="hidden" name="_tab" value="login">

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico</label>
                        <input name="email" type="email" value="{{ old('email') }}"
                               class="block w-full px-4 py-3 rounded-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                               placeholder="ejemplo@correo.com" required/>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-sm font-medium text-slate-700">Contraseña</label>
                            <a class="text-xs font-semibold text-primary hover:underline" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                        </div>
                        <input name="password" type="password"
                               class="block w-full px-4 py-3 rounded-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                               placeholder="••••••••" required/>
                    </div>

                    <div class="flex items-center">
                        <input name="remember" type="checkbox" id="remember"
                               class="w-4 h-4 rounded border-primary/30 text-primary focus:ring-primary"/>
                        <label class="ml-2 text-sm text-slate-600" for="remember">Mantener sesión iniciada</label>
                    </div>

                    <button type="submit"
                            class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3.5 rounded-lg shadow-lg shadow-primary/20 transition-all active:scale-[0.98]">
                        Entrar
                    </button>
                </form>
            </div>

            {{-- ===== FORM REGISTRO ===== --}}
            <div id="panel-registro">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-slate-900">Crea tu cuenta</h1>
                    <p class="text-slate-500 mt-2">Únete a la comunidad Stitch &amp; Co</p>
                </div>

                @if($errors->any() && old('_form') === 'registro')
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_form" value="registro">
                    <input type="hidden" name="_tab" value="registro">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
                            <input name="nombre" type="text" value="{{ old('nombre') }}"
                                   class="block w-full px-4 py-3 rounded-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                                   placeholder="María" required/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Apellido</label>
                            <input name="apellido" type="text" value="{{ old('apellido') }}"
                                   class="block w-full px-4 py-3 rounded-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                                   placeholder="González" required/>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico</label>
                        <input name="email" type="email" value="{{ old('email') }}"
                               class="block w-full px-4 py-3 rounded-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                               placeholder="ejemplo@correo.com" required/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono (opcional)</label>
                        <input name="telefono" type="tel" value="{{ old('telefono') }}"
                               class="block w-full px-4 py-3 rounded-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                               placeholder="+591 700 00000"/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
                        <input name="password" type="password"
                               class="block w-full px-4 py-3 rounded-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                               placeholder="Mínimo 8 caracteres" required/>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Confirmar Contraseña</label>
                        <input name="password_confirmation" type="password"
                               class="block w-full px-4 py-3 rounded-lg border border-primary/20 bg-primary/5 focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-400"
                               placeholder="Repite tu contraseña" required/>
                    </div>

                    <button type="submit"
                            class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3.5 rounded-lg shadow-lg shadow-primary/20 transition-all active:scale-[0.98]">
                        Crear Cuenta
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ACTIVE   = ['border-primary', 'text-primary', 'bg-primary/5'];
    const INACTIVE = ['border-transparent', 'text-slate-400', 'hover:text-slate-600'];

    function switchTab(tab) {
        ['login', 'registro'].forEach(t => {
            const btn   = document.getElementById('tab-' + t);
            const panel = document.getElementById('panel-' + t);
            if (t === tab) {
                btn.classList.add(...ACTIVE);
                btn.classList.remove(...INACTIVE);
                panel.style.display = 'block';
            } else {
                btn.classList.remove(...ACTIVE);
                btn.classList.add(...INACTIVE);
                panel.style.display = 'none';
            }
        });
    }

    // Inicializar con el tab correcto (login por defecto, o registro si hubo error en ese form)
    switchTab('{{ old('_tab', 'login') }}');
</script>
@endpush
