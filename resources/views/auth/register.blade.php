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
