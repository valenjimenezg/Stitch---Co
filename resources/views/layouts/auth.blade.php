<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Acceso') | Stitch &amp; Co</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#7c3aed' }
                }
            }
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* ── Panel izquierdo ── */
        .auth-panel-left {
            background: linear-gradient(145deg, #3b0764 0%, #6d28d9 50%, #4c1d95 100%);
            position: relative;
            overflow: hidden;
        }
        .auth-panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        /* Círculo decorativo grande */
        .auth-panel-left .deco-circle-1 {
            position: absolute;
            width: 380px; height: 380px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            top: -80px; right: -100px;
        }
        .auth-panel-left .deco-circle-2 {
            position: absolute;
            width: 220px; height: 220px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            bottom: 60px; left: -60px;
        }
        .auth-panel-left .deco-ring {
            position: absolute;
            width: 180px; height: 180px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.10);
            bottom: 180px; right: 30px;
        }

        /* Tag "feature" en el panel izquierdo */
        .feature-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.10);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 50px;
            padding: 8px 16px;
            color: white;
            font-size: 13px;
            font-weight: 600;
        }

        /* ── Inputs ── */
        .auth-input {
            display: block;
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1.5px solid #e5e7eb;
            background: #fafafa;
            font-size: 14px;
            font-weight: 500;
            color: #0f172a;
            transition: all .2s;
            outline: none;
        }
        .auth-input:focus {
            border-color: #7c3aed;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(124,58,237,0.12);
        }
        .auth-input::placeholder { color: #9ca3af; font-weight: 400; }

        /* Tabs */
        .auth-tab {
            flex: 1;
            padding: 14px;
            font-size: 14px;
            font-weight: 700;
            border-bottom: 2.5px solid transparent;
            color: #9ca3af;
            transition: all .2s;
            background: none;
            cursor: pointer;
        }
        .auth-tab.active {
            color: #7c3aed;
            border-bottom-color: #7c3aed;
        }

        /* Botón principal */
        .auth-btn {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            color: white;
            font-weight: 800;
            font-size: 15px;
            letter-spacing: 0.3px;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(124,58,237,0.35);
            transition: all .2s;
        }
        .auth-btn:hover { transform: translateY(-1px); box-shadow: 0 12px 30px rgba(124,58,237,0.40); }
        .auth-btn:active { transform: scale(0.98); }
        .auth-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

        /* Label */
        .auth-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        /* Aguja SVG animada */
        @keyframes needle-sway {
            0%, 100% { transform: rotate(-3deg); }
            50%       { transform: rotate(3deg); }
        }
        .needle-anim { animation: needle-sway 3s ease-in-out infinite; transform-origin: center top; }

        /* Scrollbar delgado en el panel de formulario */
        .auth-scroll::-webkit-scrollbar { width: 4px; }
        .auth-scroll::-webkit-scrollbar-track { background: transparent; }
        .auth-scroll::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
    </style>
</head>

<body class="min-h-screen flex" style="background: #f5f3ff;">

<div class="flex w-full min-h-screen">

    {{-- ══════════════════════════════ --}}
    {{-- PANEL IZQUIERDO — Branding    --}}
    {{-- ══════════════════════════════ --}}
    <div class="hidden lg:flex auth-panel-left w-[46%] flex-col justify-between p-12 xl:p-16">
        <div class="deco-circle-1"></div>
        <div class="deco-circle-2"></div>
        <div class="deco-ring"></div>

        <div class="flex flex-col flex-1">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="relative z-10 flex items-center gap-3">
                {{-- Aguja SVG --}}
                <div class="needle-anim" style="width:20px; height:52px; flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 80" fill="none">
                        <rect x="10" y="8" width="4" height="52" rx="2" fill="#F5C518"/>
                        <ellipse cx="12" cy="12" rx="2.2" ry="3.5" fill="#F5C518" stroke="#c49a00" stroke-width="0.8"/>
                        <ellipse cx="12" cy="12" rx="1" ry="1.8" fill="white"/>
                        <polygon points="10,60 14,60 12,76" fill="#F5C518"/>
                        <path d="M14 12 Q22 20 18 35 Q14 48 20 62" stroke="rgba(255,255,255,0.5)" stroke-width="1.4" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <span style="font-size:1.4rem; font-weight:900; color:#fff; letter-spacing:-0.5px; display:block; line-height:1;">Stitch <span style="color:#F5C518;">&amp;</span> Co.</span>
                    <span style="font-size:9px; font-weight:700; color:rgba(255,255,255,0.55); text-transform:uppercase; letter-spacing:0.15em;">Mercería Online</span>
                </div>
            </a>

            {{-- Central copy --}}
            <div class="relative z-10 mt-16 xl:mt-24">
                <div class="mb-8">
                    <span class="feature-tag mb-6 inline-flex">
                        <span class="material-symbols-outlined text-[16px]" style="color:#F5C518;">auto_awesome</span>
                        La mercería que inspira
                    </span>
                    <h2 class="text-4xl xl:text-5xl font-black text-white leading-tight mt-5" style="letter-spacing:-1px;">
                        Todo lo que<br>necesitas para<br><span style="color:#F5C518;">crear.</span>
                    </h2>
                    <p class="text-white/60 mt-5 text-base leading-relaxed max-w-sm">
                        Telas, lanas, botones y accesorios de la más alta calidad. Solo en Guanare, Portuguesa.
                    </p>
                </div>

                {{-- Feature pills --}}
                <div class="flex flex-col gap-3">
                    @foreach([
                        ['auto_awesome', 'Catálogo actualizado diariamente'],
                        ['local_shipping', 'Delivery en Guanare, Portuguesa'],
                        ['verified_user', 'Compras 100% seguras'],
                    ] as [$icon, $text])
                    <div class="flex items-center gap-3">
                        <div style="width:34px; height:34px; border-radius:10px; background:rgba(255,255,255,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <span class="material-symbols-outlined text-[18px]" style="color:#F5C518;">{{ $icon }}</span>
                        </div>
                        <span style="color:rgba(255,255,255,0.75); font-size:14px; font-weight:500;">{{ $text }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>


        {{-- Footer del panel --}}
        <p class="relative z-10 text-white/30 text-xs">
            © {{ date('Y') }} Stitch &amp; Co. Mercería Online · Guanare, Venezuela
        </p>
    </div>

    {{-- ══════════════════════════════ --}}
    {{-- PANEL DERECHO — Formulario    --}}
    {{-- ══════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-h-screen">

        {{-- Top bar móvil --}}
        <header class="lg:hidden bg-white border-b border-slate-100 px-5 h-14 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div style="width:14px; height:34px; flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 80" fill="none">
                        <rect x="10" y="8" width="4" height="52" rx="2" fill="#F5C518"/>
                        <ellipse cx="12" cy="12" rx="2.2" ry="3.5" fill="#F5C518" stroke="#c49a00" stroke-width="0.8"/>
                        <ellipse cx="12" cy="12" rx="1" ry="1.8" fill="white"/>
                        <polygon points="10,60 14,60 12,76" fill="#F5C518"/>
                        <path d="M14 12 Q22 20 18 35 Q14 48 20 62" stroke="#9b7fc4" stroke-width="1.2" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>
                <span style="font-size:1.05rem; font-weight:900; color:#4a1a6e;">Stitch <span style="color:#b8962e;">&amp;</span> Co.</span>
            </a>
            <a href="{{ route('home') }}" class="text-xs font-semibold text-slate-500 hover:text-primary flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">storefront</span> Tienda
            </a>
        </header>

        {{-- Contenido del formulario (centrado verticalmente) --}}
        <div class="flex-1 overflow-y-auto auth-scroll flex items-start lg:items-center justify-center p-5 sm:p-8 lg:p-12">
            <div class="w-full max-w-[460px]">

                {{-- Tabs --}}
                <div class="flex bg-slate-100 rounded-xl p-1 mb-8">
                    <button id="tab-login" onclick="switchTab('login')" class="auth-tab rounded-lg transition-all">
                        Iniciar Sesión
                    </button>
                    <button id="tab-registro" onclick="switchTab('registro')" class="auth-tab rounded-lg transition-all">
                        Registrarse
                    </button>
                </div>

                @yield('content')

                <p class="text-center text-slate-400 text-xs mt-8">
                    © {{ date('Y') }} Stitch &amp; Co. — Todos los derechos reservados.
                </p>
            </div>
        </div>
    </div>

</div>

@stack('scripts')
</body>
</html>
