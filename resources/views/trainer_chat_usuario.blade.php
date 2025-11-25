@extends('layouts.app')

@section('title','Chat con '.$usuario->nombre)

@section('content')
    <h2>Chat con {{ $usuario->nombre }}</h2>

    <p class="muted">Vista de conversación similar a WhatsApp: mensajes del usuario a la izquierda, respuestas del entrenador a la derecha.</p>

    <div class="card" style="max-height:480px;overflow-y:auto;display:flex;flex-direction:column;gap:.4rem;">
        @forelse($mensajes as $m)
            <div style="display:flex;flex-direction:column;align-items:flex-start;margin-bottom:.35rem;">
                <div class="chat-message bot" style="max-width:70%;align-self:flex-start;">
                    <strong>{{ $usuario->nombre }}:</strong>
                    <div>{{ $m->mensaje }}</div>
                    <div class="muted" style="font-size:.75rem;margin-top:.15rem;">{{ $m->created_at->format('d/m/Y H:i') }}</div>
                </div>

                @if($m->respuesta_entrenador)
                    <div class="chat-message user" style="max-width:70%;align-self:flex-end;margin-top:.15rem;">
                        <strong>Tú:</strong>
                        <div>{{ $m->respuesta_entrenador }}</div>
                        <div class="muted" style="font-size:.75rem;margin-top:.15rem;">{{ $m->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                @else
                    @if(auth()->user()->tipo_usuario === 'ENTRENADOR')
                        <form method="post" action="{{ route('trainer.responder', $m->id) }}" style="align-self:flex-end;margin-top:.25rem;width:70%;">
                            @csrf
                            <textarea name="respuesta_entrenador" rows="2" placeholder="Escribe tu respuesta" required style="width:100%;"></textarea>
                            <button type="submit" class="btn" style="margin-top:.25rem;float:right;">Enviar</button>
                        </form>
                    @endif
                @endif
            </div>
        @empty
            <p class="muted">Todavía no hay mensajes con este usuario.</p>
        @endforelse
    </div>
@endsection
