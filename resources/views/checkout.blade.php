@extends('layouts.app')

@section('title','Checkout')

@section('content')
    <section class="hero">
        <h2>Confirmar plan: {{ $plan['name'] }}</h2>
        <p class="muted">Precio: {{ $plan['price'] }}</p>
        <p>{{ $plan['description'] }}</p>
    </section>

    <section class="grid" style="margin-top:1.8rem;">
        <div class="card">
            <h3>Datos de pago (ejemplo)</h3>
            <p class="muted">Este formulario simula un pago. Aquí deberás integrar la pasarela real.</p>

            <form method="post" action="{{ route('checkout.process') }}" class="contact-form">
                @csrf
                <input type="hidden" name="plan" value="{{ $planKey }}">

                <label>Nombre del titular
                    <input type="text" name="card_name" required>
                </label>
                <label>Número de tarjeta (ficticio)
                    <input type="text" name="card_number" maxlength="19" placeholder="1111 2222 3333 4444" required>
                </label>
                <label>Fecha de caducidad
                    <input type="text" name="card_exp" maxlength="5" placeholder="MM/AA" required>
                </label>
                <label>CVV
                    <input type="password" name="card_cvv" maxlength="4" required>
                </label>

                <button type="submit" class="btn">Pagar y activar plan</button>
            </form>

            <p class="muted" style="margin-top:.8rem;">
                Nota: en un entorno real aquí se usaría Stripe, PayPal u otra pasarela
                para procesar el cargo. Después del pago, se actualizaría igualmente tu plan.
            </p>
        </div>

        <div class="card">
            <h3>Resumen del plan</h3>
            <ul>
                <li><strong>Plan:</strong> {{ $plan['name'] }}</li>
                <li><strong>Precio:</strong> {{ $plan['price'] }}</li>
                <li><strong>Ventajas:</strong> {{ $plan['description'] }}</li>
            </ul>
            <p class="muted">Tu tipo de usuario se actualizará automáticamente al completar el pago simulado.</p>
        </div>
    </section>
@endsection
