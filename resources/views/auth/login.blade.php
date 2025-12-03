@extends('layouts.app')

@section('title','Iniciar sesión')

@section('content')
    <div class="auth-layout">
        <div class="auth-hero">
            <h2>Vuelve a tu entrenamiento</h2>
            <p>Inicia sesión para seguir sumando rachas, Fitcoins y clases.</p>
        </div>

        <div class="auth-card card">
            <h2 style="margin-top:0;display:flex;align-items:center;gap:.5rem;">
                <span style="display:inline-flex;width:28px;height:28px;border-radius:999px;align-items:center;justify-content:center;background:var(--accent-soft);color:var(--accent);">🔒</span>
                Iniciar sesión
            </h2>

            @if($errors->any())
                <div class="auth-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="post" action="{{ route('login.post') }}" class="auth-form">
                @csrf
                <label>Correo
                    <input type="email" name="correo" value="{{ old('correo') }}" required placeholder="tu@email.com">
                </label>
                <label>Contraseña
                    <input type="password" name="password" required placeholder="••••••••">
                </label>
                <button class="btn" type="submit" style="width:100%;margin-top:.5rem;">Entrar</button>
            </form>

            <form method="post" action="{{ route('password.request-reset') }}" class="auth-form" style="margin-top:1rem;">
                @csrf
                <input type="hidden" name="correo" value="{{ old('correo') }}">
                <button class="btn" type="submit" style="width:100%;background:#262b3f;box-shadow:none;">No recuerdo mi contraseña</button>
            </form>

            <div class="auth-links">
                <p>¿No tienes cuenta? <a href="{{ route('register') }}">Crear cuenta</a></p>
                <p>¿Eres entrenador? <a href="{{ route('login.trainer') }}">Acceso entrenadores</a></p>
            </div>
        </div>
    </div>
@endsection
