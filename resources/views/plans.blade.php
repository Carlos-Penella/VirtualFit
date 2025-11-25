@extends('layouts.app')

@section('title','Planes')

@section('content')
    <section class="hero">
        <h2>Elige tu plan</h2>
        <p class="muted">Empieza gratis y mejora cuando quieras a un plan de pago.</p>
    </section>

    <section class="cards cards-3" style="margin-top:1.8rem;">
        <div class="card">
            <h3>Freemium</h3>
            <p class="muted">0 € / mes</p>
            <ul>
                <li>Acceso a ejercicios básicos.</li>
                <li>Algunas clases en abierto.</li>
                <li>Sistema de Fitcoins estándar.</li>
            </ul>
        </div>

        <div class="card">
            <h3>Plan Premium</h3>
            <p class="muted">9,99 € / mes</p>
            <ul>
                <li>Acceso a la mayoría de clases online.</li>
                <li>Prioridad en las últimas plazas.</li>
                <li>Multiplicador de Fitcoins 1.3x.</li>
            </ul>
            <p>
                <a href="{{ route('checkout.show','premium') }}" class="btn">Elegir Premium</a>
            </p>
        </div>

        <div class="card">
            <h3>Plan Premium Fit</h3>
            <p class="muted">14,99 € / mes</p>
            <ul>
                <li>Todo lo del Plan Premium.</li>
                <li>Contenidos exclusivos y retos especiales.</li>
                <li>Mayor foco en objetivos avanzados.</li>
            </ul>
            <p>
                <a href="{{ route('checkout.show','premiumFit') }}" class="btn">Elegir Premium Fit</a>
            </p>
        </div>
    </section>
@endsection
