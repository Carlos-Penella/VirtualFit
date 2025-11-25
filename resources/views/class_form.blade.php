@extends('layouts.app')

@section('title', $mode === 'edit' ? 'Editar clase' : 'Nueva clase')

@section('content')
    <h2>{{ $mode === 'edit' ? 'Editar clase' : 'Crear nueva clase' }}</h2>

    <form method="post" action="{{ $mode === 'edit' ? route('classes.update', $clase) : route('classes.store') }}">
        @csrf
        @if($mode === 'edit')
            @method('PUT')
        @endif

        <label>Nombre de la clase
            <input type="text" name="nombre" value="{{ old('nombre', $clase->nombre) }}" required>
        </label>

        <label>Descripción
            <textarea name="descripcion">{{ old('descripcion', $clase->descripcion) }}</textarea>
        </label>

        <label>Fecha
            <input type="date" name="fecha" value="{{ old('fecha', $clase->fecha ?? $date) }}" required>
        </label>

        <label>Hora de inicio
            <input type="time" name="hora_inicio" value="{{ old('hora_inicio', $clase->hora_inicio) }}" required>
        </label>

        <label>Aforo máximo
            <input type="number" name="aforo_max" min="1" value="{{ old('aforo_max', $clase->aforo_max ?? 20) }}">
        </label>

        <button type="submit" class="btn">Guardar</button>
    </form>

    @if($mode === 'edit')
        <hr style="margin:2rem 0;">
        <h3>Usuarios inscritos</h3>

        @php $inscritos = $clase->usuarios; @endphp

        @if($inscritos->isEmpty())
            <p class="muted">No hay usuarios inscritos en esta clase.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Tipo de suscripción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inscritos as $usuario)
                        <tr>
                            <td>{{ $usuario->nombre }}</td>
                            <td>{{ $usuario->correo }}</td>
                            <td>{{ $usuario->tipo_usuario }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endif
@endsection
