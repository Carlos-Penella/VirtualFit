@extends('layouts.app')

@section('title','Todos los ejercicios')

@section('content')
    <h2>Todos los ejercicios</h2>

    <form method="get" action="{{ route('exercises.all') }}" class="filter-form">
        <label>
            Nivel:
            <select name="nivel">
                <option value="">Todos</option>
                @foreach($niveles as $nivel)
                    <option value="{{ $nivel }}" {{ ($filtroNivel ?? '') === $nivel ? 'selected' : '' }}>
                        {{ $nivel }}
                    </option>
                @endforeach
            </select>
        </label>

        <label>
            Grupo muscular:
            <select name="musculo">
                <option value="">Todos</option>
                @foreach($musculos as $m)
                    <option value="{{ $m }}" {{ ($filtroMusculo ?? '') === $m ? 'selected' : '' }}>
                        {{ $m }}
                    </option>
                @endforeach
            </select>
        </label>

        <button type="submit" class="btn">Filtrar</button>
    </form>

    @forelse($groups as $group)
        <section class="exercise-group">
            <h3>
                @if(!empty($group['nivel']))
                    Nivel: {{ $group['nivel'] }} ·
                @endif
                Músculo: {{ $group['musculo'] }}
            </h3>
            <div class="cards cards-3">
                @foreach($group['ejercicios'] as $exercise)
                    <div class="card">
                        @if(!empty($exercise->video_url))
                            <div class="exercise-video">
                                <iframe src="{{ $exercise->video_url }}" frameborder="0" allowfullscreen></iframe>
                            </div>
                        @endif

                        <h4>{{ $exercise->nombre }}</h4>

                        @if(!empty($exercise->tipo))
                            <p class="muted">Tipo: {{ $exercise->tipo }}</p>
                        @endif

                        @if(!empty($exercise->descripcion))
                            <p>{{ $exercise->descripcion }}</p>
                        @endif

                        <ul class="exercise-meta">
                            @if(!empty($exercise->dificultad))
                                <li><strong>Dificultad:</strong> {{ $exercise->dificultad }}</li>
                            @endif
                            @if(!empty($exercise->grupo_musculos))
                                <li><strong>Grupo de músculos:</strong> {{ $exercise->grupo_musculos }}</li>
                            @endif
                            @if(!empty($exercise->equipamiento))
                                <li><strong>Equipamiento:</strong> {{ $exercise->equipamiento }}</li>
                            @endif
                            @if(!empty($exercise->duracion))
                                <li><strong>Duración:</strong> {{ $exercise->duracion }}</li>
                            @endif
                            @if(!empty($exercise->calorias))
                                <li><strong>Calorías aprox.:</strong> {{ $exercise->calorias }}</li>
                            @endif
                        </ul>
                    </div>
                @endforeach
            </div>
        </section>
    @empty
        <p>No hay ejercicios registrados.</p>
    @endforelse
@endsection
