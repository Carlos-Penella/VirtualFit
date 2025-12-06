@extends('layouts.app')

@section('title','Chat con '.$usuario->nombre)

@section('content')
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
        <h2 style="margin:0;">Chat con {{ $usuario->nombre }}</h2>
        <a href="{{ route('trainer.panel') }}" class="btn-secondary">← Volver al panel</a>
    </div>

    <div class="card" style="max-height:520px;overflow-y:auto;display:flex;flex-direction:column;gap:.4rem;">
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
                        <form method="post" action="{{ route('trainer.responder', $m->id) }}" class="contact-form" style="align-self:flex-end;margin-top:.5rem;width:70%;">
                            @csrf
                            <label style="margin-bottom:0.5rem;">
                                <textarea name="respuesta_entrenador" rows="3" placeholder="Escribe tu respuesta..." required style="width:100%;resize:vertical;"></textarea>
                            </label>
                            <button type="submit" class="btn" style="float:right;">Enviar respuesta</button>
                        </form>
                    @endif
                @endif
            </div>
        @empty
            <p class="muted">Todavía no hay mensajes con este usuario.</p>
        @endforelse
    </div>
@endsection
