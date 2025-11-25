@extends('layouts.app')

@section('title','Vídeos ejercicios diarios')

@section('content')
    <h2>Vídeos de ejercicios diarios</h2>
    <p class="muted">Revisar la técnica de los usuarios en los ejercicios diarios.</p>

    @if(session('status'))
        <div class="card" style="margin-bottom:1rem;">
            {{ session('status') }}
        </div>
    @endif

    @if($videos->isEmpty())
        <div class="card">Todavía no hay vídeos subidos por los usuarios.</div>
    @else
        <div class="cards" style="margin-top:1rem;">
            @foreach($videos as $video)
                <div class="card">
                    <h3>{{ $video->ejercicio->nombre ?? 'Ejercicio' }}</h3>
                    <p class="muted">
                        Usuario: {{ $video->usuario->nombre ?? 'Desconocido' }} ·
                        Fecha: {{ \Carbon\Carbon::parse($video->fecha)->format('d/m/Y') }}
                    </p>
                    @if($video->comentario)
                        <p class="muted">Comentario del usuario: "{{ $video->comentario }}"</p>
                    @endif
                    <video controls style="width:100%;max-height:260px;margin-top:.5rem;border-radius:12px;">
                        <source src="{{ asset('storage/'.$video->ruta_video) }}" type="video/mp4">
                        Tu navegador no soporta la reproducción de vídeo.
                    </video>
                </div>
            @endforeach
        </div>

        <div style="margin-top:1rem;">
            {{ $videos->links() }}
        </div>
    @endif
@endsection
