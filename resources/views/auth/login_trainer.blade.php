@extends('layouts.app')

@section('title','Login Entrenador')

@section('content')
    <div class="card" style="max-width:420px;margin:2rem auto;">
        <h2>Iniciar sesión como entrenador</h2>

        @if($errors->any())
            <div class="card" style="background:#fee;border-color:#fdd;color:#600;padding:.6rem;margin-bottom:1rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="post" action="{{ route('login.trainer.post') }}">
            @csrf
            <label>Correo de entrenador
                <input type="email" name="correo" value="{{ old('correo') }}" required>
            </label>
            <label>Contraseña
                <input type="password" name="password" required>
            </label>
            <button class="btn" type="submit">Entrar como entrenador</button>
        </form>

        <p class="muted" style="margin-top:1rem;font-size:.9rem;">
            Este acceso es solo para entrenadores registrados por la administración
            de VirtualFit.
        </p>
    </div>
@endsection
