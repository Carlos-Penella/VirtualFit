@extends('layouts.app')

@section('title','Recompensas')

@section('content')
    <h2>Recompensas</h2>

    @if(session('status'))
        <p class="alert">{{ session('status') }}</p>
    @endif

    <div class="card" style="max-width:500px;margin-bottom:1.5rem;">
        <p><strong>Fitcoins disponibles:</strong> {{ $fitcoinsTotales }}</p>
        <p class="muted">Gana Fitcoins completando tu rutina diaria y asistiendo a clases.</p>
    </div>

    @if(isset($canjeos) && $canjeos->isNotEmpty())
        <div class="card" style="max-width:700px;margin-bottom:1.5rem;">
            <h3>Mis recompensas canjeadas</h3>
            <ul>
                @foreach($canjeos as $c)
                    <li>
                        <strong>{{ $c->recompensa->nombre ?? 'Recompensa' }}</strong>
                        &mdash;
                        {{ $c->fecha ? \Carbon\Carbon::parse($c->fecha)->format('d/m/Y H:i') : '' }}
                        @if(!empty($c->recompensa) && !is_null($c->recompensa->costo_fitcoins))
                            <span class="muted">(Coste: {{ $c->recompensa->costo_fitcoins }} Fitcoins)</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($recompensas->isEmpty())
        <p class="muted">Todavía no hay recompensas configuradas.</p>
    @else
        <div class="cards">
            @foreach($recompensas as $r)
                <div class="card">
                    <h3>{{ $r->nombre }}</h3>
                    <p class="muted">Coste: {{ $r->costo_fitcoins }} Fitcoins</p>
                    <p>{{ $r->descripcion }}</p>
                    <form method="post" action="{{ route('recompensas.canjear') }}" onsubmit="return confirm('¿Canjear esta recompensa?');">
                        @csrf
                        <input type="hidden" name="recompensa_id" value="{{ $r->id }}">
                        <button type="submit" class="btn" @if($fitcoinsTotales < $r->costo_fitcoins) disabled @endif>
                            Canjear
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
@endsection
