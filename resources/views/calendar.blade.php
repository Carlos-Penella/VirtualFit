{{-- Usamos el layout principal de la aplicación --}}
@extends('layouts.app')

{{-- Título de la página (puede ir al <title> del layout) --}}
@section('title','Calendario de clases')

@section('content')
    {{-- Encabezado de la sección --}}
    <h2>Calendario de clases</h2>

    {{-- Mensaje flash de estado (inscripción, baja, etc.) --}}
    @if(session('status'))
        <p class="alert">{{ session('status') }}</p>
    @endif

    {{-- Navegación entre semanas --}}
    <div class="calendar-nav">
        {{-- Botón semana anterior: pasa como start el lunes anterior --}}
        <a href="{{ route('calendar', ['start' => date('Y-m-d', strtotime($monday . ' -7 days'))]) }}" class="btn">Semana anterior</a>

        {{-- Texto que muestra el rango de la semana actual --}}
        <span>Semana del {{ \Carbon\Carbon::parse($monday)->format('d/m') }} al {{ \Carbon\Carbon::parse($sunday)->format('d/m') }}</span>

        {{-- Botón semana siguiente: pasa como start el lunes siguiente --}}
        <a href="{{ route('calendar', ['start' => date('Y-m-d', strtotime($monday . ' +7 days'))]) }}" class="btn">Semana siguiente</a>
    </div>

    {{-- Grid con los 7 días de la semana --}}
    <div class="calendar-grid">
        @for($i = 0; $i < 7; $i++)
            @php
                // Calculamos la fecha concreta sumando $i días al lunes
                $dia = date('Y-m-d', strtotime($monday . " +$i days"));
                // Obtenemos la colección de clases para ese día, o colección vacía
                $lista = $clasesPorDia[$dia] ?? collect();
            @endphp

            <div class="calendar-day">
                {{-- Título del día, en español, tipo “lunes 25” --}}
                <h3>{{ \Carbon\Carbon::parse($dia)->locale('es')->isoFormat('dddd D') }}</h3>

                {{-- Si hay usuario logueado y es ENTRENADOR o ADMIN, puede añadir clases --}}
                @if(auth()->check() && in_array(auth()->user()->tipo_usuario, ['ENTRENADOR','ADMIN']))
                    <p>
                        <a href="{{ route('classes.create', ['date' => $dia]) }}" class="btn">Añadir clase</a>
                    </p>
                @endif

                {{-- Listado de clases de ese día --}}
                @forelse($lista as $clase)
                    @php
                        // Plazas restantes para esta clase (método del modelo Clase)
                        $plazas = $clase->plazasRestantes();
                        // Comprobamos si el usuario autenticado está inscrito en esta clase
                        $inscrito = auth()->check() && $clase->usuarios->contains(auth()->user()->id ?? null);
                    @endphp

                    {{-- Tarjeta de la clase, con estilos según plazas --}}
                    <div class="calendar-class {{ $plazas <= 0 ? 'full' : ($plazas <= 3 ? 'few' : '') }}">
                        {{-- Nombre de la clase --}}
                        <strong>{{ $clase->nombre }}</strong>

                        {{-- Insignia si el usuario está apuntado --}}
                        @if($inscrito)
                            <span class="badge">Apuntado</span>
                        @endif

                        {{-- Horario de la clase --}}
                        <p>{{ $clase->hora_inicio }} - {{ $clase->hora_fin }}</p>

                        {{-- Plazas restantes --}}
                        <p>Plazas restantes: {{ $plazas }}</p>

                        {{-- Nombre del entrenador asignado a la clase, si existe --}}
                        @if($clase->entrenador)
                            <p>Entrenador: {{ $clase->entrenador->nombre ?? '' }}</p>
                        @endif

                        {{-- Acciones solo para usuarios autenticados --}}
                        @auth
                            {{-- Formulario para inscribirse a la clase --}}
                            <form method="post" action="{{ route('calendar.inscribirse', $clase) }}">
                                @csrf
                                {{-- Botón se deshabilita si no hay plazas --}}
                                <button type="submit" class="btn" {{ $plazas <= 0 ? 'disabled' : '' }}>Inscribirme</button>
                            </form>

                            {{-- Acciones extra para ENTRENADOR o ADMIN: editar / eliminar clase --}}
                            @if(in_array(auth()->user()->tipo_usuario, ['ENTRENADOR','ADMIN']))
                                <p style="margin-top:.25rem;">
                                    <a href="{{ route('classes.edit', $clase) }}" class="btn" style="background:#666;">Editar</a>
                                </p>

                                {{-- Formulario para eliminar la clase (con confirmación JS) --}}
                                <form method="post" action="{{ route('classes.destroy', $clase) }}" onsubmit="return confirm('¿Seguro que quieres eliminar esta clase?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="background:#c0392b;margin-top:.25rem;">Eliminar</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                @empty
                    {{-- Si no hay clases ese día, mostramos un mensaje --}}
                    <p class="muted">No hay clases este día.</p>
                @endforelse
            </div>
        @endfor
    </div>

    {{-- Sección inferior: tarjetas con los entrenadores --}}
    @if(!empty($trainers))
        <h2 style="margin-top:2rem;">Nuestros entrenadores</h2>

        <div class="cards" style="margin-top:.5rem;">
            @foreach($trainers as $t)
                <div class="card">
                    {{-- Foto del entrenador si tiene ruta de imagen --}}
                    @if(!empty($t['photo']))
                        <div class="exercise-video" style="margin-bottom:.6rem;">
                            {{-- asset() convierte la ruta de BD (p.ej. storage/trainers/...) en URL pública --}}
                            <img src="{{ asset($t['photo']) }}" alt="Entrenador {{ $t['name'] }}" style="width:100%;height:100%;object-fit:cover;border-radius:14px;">
                        </div>
                    @endif

                    {{-- Nombre del entrenador --}}
                    <h3>{{ $t['name'] }}</h3>

                    {{-- Texto de especialidad (aquí fijo) --}}
                    <p class="muted">Especialidad: {{ $t['specialty'] }}</p>

                    {{-- Bio o info adicional (en tu caso, el correo) --}}
                    <p>{{ $t['bio'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    @endif
@endsection