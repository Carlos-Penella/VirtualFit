@extends('layouts.app')

@section('title','Registrarse')

@section('content')
    <div class="card" style="max-width:480px;margin:3rem auto;">
        <h2 style="margin-top:0;color:var(--brand-primary);display:flex;align-items:center;gap:.5rem;">
            <span style="display:inline-flex;width:32px;height:32px;border-radius:50%;align-items:center;justify-content:center;background:rgba(44,122,123,0.1);font-size:1.2rem;">✨</span>
            Crear cuenta
        </h2>
        <p class="muted" style="margin-top:0;">Únete a VirtualFit y empieza tu transformación</p>

        @if($errors->any())
            <div class="auth-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="post" action="{{ route('register.post') }}" class="auth-form" style="margin-top:1.5rem;">
            @csrf
            <label>
                Nombre completo
                <input type="text" name="nombre" value="{{ old('nombre') }}" required placeholder="Tu nombre">
            </label>
            <label>
                Correo electrónico
                <input type="email" name="correo" value="{{ old('correo') }}" required placeholder="tu@email.com">
            </label>
            <label>
                Contraseña
                <input type="password" name="contraseña" required placeholder="Mínimo 8 caracteres">
            </label>
            <label>
                Confirmar contraseña
                <input type="password" name="contraseña_confirmation" required placeholder="Repite tu contraseña">
            </label>
            <button class="btn" type="submit" style="width:100%;margin-top:.75rem;">Crear cuenta</button>
        </form>
        
        <div class="auth-links">
            <p>¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
        </div>
    </div>
@endsection
