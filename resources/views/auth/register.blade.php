@extends('layouts.app')

@section('title','Registrarse')

@section('content')
    <div class="card" style="max-width:420px;margin:2rem auto;">
        <h2>Registro de usuario</h2>

        @if($errors->any())
            <div class="card" style="background:#fee;border-color:#fdd;color:#600;padding:.6rem;margin-bottom:1rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="post" action="{{ route('register.post') }}">
            @csrf
            <label>Nombre
                <input type="text" name="nombre" value="{{ old('nombre') }}" required>
            </label>
            <label>Email
                <input type="email" name="correo" value="{{ old('correo') }}" required>
            </label>
            <label>Tipo de usuario
                <select name="tipo_usuario" required>
                    <option value="freemium" {{ old('tipo_usuario') == 'freemium' ? 'selected' : '' }}>Freemium</option>
                    <option value="premium" {{ old('tipo_usuario') == 'premium' ? 'selected' : '' }}>Premium</option>
                    <option value="premiumFit" {{ old('tipo_usuario') == 'premiumFit' ? 'selected' : '' }}>Premium Fit</option>
                    <option value="ENTRENADOR" {{ old('tipo_usuario') == 'ENTRENADOR' ? 'selected' : '' }}>Entrenador</option>
                    <option value="ADMIN" {{ old('tipo_usuario') == 'ADMIN' ? 'selected' : '' }}>Admin</option>
                </select>
            </label>
            <label>Contraseña
                <input type="password" name="contraseña" required>
            </label>
            <label>Confirmar contraseña
                <input type="password" name="contraseña_confirmation" required>
            </label>
            <button class="btn" type="submit">Registrarse</button>
        </form>
    </div>
    <p style="text-align:center;color:var(--muted);">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
@endsection
