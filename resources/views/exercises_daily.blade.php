@extends('layouts.app')

@section('title','Ejercicios diarios')

@section('content')
    <h2>Ejercicios diarios para {{ $user->nombre }}</h2>
    <p class="muted">Fecha: {{ $date }}</p>
    <div class="cards">
        @forelse($exercises as $e)
            <div class="card">
                <h3>{{ $e->nombre }}</h3>
                <p class="muted">Tipo: {{ $e->tipo }}</p>
                <p>{{ $e->descripcion }}</p>
                <p style="font-weight:bold;color:var(--accent);margin-top:1rem;">Recompensa: {{ $e->recompensa }} fitcoins</p>
                <hr style="border-color:rgba(255,255,255,.06);margin:1rem 0;">
                <form method="post" action="{{ route('exercises.daily.video') }}" enctype="multipart/form-data" class="contact-form">
                    @csrf
                    <input type="hidden" name="ejercicio_id" value="{{ $e->id }}">
                    <label>Sube tu vídeo realizando este ejercicio
                        <input type="file" name="video" accept="video/*" required>
                    </label>
                    <label>Comentario (opcional)
                        <input type="text" name="comentario" placeholder="Cómo te has sentido, peso usado, etc.">
                    </label>
                    <button type="submit" class="btn" style="margin-top:.4rem;">Subir vídeo</button>
                </form>
            </div>
        @empty
            <div class="card">No hay ejercicios registrados en la base de datos.</div>
        @endforelse
    </div>
    <form method="post" action="{{ route('exercises.complete') }}" style="margin-top:2rem;">
        @csrf
        <button type="submit" class="btn">Marcar rutina como completada y ganar Fitcoins</button>
    </form>
    <p style="margin-top:1rem;color:var(--muted);">Los ejercicios cambian cada 24 horas automáticamente. Solo puedes completar la rutina una vez por día.</p>
@endsection
