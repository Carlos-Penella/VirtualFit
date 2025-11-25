@extends('layouts.app')

@section('title','Inicio')

@section('content')
    <section class="hero">
        <h2>Bienvenido a VirtualFit</h2>
        <p>Tu gimnasio online para entrenar cuando quieras y donde quieras, con seguimiento y recompensas.</p>
        <p>
            <a class="btn" href="{{ route('exercises') }}">Ir a mis ejercicios diarios</a>
            <a class="btn" href="{{ route('classes') }}" style="margin-left:.5rem;">Ver clases</a>
            @if(auth()->check() && auth()->user()->tipo_usuario === 'ENTRENADOR')
                <a class="btn" href="{{ route('trainer.panel') }}" style="margin-left:.5rem;">Ver mensajes de usuarios</a>
            @endif
        </p>
    </section>

    <section class="grid">
        <div class="card">
            <h3>Ejercicios diarios de hoy</h3>
            @if($dailyExercises->isEmpty())
                <p class="muted">No hay ejercicios configurados todavía.</p>
            @else
                @auth
                    @php
                        $racha = optional(auth()->user()->racha);
                    @endphp
                    <p class="muted">
                        Racha actual: {{ $racha->dias_consecutivos ?? 0 }} días ·
                        Fitcoins totales: {{ $racha->fitcoins_ganados ?? 0 }}
                    </p>
                @endauth
                <ul>
                    @foreach($dailyExercises as $e)
                        <li>
                            <strong>{{ $e->nombre }}</strong>
                            <div class="muted">{{ $e->descripcion }}</div>
                            <div>Recompensa: {{ $e->recompensa }} Fitcoins</div>
                        </li>
                    @endforeach
                </ul>
                <p><a href="{{ route('exercises') }}">Ver rutina diaria completa</a></p>
            @endif
        </div>

        <div class="card">
            <h3>Próximas clases</h3>
            @if($upcomingClasses->isEmpty())
                <p class="muted">No hay clases programadas próximamente.</p>
            @else
                <ul>
                    @foreach($upcomingClasses as $clase)
                        <li>
                            <strong>{{ $clase->nombre }}</strong>
                            @if(auth()->check() && $clase->usuarios->contains(auth()->user()->id ?? null))
                                <span class="badge">Apuntado</span>
                            @endif
                            <div>
                                {{ \Carbon\Carbon::parse($clase->fecha)->format('d/m') }}
                                — {{ $clase->hora_inicio }} - {{ $clase->hora_fin }}
                            </div>
                            @if($clase->descripcion)
                                <div class="muted">{{ $clase->descripcion }}</div>
                            @endif
                            @php $plazas = $clase->plazasRestantes(); @endphp
                            <div>Plazas restantes: {{ $plazas }}</div>
                            @auth
                                <form method="post" action="{{ route('calendar.inscribirse', $clase) }}">
                                    @csrf
                                    <button type="submit" class="btn" {{ $plazas <= 0 ? 'disabled' : '' }}>Inscribirme</button>
                                </form>
                            @endauth
                        </li>
                    @endforeach
                </ul>
                <p><a href="{{ route('calendar') }}">Ver calendario completo</a></p>
            @endif
        </div>
    </section>

    <section class="cards" style="margin-top:1.8rem;">
        <div class="card">
            <h3>Por qué VirtualFit</h3>
            <p>
                VirtualFit acerca la experiencia de un gimnasio completo a tu casa.
                Combina clases en directo, entrenamientos grabados, ejercicios diarios
                personalizados y un sistema de Fitcoins para que cada sesión cuente.
            </p>
            <div class="grid" style="margin-top:1rem;">
                <div>
                    <h4>Entrenamientos a tu medida</h4>
                    <p class="muted">Planes adaptados a tu nivel y objetivos, con ejercicios explicados en vídeo.</p>
                </div>
                <div>
                    <h4>Clases en directo y bajo demanda</h4>
                    <p class="muted">Reserva tu plaza en el calendario o repite tus clases favoritas cuando quieras.</p>
                </div>
                <div>
                    <h4>Seguimiento y rachas</h4>
                    <p class="muted">Registra tus entrenos, mantén tu racha activa y gana Fitcoins por tu constancia.</p>
                </div>
                <div>
                    <h4>Ventajas Premium</h4>
                    <p class="muted">Acceso a contenidos exclusivos, prioridad en plazas y soporte prioritario.</p>
                </div>
            </div>
        </div>

        <div class="card">
            <h3>Comunidad VirtualFit</h3>
            <p class="muted">Opiniones reales de usuarios que entrenan con nosotros.</p>
            <ul>
                <li>
                    <strong>Ana · Plan Premium</strong>
                    <div class="muted">“Gracias al calendario y a los ejercicios diarios llevo 3 meses sin fallar a mis entrenos.”</div>
                </li>
                <li>
                    <strong>Carlos · Plan Freemium</strong>
                    <div class="muted">“Puedo entrenar en casa con material básico y seguir progresando.”</div>
                </li>
                <li>
                    <strong>Lucía · Premium Fit</strong>
                    <div class="muted">“Las clases en directo y las recompensas con Fitcoins me mantienen motivada.”</div>
                </li>
            </ul>
        </div>

        <div class="card">
            <h3>Cómo funciona</h3>
            <ol>
                <li><strong>Crea tu cuenta</strong> y completa tu perfil con tus objetivos.</li>
                <li><strong>Reserva clases</strong> desde el calendario y asegúrate tu plaza.</li>
                <li><strong>Sigue los ejercicios diarios</strong> recomendados para mantener tu racha.</li>
                <li><strong>Gana Fitcoins</strong> por entrenar de forma constante.</li>
                <li><strong>Canjea tus Fitcoins</strong> por recompensas dentro de la plataforma.</li>
            </ol>
        </div>
    </section>
@endsection
