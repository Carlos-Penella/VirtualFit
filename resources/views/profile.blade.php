@extends('layouts.app')

@section('title','Mi perfil')

@section('content')
    <div class="card" style="max-width:600px;margin:2rem auto;">
        <h2>Mi perfil</h2>
        <p><strong>Nombre:</strong> {{ auth()->user()->nombre }}</p>
        <p><strong>Correo:</strong> {{ auth()->user()->correo }}</p>
        <p><strong>Tipo de usuario:</strong> {{ auth()->user()->tipo_usuario }}</p>
        <p><strong>Fecha de registro:</strong> {{ auth()->user()->fecha_registro }}</p>

        @if(auth()->user()->tipo_usuario === 'ENTRENADOR')
            <hr>
            <h3>Foto de entrenador</h3>
            @if(auth()->user()->foto_entrenador)
                <p class="muted">Vista previa actual:</p>
                <div class="exercise-video" style="max-width:260px;margin-bottom:1rem;">
                    <img src="{{ asset(auth()->user()->foto_entrenador) }}" alt="Foto de entrenador" style="width:100%;height:100%;object-fit:cover;border-radius:14px;">
                </div>
            @endif

            <form method="post" action="{{ route('profile.foto-entrenador') }}" enctype="multipart/form-data" class="contact-form">
                @csrf
                <label>Subir nueva foto
                    <input type="file" name="foto_entrenador" accept="image/*" required>
                </label>
                <button type="submit" class="btn">Actualizar foto</button>
            </form>
        @endif
    </div>
@endsection
