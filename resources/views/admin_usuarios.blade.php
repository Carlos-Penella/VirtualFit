@extends('layouts.app')

@section('title','Panel de usuarios')

@section('content')
    <h2>Panel de usuarios</h2>

    @if(session('status'))
        <p class="alert">{{ session('status') }}</p>
    @endif

    @if(!empty($resetRequests) && $resetRequests->count())
        <div class="card" style="margin-bottom:1.5rem;">
            <h3>Solicitudes de cambio de contraseña</h3>
            <p class="muted">Los siguientes usuarios han pedido que se les cambie la contraseña.</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Fecha solicitud</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resetRequests as $req)
                        <tr>
                            <td>{{ $req->usuario->nombre }}</td>
                            <td>{{ $req->usuario->correo }}</td>
                            <td>{{ $req->created_at ? $req->created_at->format('d/m/Y H:i') : '' }}</td>
                            <td>
                                <details>
                                    <summary class="btn" style="display:inline-block;cursor:pointer;">Cambiar contraseña</summary>
                                    <form method="post" action="{{ route('admin.usuarios.password', $req->usuario) }}" style="margin-top:.5rem;">
                                        @csrf
                                        <label>Nueva contraseña
                                            <input type="password" name="password" required>
                                        </label>
                                        <label>Confirmar contraseña
                                            <input type="password" name="password_confirmation" required>
                                        </label>
                                        <button type="submit" class="btn">Guardar</button>
                                    </form>
                                </details>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="card">
        <p class="muted">Solo usuarios con rol ADMIN pueden ver y gestionar esta información.</p>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Plan actual</th>
                        <th>Fecha fin plan</th>
                        <th>Estado membresía</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $u)
                        @php
                            $ultimaMembresia = $u->membresias->first();
                            $fechaFin = $ultimaMembresia->fecha_fin ?? null;
                            $activo = $ultimaMembresia->activo ?? 0;
                        @endphp
                        <tr>
                            <td>{{ $u->nombre }}</td>
                            <td>{{ $u->correo }}</td>
                            <td>{{ $u->tipo_usuario }}</td>
                            <td>{{ $fechaFin ? \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') : 'Sin plan' }}</td>
                            <td>
                                @if($activo)
                                    <span class="badge">Activa</span>
                                @else
                                    <span class="muted">Inactiva</span>
                                @endif
                            </td>
                            <td>
                                @if($activo && $u->id !== auth()->id())
                                    <form method="post" action="{{ route('admin.usuarios.cancelar', $u) }}" onsubmit="return confirm('¿Cancelar la suscripción de este usuario?');">
                                        @csrf
                                        <button type="submit" class="btn" style="background:#c0392b;">Cancelar suscripción</button>
                                    </form>
                                @else
                                    <span class="muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
