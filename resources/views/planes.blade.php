@extends('layouts.app')

@section('title','Planes y precios')

@section('content')
    <h2>Planes VirtualFit</h2>
    <p class="muted">Elige el plan que mejor se adapta a tu forma de entrenar.</p>

    <div class="cards cards-3" style="margin-top:1rem;">
        <div class="card">
            <h3>Freemium</h3>
            <p><strong>0 €/mes</strong></p>
            <ul>
                <li>Acceso a la web y ejercicios diarios básicos.</li>
                <li>Participación en un número limitado de clases a la semana.</li>
                <li>Acumula Fitcoins por completar rutinas.</li>
                <li>Sin chat con entrenadores.</li>
            </ul>
        </div>

        <div class="card" style="border:2px solid var(--accent);">
            <h3>Premium</h3>
            <p><strong>19,99 €/mes</strong></p>
            <ul>
                <li>Todo lo de Freemium.</li>
                <li>Más clases semanales y prioridad en plazas.</li>
                <li>Acceso al chat con entrenadores.</li>
                <li>Más Fitcoins por actividad y retos especiales.</li>
            </ul>
        </div>

        <div class="card">
            <h3>Premium Fit</h3>
            <p><strong>29,99 €/mes</strong></p>
            <ul>
                <li>Todo lo de Premium.</li>
                <li>Sesiones personalizadas con entrenadores.</li>
                <li>Planes de entrenamiento avanzados y seguimiento.</li>
                <li>Recompensas extra y ventajas exclusivas.</li>
            </ul>
        </div>
    </div>
@endsection
