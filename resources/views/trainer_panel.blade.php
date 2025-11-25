@extends('layouts.app')

@section('title','Consultas a entrenadores')

@section('content')
    <h2>Consultas a entrenadores</h2>

    <p class="muted">Listado de mensajes enviados en modo Entrenador por usuarios con plan Premium/PremiumFit. Puedes responder a cada consulta y la respuesta se mostrará en el chat del usuario.</p>

    @if($mensajes->isEmpty())
        <p>No hay mensajes pendientes.</p>
    @else
        <div class="grid" style="align-items:flex-start;grid-template-columns: minmax(0,0.9fr) minmax(0,2fr);gap:1.5rem;">
            <div class="card">
                <h3>Chats con usuarios</h3>
                <p class="muted">Selecciona un usuario para ver la conversación completa.</p>
                @if(isset($chats) && $chats->isNotEmpty())
                    <ul class="list-unstyled">
                        @foreach($chats as $u)
                            <li style="margin-bottom:.5rem;">
                                <a href="{{ route('trainer.chat.usuario', $u->id) }}" class="user-menu-link">
                                    <strong>{{ $u->nombre }}</strong>
                                    <div class="muted" style="font-size:.85rem;">{{ $u->correo }}</div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="muted">Todavía no tienes mensajes de usuarios.</p>
                @endif
            </div>

            <div class="card">
                <h3>Chats recientes</h3>
                <p class="muted">Usuarios que te han escrito más recientemente.</p>
                @if(isset($chatsRecientes) && $chatsRecientes->isNotEmpty())
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Último mensaje</th>
                                <th>Hace</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chatsRecientes as $m)
                                <tr>
                                    <td>
                                        <a href="{{ route('trainer.chat.usuario', $m->usuario_id) }}" class="user-menu-link">
                                            {{ $m->usuario->nombre ?? 'Usuario #'.$m->usuario_id }}
                                        </a>
                                    </td>
                                    <td>{{ \Illuminate\Support\Str::limit($m->mensaje, 60) }}</td>
                                    <td>{{ $m->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="muted">Todavía no hay chats recientes.</p>
                @endif
            </div>
        </div>
        @endif
