@extends('layouts.app')

@section('title','Clases')

@section('content')
    <h2>Clases</h2>
    <div class="cards">
        @foreach($classes as $c)
            <div class="card">
                @if(!empty($c['image']))
                    <div class="exercise-video" style="margin-bottom:.6rem;">
                        <img src="{{ $c['image'] }}" alt="Clase {{ $c['title'] }}" style="width:100%;height:100%;object-fit:cover;border-radius:14px;">
                    </div>
                @endif
                <h3>{{ $c['title'] }}</h3>
                <p class="muted">Horario: {{ $c['time'] }}</p>
                <p>{{ $c['desc'] }}</p>
            </div>
        @endforeach
    </div>

    @if(!empty($trainers))
        <h2 style="margin-top:2rem;">Nuestros entrenadores</h2>
        <div class="cards" style="margin-top:.5rem;">
            @foreach($trainers as $t)
                <div class="card">
                    @if(!empty($t['photo']))
                        <div class="exercise-video" style="margin-bottom:.6rem;">
                            <img src="{{ $t['photo'] }}" alt="Entrenador {{ $t['name'] }}" style="width:100%;height:100%;object-fit:cover;border-radius:14px;">
                        </div>
                    @endif
                    <h3>{{ $t['name'] }}</h3>
                    <p class="muted">Especialidad: {{ $t['specialty'] }}</p>
                    <p>{{ $t['bio'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    @endif
@endsection
