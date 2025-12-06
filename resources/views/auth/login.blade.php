@extends('layouts.app')

@section('title','Iniciar sesión')

@section('content')
    <div class="auth-layout">
        <div class="auth-hero">
            <h2>Vuelve a tu entrenamiento</h2>
            <p>Inicia sesión para seguir sumando rachas, Fitcoins y clases.</p>
        </div>

        <div class="auth-card card">
            <h2 style="margin-top:0;display:flex;align-items:center;gap:.5rem;color:var(--brand-primary);">
                <span style="display:inline-flex;width:32px;height:32px;border-radius:50%;align-items:center;justify-content:center;background:rgba(44,122,123,0.1);font-size:1.2rem;">🔒</span>
                Iniciar sesión
            </h2>

            @if($errors->any())
                <div class="auth-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="post" action="{{ route('login.post') }}" class="auth-form" style="margin-top:1.5rem;">
                @csrf
                <label>
                    Correo electrónico
                    <input type="email" name="correo" value="{{ old('correo') }}" required placeholder="tu@email.com">
                </label>
                <label>
                    Contraseña
                    <input type="password" name="password" required placeholder="••••••••">
                </label>
                <button class="btn" type="submit" style="width:100%;margin-top:.75rem;">Entrar</button>
            </form>

            <form method="post" action="{{ route('password.request-reset') }}" style="margin-top:1rem;">
                @csrf
                <input type="hidden" name="correo" value="{{ old('correo') }}">
                <button class="btn-secondary" type="submit" style="width:100%;padding:.65rem;">¿Olvidaste tu contraseña?</button>
            </form>

            <div class="auth-links">
                <p>¿No tienes cuenta? <a href="{{ route('register') }}">Crear cuenta</a></p>
                <p>¿Eres entrenador? <a href="{{ route('login.trainer') }}">Acceso entrenadores</a></p>
            </div>
        </div>
    </div>
@endsection
