@extends('layouts.auth')

@section('title', 'Recuperar Contraseña')

@section('content')
<div class="w-full max-w-2xl mx-auto">
    <!-- Header General -->
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-primary">Cambia tu clave de internet</h1>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-6 md:p-8">
        
        <!-- Step Header -->
        <div class="flex items-center gap-4 mb-8">
            <div class="relative flex items-center justify-center w-14 h-14 rounded-full border-2 border-primary text-primary font-bold text-sm bg-white z-10 shrink-0">
                1 de 3
                <!-- "Progress" border logic visual approximation -->
                <svg class="absolute inset-0 w-full h-full text-primary -rotate-90" viewBox="0 0 36 36">
                    <path class="text-slate-200" stroke-width="2" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="text-primary" stroke-dasharray="33, 100" stroke-width="2" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Identifícate</h2>
                <p class="text-sm font-medium text-slate-600">Siguiente paso: Selecciona el método de seguridad</p>
            </div>
        </div>

        <p class="text-slate-600 mb-8 font-medium">Inicia el proceso de cambio de clave de internet ingresando la siguiente información:</p>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form fields -->
        <form method="POST" action="{{ route('password.identify') }}" class="space-y-6 max-w-sm mx-auto md:mx-0 md:ml-16">
            @csrf
            
            <!-- Documento de identidad -->
            <div>
                <label class="block text-sm font-bold text-primary mb-1">Documento de identidad</label>
                <div class="flex border-b-2 border-primary/20 focus-within:border-primary transition-colors pb-1">
                    <select name="document_type" id="document_type" class="bg-transparent border-none focus:ring-0 text-slate-700 font-medium pl-0 pr-6 py-1 cursor-pointer">
                        <option value="V" {{ old('document_type') == 'V' ? 'selected' : '' }}>V</option>
                        <option value="E" {{ old('document_type') == 'E' ? 'selected' : '' }}>E</option>
                        <option value="J" {{ old('document_type') == 'J' ? 'selected' : '' }}>J</option>
                        <option value="G" {{ old('document_type') == 'G' ? 'selected' : '' }}>G</option>
                    </select>
                    <input name="document_number" id="document_number" type="text" value="{{ old('document_number') }}"
                           class="w-full bg-transparent border-none focus:ring-0 px-2 py-1 text-slate-800 placeholder:text-slate-400 font-medium"
                           required autofocus />
                </div>
                <p id="document-error" class="hidden text-xs text-rose-500 mt-1 font-bold"></p>
            </div>

            <!-- Usuario -->
            <div>
                <label class="block text-sm font-bold text-primary mb-1">Usuario</label>
                <div class="relative border-b-2 border-primary/20 focus-within:border-primary transition-colors pb-1">
                    <input name="usuario" type="text" value="{{ old('usuario') }}"
                           class="w-full bg-transparent border-none focus:ring-0 pl-0 pr-8 py-1 text-slate-800 placeholder:text-slate-400 font-medium"
                           placeholder="Ingresa tu Usuario" required />
                    <button type="button" class="absolute right-0 top-1/2 -translate-y-1/2 text-primary hover:text-primary-dark transition-colors" title="El usuario es tu correo electrónico registrado">
                        <span class="material-symbols-outlined text-[20px]">person</span>
                    </button>
                </div>
            </div>

            <!-- Botón alineado a la derecha como en bancos, o full width -->
            <div class="pt-6 text-right md:text-left">
                <button type="submit" id="btn-submit"
                        class="bg-primary hover:bg-primary-dark text-white font-bold py-3 px-8 rounded-lg shadow-lg shadow-primary/20 transition-all active:scale-[0.98] w-full md:w-auto">
                    Continuar
                </button>
            </div>
        </form>

        <div class="mt-10 pt-6 border-t border-slate-100 flex items-center">
            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-500 hover:text-primary transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">arrow_back</span> Volver a inicio
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const docTypeEl = document.getElementById('document_type');
        const docNumEl = document.getElementById('document_number');
        const docErrorEl = document.getElementById('document-error');
        const btnSubmit = document.getElementById('btn-submit');

        docNumEl.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        function validateDocumentLength() {
            const type = docTypeEl.value;
            const numberStr = docNumEl.value.trim();
            if(!numberStr) {
                docErrorEl.classList.add('hidden');
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                return;
            }

            let error = '';
            if ((type === 'V' || type === 'E') && (numberStr.length < 6 || numberStr.length > 8)) {
                error = 'La cédula debe tener entre 6 y 8 números.';
            } else if ((type === 'J' || type === 'G') && numberStr.length !== 9) {
                error = 'El RIF debe tener 9 números.';
            }

            if (error) {
                docErrorEl.textContent = error;
                docErrorEl.classList.remove('hidden');
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                docErrorEl.classList.add('hidden');
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        docNumEl.addEventListener('blur', validateDocumentLength);
        docTypeEl.addEventListener('change', validateDocumentLength);
    });
</script>
@endsection
