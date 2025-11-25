@extends('layouts.app')

@section('title','Contacto')

@section('content')
    <h2>Contacto</h2>
    <p>Usa el formulario para escribirnos (este es un ejemplo estático).</p>

    <form class="contact-form" method="post" action="#">
        @csrf
        <label>Nombre
            <input type="text" name="name" required>
        </label>
        <label>Email
            <input type="email" name="email" required>
        </label>
        <label>Mensaje
            <textarea name="message" rows="5" required></textarea>
        </label>
        <button class="btn" type="submit">Enviar</button>
    </form>
@endsection
