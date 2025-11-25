@extends('layouts.app')

@section('title','Mis suscripciones')

@section('content')
    <h2>Mis suscripciones</h2>

    @php
        $user = auth()->user();
        $tipo = $user->tipo_usuario;

        $descripciones = [
            'freemium' => 'Plan gratuito con acceso básico y número limitado de clases.',
            'premium' => 'Más clases, prioridad en plazas y acceso a entrenadores.',
            'premiumFit' => 'Plan completo con seguimiento avanzado y beneficios extra.',
            'ENTRENADOR' => 'Cuenta de entrenador con acceso a gestión de clases y consultas.',
            'ADMIN' => 'Cuenta de administrador con control total de la plataforma.',
        ];
    @endphp

    <div class="card" style="max-width:600px;">
        <p><strong>Tu plan actual:</strong> {{ $tipo }}</p>
        <p>{{ $descripciones[$tipo] ?? 'Plan personalizado.' }}</p>
        <p class="muted">Para cambiar de plan, puedes ponerte en contacto con el administrador o usar el selector de tipo de usuario en el registro para nuevas cuentas.</p>
        <p><a href="{{ route('planes') }}" class="btn">Ver todos los planes</a></p>
    </div>
@endsection
