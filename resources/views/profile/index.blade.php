@extends('layouts.app')
@section('title', 'Mi Perfil — Stitch & Co')

@push('styles')
<style>
/* ── Profile Layout ──────────────────────────────────── */
.profile-layout { display: flex; flex-direction: column; gap: 28px; }
@media(min-width:1024px) { .profile-layout { flex-direction: row; gap: 32px; } }

/* ── Sidebar ─────────────────────────────────────────── */
.profile-sidebar {
    width: 100%;
    flex-shrink: 0;
}
@media(min-width:1024px) { .profile-sidebar { width: 240px; } }

.profile-sidebar-card {
    background: #fff;
    border: 1.5px solid #f0ebff;
    border-radius: 24px;
    padding: 24px 20px;
    position: sticky; top: 88px;
    box-shadow: 0 4px 20px -8px rgba(109,40,217,0.10);
}

/* Avatar */
.profile-avatar {
    width: 56px; height: 56px; border-radius: 50%;
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: #fff; font-size: 1.35rem; font-weight: 900;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(109,40,217,0.35);
}
.profile-user-name {
    font-size: 14px; font-weight: 800; color: #1e1b4b; line-height: 1.2;
}
.profile-user-role {
    font-size: 11px; font-weight: 600; color: #7c3aed;
    background: #f3f0ff; padding: 2px 8px; border-radius: 50px;
    display: inline-block; margin-top: 4px;
}

/* Nav links */
.profile-nav { display: flex; flex-direction: column; gap: 2px; margin-top: 20px; }
.profile-nav-link {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 13.5px; font-weight: 600; color: #6b7280;
    text-decoration: none;
    transition: background .15s, color .15s;
}
.profile-nav-link:hover { background: #f3f0ff; color: #7c3aed; }
.profile-nav-link.active {
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: #fff;
    box-shadow: 0 4px 14px rgba(109,40,217,0.28);
}
.profile-nav-link .material-symbols-outlined { font-size: 20px; }

.profile-nav-divider { border: none; border-top: 1px solid #f0ebff; margin: 12px 0; }
.profile-nav-logout {
    display: flex; align-items: center; gap: 10px;
    width: 100%; padding: 10px 14px;
    border-radius: 12px;
    font-size: 13.5px; font-weight: 600; color: #ef4444;
    background: transparent; border: none; cursor: pointer;
    transition: background .15s;
}
.profile-nav-logout:hover { background: #fff5f5; }
.profile-nav-logout .material-symbols-outlined { font-size: 20px; }

/* ── Main Content Card ───────────────────────────────── */
.profile-main { flex: 1; max-width: 720px; }

.profile-page-title {
    font-size: 1.65rem; font-weight: 900; color: #1e1b4b;
    letter-spacing: -.4px; margin-bottom: 4px;
}
.profile-page-sub { font-size: 13.5px; color: #9ca3af; margin-bottom: 24px; }

.profile-form-card {
    background: #fff;
    border: 1.5px solid #f0ebff;
    border-radius: 24px;
    padding: 28px;
    box-shadow: 0 4px 20px -8px rgba(109,40,217,0.08);
}

/* Field */
.pf-field { display: flex; flex-direction: column; gap: 6px; }
.pf-label {
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .08em; color: #4c1d95;
}
.pf-label-note { font-size: 10px; font-weight: 500; color: #9ca3af; text-transform: none; letter-spacing: 0; }
.pf-input {
    width: 100%;
    background: #f8f7ff;
    border: 1.5px solid #ede9fe;
    border-radius: 12px;
    padding: 11px 14px;
    font-size: 13.5px; font-weight: 600; color: #1e1b4b;
    height: 46px;
    transition: border-color .2s, box-shadow .2s;
}
.pf-input:focus {
    border-color: #7c3aed;
    box-shadow: 0 0 0 3px rgba(124,58,237,0.12);
    outline: none;
    background: #fff;
}
.pf-input-icon-wrap { position: relative; }
.pf-input-icon-wrap .pf-input { padding-left: 42px; }
.pf-input-icon {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    color: #7c3aed; font-size: 18px; pointer-events: none;
}

/* Doc duo */
.pf-doc-wrap { display: flex; }
.pf-select-prefix {
    background: #f3f0ff;
    border: 1.5px solid #ede9fe;
    border-right: none;
    border-radius: 12px 0 0 12px;
    padding: 0 12px;
    font-size: 13px; font-weight: 700; color: #7c3aed;
    height: 46px;
    transition: border-color .2s;
}
.pf-select-prefix:focus { border-color: #7c3aed; outline: none; }
.pf-doc-wrap .pf-input { border-radius: 0 12px 12px 0; }

/* Divider */
.pf-section-divider {
    border: none; border-top: 1px solid #f0ebff;
    margin: 8px 0;
}

/* Save button */
.pf-save-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 28px;
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: #fff; font-size: 14px; font-weight: 700;
    border-radius: 14px; border: none; cursor: pointer;
    box-shadow: 0 6px 20px -6px rgba(109,40,217,0.45);
    transition: transform .2s, box-shadow .2s;
}
.pf-save-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px -6px rgba(109,40,217,0.52);
}
.pf-save-btn:active { transform: scale(.97); }
</style>
@endpush

@section('content')

<div class="profile-layout">

    {{-- ── Sidebar ──────────────────────────────────────── --}}
    <aside class="profile-sidebar">
        <div class="profile-sidebar-card">

            {{-- Avatar + Nombre --}}
            <div style="display:flex; align-items:center; gap:14px; margin-bottom:4px;">
                <div class="profile-avatar">
                    {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}
                </div>
                <div>
                    <div class="profile-user-name">{{ auth()->user()->nombre }} {{ auth()->user()->apellido }}</div>
                    <span class="profile-user-role">Cliente</span>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="profile-nav">
                <a href="{{ route('profile.index') }}"
                   class="profile-nav-link {{ request()->routeIs('profile.index') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">person</span>
                    Información Personal
                </a>
                <a href="{{ route('profile.orders') }}"
                   class="profile-nav-link {{ request()->routeIs('profile.orders') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">shopping_bag</span>
                    Mis Pedidos
                </a>
                <a href="{{ route('wishlist.index') }}"
                   class="profile-nav-link {{ request()->routeIs('wishlist.index') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">favorite</span>
                    Lista de Deseos
                </a>

                <hr class="profile-nav-divider">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="profile-nav-logout">
                        <span class="material-symbols-outlined">logout</span>
                        Cerrar Sesión
                    </button>
                </form>
            </nav>
        </div>
    </aside>

    {{-- ── Main Content ─────────────────────────────────── --}}
    <section class="profile-main">

        <h1 class="profile-page-title">Información Personal</h1>
        <p class="profile-page-sub">Gestiona los datos de tu cuenta.</p>

        @if(session('success'))
            <div style="margin-bottom:20px; display:flex; align-items:center; gap:10px; background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; font-size:13px; padding:12px 16px; border-radius:14px;">
                <span class="material-symbols-outlined" style="font-size:18px; color:#22c55e;">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="profile-form-card">
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                @csrf @method('PATCH')

                {{-- Nombre + Apellido --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="pf-field">
                        <label class="pf-label">Nombre</label>
                        <input name="nombre" type="text" value="{{ old('nombre', auth()->user()->nombre) }}"
                               class="pf-input" required/>
                        @error('nombre')<p style="font-size:11px; color:#ef4444; margin-top:2px;">{{ $message }}</p>@enderror
                    </div>
                    <div class="pf-field">
                        <label class="pf-label">Apellido</label>
                        <input name="apellido" type="text" value="{{ old('apellido', auth()->user()->apellido) }}"
                               class="pf-input" required/>
                        @error('apellido')<p style="font-size:11px; color:#ef4444; margin-top:2px;">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Email --}}
                <div class="pf-field">
                    <label class="pf-label">Correo Electrónico</label>
                    <div class="pf-input-icon-wrap">
                        <span class="material-symbols-outlined pf-input-icon">mail</span>
                        <input name="email" type="email" value="{{ old('email', auth()->user()->email) }}"
                               class="pf-input" required/>
                    </div>
                    @error('email')<p style="font-size:11px; color:#ef4444; margin-top:2px;">{{ $message }}</p>@enderror
                </div>

                {{-- Documento + Teléfono --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="pf-field">
                        <label class="pf-label">Documento de Identidad <span style="color:#ef4444;">*</span></label>
                        <div class="pf-doc-wrap">
                            <select name="tipo_documento" class="pf-select-prefix">
                                <option value="V" {{ old('tipo_documento', auth()->user()->tipo_documento) == 'V' ? 'selected' : '' }}>V</option>
                                <option value="E" {{ old('tipo_documento', auth()->user()->tipo_documento) == 'E' ? 'selected' : '' }}>E</option>
                                <option value="J" {{ old('tipo_documento', auth()->user()->tipo_documento) == 'J' ? 'selected' : '' }}>J</option>
                                <option value="G" {{ old('tipo_documento', auth()->user()->tipo_documento) == 'G' ? 'selected' : '' }}>G</option>
                            </select>
                            <input name="documento_identidad" type="text"
                                   value="{{ old('documento_identidad', auth()->user()->documento_identidad) }}"
                                   class="pf-input" required/>
                        </div>
                        @error('documento_identidad')<p style="font-size:11px; color:#ef4444; margin-top:2px;">{{ $message }}</p>@enderror
                    </div>
                    <div class="pf-field">
                        <label class="pf-label">Teléfono <span style="font-size:10px; font-weight:500; color:#9ca3af; text-transform:none;">(opcional)</span></label>
                        <div class="pf-input-icon-wrap">
                            <span class="material-symbols-outlined pf-input-icon">phone</span>
                            <input name="telefono" type="tel"
                                   value="{{ old('telefono', auth()->user()->telefono) }}"
                                   class="pf-input"/>
                        </div>
                    </div>
                </div>

                <hr class="pf-section-divider">

                {{-- Contraseña --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="pf-field">
                        <label class="pf-label">Nueva Contraseña</label>
                        <div class="pf-input-icon-wrap">
                            <span class="material-symbols-outlined pf-input-icon">lock</span>
                            <input name="password" type="password" placeholder="Mínimo 6 caracteres" class="pf-input"/>
                        </div>
                        <p style="font-size:10.5px; color:#9ca3af;">Déjalo en blanco para no cambiarla.</p>
                        @error('password')<p style="font-size:11px; color:#ef4444;">{{ $message }}</p>@enderror
                    </div>
                    <div class="pf-field">
                        <label class="pf-label">Confirmar Contraseña</label>
                        <div class="pf-input-icon-wrap">
                            <span class="material-symbols-outlined pf-input-icon">lock_reset</span>
                            <input name="password_confirmation" type="password" placeholder="Repite la contraseña" class="pf-input"/>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div style="padding-top:18px; border-top:1px solid #f0ebff; display:flex; justify-content:flex-end;">
                    <button type="submit" class="pf-save-btn">
                        <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                        Guardar Cambios
                    </button>
                </div>

            </form>
        </div>
    </section>

</div>

@endsection
