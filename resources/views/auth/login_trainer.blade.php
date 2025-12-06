@extends('layouts.app')

@section('title','Login Entrenador')

@section('content')
    <div class="card" style="max-width:480px;margin:3rem auto;">
        <h2 style="margin-top:0;color:var(--brand-primary);display:flex;align-items:center;gap:.5rem;">
            <span style="display:inline-flex;width:32px;height:32px;border-radius:50%;align-items:center;justify-content:center;background:rgba(44,122,123,0.1);font-size:1.2rem;">💪</span>
            Acceso entrenadores
        </h2>
        <p class="muted" style="margin-top:0;">Panel exclusivo para entrenadores de VirtualFit</p>

        @if($errors->any())
            <div class="auth-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="post" action="{{ route('login.trainer.post') }}" class="auth-form" style="margin-top:1.5rem;">
            @csrf
            <label>
                Correo de entrenador
                <input type="email" name="correo" value="{{ old('correo') }}" required placeholder="entrenador@virtualfit.com">
            </label>
            <label>
                Contraseña
                <input type="password" name="password" required placeholder="••••••••">
            </label>
            <button class="btn" type="submit" style="width:100%;margin-top:.75rem;">Entrar como entrenador</button>
        </form>

        <div style="margin-top:1.5rem;padding:1rem;background:#f7fafc;border-radius:8px;border-left:3px solid var(--brand-primary);">
            <p class="muted" style="margin:0;font-size:.9rem;">
                <strong>Acceso restringido:</strong> Este panel es solo para entrenadores registrados por la administración de VirtualFit.
            </p>
        </div>
        
        <div class="auth-links">
            <p><a href="{{ route('login') }}">← Volver al login de usuarios</a></p>
        </div>
    </div>
@endsection
