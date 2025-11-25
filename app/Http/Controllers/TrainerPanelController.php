<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatMensaje;
use App\Models\Usuario;
use App\Models\Membresia;
use App\Models\PasswordResetRequest;
use App\Models\VideoEjercicioDiario;

class TrainerPanelController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (! $user || ! in_array($user->tipo_usuario, ['ENTRENADOR', 'ADMIN'], true)) {
            abort(403);
        }

        $query = ChatMensaje::with(['usuario', 'entrenador'])
            ->where('modo', 'trainer');

        if ($user->tipo_usuario === 'ENTRENADOR') {
            $query->where(function ($q) use ($user) {
                $q->where('entrenador_id', $user->id)
                    ->orWhereNull('entrenador_id');
            });
        }

        $mensajes = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Lista de "chats" (usuarios únicos que han escrito a este entrenador)
        $chats = collect();
        $chatsRecientes = collect();
        if ($user->tipo_usuario === 'ENTRENADOR') {
            $baseChats = ChatMensaje::with('usuario')
                ->where('modo', 'trainer')
                ->where(function ($q) use ($user) {
                    $q->where('entrenador_id', $user->id)
                        ->orWhereNull('entrenador_id');
                })
                ->whereNotNull('usuario_id')
                ->orderBy('created_at', 'desc')
                ->get();

            // Lista completa en la izquierda
            $chats = $baseChats->pluck('usuario')->filter()->unique('id');

            // Chats recientes: agrupar por usuario y quedarnos con el último mensaje
            $chatsRecientes = $baseChats
                ->groupBy('usuario_id')
                ->map(function ($grupo) {
                    return $grupo->first(); // ya viene ordenado desc
                })
                ->sortByDesc('created_at');
        }

        return view('trainer_panel', [
            'mensajes' => $mensajes,
            'chats' => $chats,
            'chatsRecientes' => $chatsRecientes,
        ]);
    }

    public function videosEjerciciosDiarios()
    {
        $user = Auth::user();
        if (! $user || ! in_array($user->tipo_usuario, ['ENTRENADOR', 'ADMIN'], true)) {
            abort(403);
        }

        $query = VideoEjercicioDiario::with(['usuario', 'ejercicio']);
        if ($user->tipo_usuario === 'ENTRENADOR') {
            $query->whereHas('usuario', function ($q) use ($user) {
                $q->where('entrenador_id', $user->id);
            });
        }

        $videos = $query
            ->orderByDesc('fecha')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('trainer_videos_diarios', compact('videos'));
    }

    public function chatUsuario(Usuario $usuario)
    {
        $user = Auth::user();
        if (! $user || $user->tipo_usuario !== 'ENTRENADOR') {
            abort(403);
        }

        $mensajes = ChatMensaje::where('modo', 'trainer')
            ->where('usuario_id', $usuario->id)
            ->where(function ($q) use ($user) {
                $q->where('entrenador_id', $user->id)
                    ->orWhereNull('entrenador_id');
            })
            ->orderBy('created_at')
            ->get();

        return view('trainer_chat_usuario', [
            'usuario' => $usuario,
            'mensajes' => $mensajes,
        ]);
    }

    public function responder(Request $request, ChatMensaje $mensaje)
    {
        $user = Auth::user();
        if (! $user || $user->tipo_usuario !== 'ENTRENADOR') {
            abort(403);
        }

        // Solo permitir responder si el mensaje va dirigido a este entrenador o es general
        if ($mensaje->entrenador_id && $mensaje->entrenador_id !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'respuesta_entrenador' => ['required', 'string'],
        ]);

        $mensaje->respuesta_entrenador = $data['respuesta_entrenador'];
        $mensaje->entrenador_id = $mensaje->entrenador_id ?: $user->id; // si era general, asignar este entrenador
        $mensaje->save();

        return redirect()->route('trainer.panel')->with('status', 'Respuesta enviada correctamente. El usuario la verá en su chat.');
    }

    public function adminUsuarios()
    {
        $user = Auth::user();
        if (! $user || $user->tipo_usuario !== 'ADMIN') {
            abort(403);
        }

        $usuarios = Usuario::with(['membresias' => function ($q) {
                $q->orderByDesc('fecha_fin');
            }])
            ->orderBy('nombre')
            ->get();

        $resetRequests = PasswordResetRequest::with('usuario')
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin_usuarios', [
            'usuarios' => $usuarios,
            'resetRequests' => $resetRequests,
        ]);
    }

    public function cancelarSuscripcion(Usuario $usuario)
    {
        $admin = Auth::user();
        if (! $admin || $admin->tipo_usuario !== 'ADMIN') {
            abort(403);
        }

        // Desactivar membresías activas
        Membresia::where('usuario_id', $usuario->id)
            ->where('activo', 1)
            ->update(['activo' => 0]);

        // Devolver al plan gratuito
        $usuario->tipo_usuario = 'freemium';
        $usuario->save();

        return redirect()->route('admin.usuarios')
            ->with('status', 'Suscripción cancelada para el usuario: ' . $usuario->correo);
    }

    public function cambiarPassword(Request $request, Usuario $usuario)
    {
        $admin = Auth::user();
        if (! $admin || $admin->tipo_usuario !== 'ADMIN') {
            abort(403);
        }

        $data = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $usuario->password = bcrypt($data['password']);
        $usuario->save();

        PasswordResetRequest::where('usuario_id', $usuario->id)
            ->where('estado', 'pendiente')
            ->update(['estado' => 'resuelto']);

        return redirect()->route('admin.usuarios')
            ->with('status', 'Contraseña actualizada para el usuario: ' . $usuario->correo);
    }
}
