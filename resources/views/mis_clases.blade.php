@extends('layouts.app')

@section('title','Mis clases')

@section('content')
    <h2>Mis clases</h2>

    @if(session('status'))
        <p class="alert">{{ session('status') }}</p>
    @endif

    @if($clases->isEmpty())
        <p class="muted">Todavía no estás inscrito en ninguna clase.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Horario</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($clases as $clase)
                    @php
                        $inicio = \Carbon\Carbon::parse($clase->fecha.' '.$clase->hora_inicio);
                        $ahora = now();
                        $yaPasada = $inicio->isPast();
                    @endphp
                    <tr>
                        <td>{{ $clase->nombre }}</td>
                        <td>{{ $inicio->format('d/m/Y') }}</td>
                        <td>{{ $clase->hora_inicio }} - {{ $clase->hora_fin }}</td>
                        <td>{{ $yaPasada ? 'Realizada / pasada' : 'Próxima' }}</td>
                        <td>
                            @if(!$yaPasada)
                                <form method="post" action="{{ route('calendar.baja', $clase) }}" onsubmit="return confirm('¿Seguro que quieres darte de baja de esta clase?');">
                                    @csrf
                                    <button type="submit" class="btn" style="background:#c0392b;">Darme de baja</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
