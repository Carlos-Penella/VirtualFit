@extends('layouts.app')

@section('title','Entrenadores')

@section('content')
    <h2>Entrenadores</h2>
    <div class="cards">
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
@endsection
