<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatMensaje;

class ChatController extends Controller
{
    public function reply(Request $request)
    {
        $message = trim((string) $request->input('message', ''));
        $mode = $request->input('mode', 'assistant');

        if ($message === '') {
            return response()->json([
                'reply' => 'No he recibido ningún mensaje. ¿En qué puedo ayudarte?',
            ]);
        }

        $lower = mb_strtolower($message, 'UTF-8');

        $user = Auth::user();
        $userType = $user->tipo_usuario ?? null;

        if ($mode === 'trainer' && $user && in_array($userType, ['PREMIUM','PREMIUMFIT'], true)) {
            $trainerId = $user->entrenador_id ?? null;
            ChatMensaje::create([
                'usuario_id' => $user->id,
                'modo' => 'trainer',
                'mensaje' => $message,
                'entrenador_id' => $trainerId,
            ]);
        }

        $reply = $this->generateReply($lower, $userType, $mode);

        return response()->json([
            'reply' => $reply,
        ]);
    }

    public function conversation(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            abort(401);
        }

        $mode = $request->query('mode', 'assistant');

        $query = ChatMensaje::query()
            ->where('usuario_id', $user->id)
            ->where('modo', $mode);

        if ($mode === 'trainer') {
            $trainerId = $user->entrenador_id ?? null;
            if ($trainerId) {
                $query->where('entrenador_id', $trainerId);
            } else {
                $query->whereNull('entrenador_id');
            }
        }

        $mensajes = $query
            ->orderBy('created_at')
            ->get(['id','mensaje','respuesta_entrenador','created_at']);

        return response()->json([
            'mensajes' => $mensajes,
        ]);
    }

    protected function generateReply(string $msg, ?string $userType, string $mode): string
    {
		$isPremium = in_array($userType, ['PREMIUM', 'PREMIUMFIT'], true);

        // Si el usuario intenta usar modo entrenador sin ser premium, bloquear
        if ($mode === 'trainer' && ! $isPremium) {
            return 'El modo Entrenador solo está disponible para usuarios Premium o Premium_Fit.';
        }

        // Preguntas sobre hablar con entrenadores
        if (str_contains($msg, 'entrenador') || str_contains($msg, 'entrenadores') || str_contains($msg, 'coach')) {
            if (! $isPremium) {
                return 'El chat directo con entrenadores está disponible solo para usuarios Premium o Premium_Fit. Puedes hacerte premium desde tu área de cuenta.';
            }

            return 'Como usuario premium, puedes usar este canal para dejar tus dudas sobre entrenamientos. Un entrenador las revisará y te responderá en tu próxima sesión o por el canal que tengáis acordado.';
        }

        // Preguntas frecuentes simples
        if (str_contains($msg, 'horario') || str_contains($msg, 'abierto')) {
            return 'VirtualFit está disponible 24/7 online. Puedes entrenar en cualquier momento y reservar clases según el calendario.';
        }

        if (str_contains($msg, 'premium')) {
            return 'Los usuarios premium tienen prioridad en las últimas plazas de cada clase, acceso a contenidos exclusivos y soporte prioritario.';
        }

        if (str_contains($msg, 'clase') || str_contains($msg, 'calendario')) {
            return 'Puedes ver y reservar clases desde el calendario en la sección "Calendario" del menú principal.';
        }

        if (str_contains($msg, 'fitcoin') || str_contains($msg, 'moneda')) {
            return 'Ganas Fitcoins completando ejercicios diarios, manteniendo tu racha de entrenamiento y participando en clases. Luego puedes canjearlos por recompensas.';
        }

        if (str_contains($msg, 'ejercicio') || str_contains($msg, 'rutina')) {
            return 'En la sección "Ejercicios" puedes filtrar por nivel y grupo muscular y ver vídeos explicativos de cada ejercicio.';
        }

        return 'Soy un asistente básico de VirtualFit. Puedo ayudarte con información sobre horarios, clases, cuenta premium, ejercicios y Fitcoins. Prueba a preguntarme, por ejemplo: "¿Qué ofrece la cuenta premium?" o "¿Cómo reservo una clase?"';
    }
}
