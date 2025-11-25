<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ejercicio;
use App\Models\Clase;
use App\Models\RegistroEjercicio;
use App\Models\Racha;
use App\Models\Recompensa;
use App\Models\Canjeo;
use App\Models\VideoEjercicioDiario;

class GymController extends Controller
{
    public function home()
    {
        // Página de inicio pública: no forzamos login aquí.
        $user = auth()->user();

        // Ejercicios diarios (mismo criterio que en exercises())
        $all = Ejercicio::all();
        $count = 5;
        $date = date('Y-m-d');
        $dayHash = crc32($date);

        $dailyExercises = $all->sortBy(function ($item) use ($dayHash) {
            return crc32($item->id . $dayHash);
        })->take($count)->values();

        $rewards = [5, 10, 20];
        $dailyExercises = $dailyExercises->map(function ($e) use ($date, $rewards) {
            $hash = crc32($e->id . $date);
            $reward = $rewards[$hash % count($rewards)];
            $e->recompensa = $reward;
            return $e;
        });

        // Tres próximas clases ordenadas por fecha y hora de inicio
        $today = date('Y-m-d');
        $nowTime = date('H:i:s');

        $upcomingClasses = Clase::where(function ($q) use ($today, $nowTime) {
                $q->where('fecha', '>', $today)
                  ->orWhere(function ($q2) use ($today, $nowTime) {
                      $q2->where('fecha', $today)
                         ->where('hora_inicio', '>=', $nowTime);
                  });
            })
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->take(3)
            ->get();

        return view('home', [
            'dailyExercises' => $dailyExercises,
            'upcomingClasses' => $upcomingClasses,
            'user' => $user,
        ]);
    }

    public function classes()
    {
        $classes = [
            [
                'title' => 'Yoga',
                'time' => '08:00',
                'desc' => 'Vinyasa flow para todos los niveles.',
                'image' => asset('images/clases/yoga.jpg'),
            ],
            [
                'title' => 'Crossfit',
                'time' => '10:00',
                'desc' => 'Entrenamiento funcional e intenso.',
                'image' => asset('images/clases/crossfit.jpg'),
            ],
            [
                'title' => 'Spinning',
                'time' => '18:00',
                'desc' => 'Clase de ciclismo indoor para quemar calorías.',
                'image' => asset('images/clases/spinning.jpg'),
            ],
        ];

        $trainers = \App\Models\Usuario::where('tipo_usuario', 'ENTRENADOR')
            ->get()
            ->map(function ($u) {
                return [
                    'name' => $u->nombre,
                    'specialty' => 'Entrenador VirtualFit',
                    'bio' => $u->correo,
                    'photo' => $u->foto_entrenador ? asset($u->foto_entrenador) : null,
                ];
            });

        return view('classes', compact('classes', 'trainers'));
    }

    public function trainers()
    {
        $trainers = \App\Models\Usuario::where('tipo_usuario', 'ENTRENADOR')
            ->get()
            ->map(function ($u) {
                return [
                    'name' => $u->nombre,
                    'specialty' => 'Entrenador VirtualFit',
                    'bio' => $u->correo,
                    'photo' => $u->foto_entrenador ? asset($u->foto_entrenador) : null,
                ];
            });

        return view('trainers', compact('trainers'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function about()
    {
        return view('about');
    }

    public function exercises()
    {
        // Ejercicios diarios: se seleccionan según el día
        $user = auth()->user();
        $all = \App\Models\Ejercicio::all();

        // Selección pseudoaleatoria diaria sin shuffle con seed
        $count = 5;
        $dayHash = crc32(date('Y-m-d'));
        $exercises = $all->sortBy(function($item) use ($dayHash) {
            // Ordena por hash del id y el día
            return crc32($item->id . $dayHash);
        })->take($count)->values();

        // Asignar recompensa aleatoria (5, 10, 20) de forma determinista por día y ejercicio
        $rewards = [5, 10, 20];
        $date = date('Y-m-d');
        $exercises = $exercises->map(function($e, $i) use ($date, $rewards) {
            // Determinista: hash del id+fecha para elegir recompensa
            $hash = crc32($e->id . $date);
            $reward = $rewards[$hash % count($rewards)];
            $e->recompensa = $reward;
            return $e;
        });

        return view('exercises_daily', [
            'exercises' => $exercises,
            'user' => $user,
            'date' => $date
        ]);
    }

    public function completeDailyExercises(Request $request)
    {
        $user = auth()->user();
        $date = date('Y-m-d');

        // Si ya tiene registros para hoy, no duplicar
        $yaHecho = RegistroEjercicio::where('usuario_id', $user->id)
            ->where('fecha', $date)
            ->exists();

        if ($yaHecho) {
            return redirect()->route('exercises')->with('status', 'Ya has completado la rutina de hoy.');
        }

        // Obtener los mismos ejercicios diarios que se muestran
        $all = Ejercicio::all();
        $count = 5;
        $dayHash = crc32($date);

        $exercises = $all->sortBy(function ($item) use ($dayHash) {
            return crc32($item->id . $dayHash);
        })->take($count)->values();

        $rewards = [5, 10, 20];
        $totalFitcoinsBase = 0;

        foreach ($exercises as $e) {
            $hash = crc32($e->id . $date);
            $reward = $rewards[$hash % count($rewards)];
            $totalFitcoinsBase += $reward;

            RegistroEjercicio::create([
                'usuario_id' => $user->id,
                'ejercicio_id' => $e->id,
                'fecha' => $date,
                'valor' => null,
                'fitcoins' => $reward,
            ]);
        }

        // Aplicar multiplicador para usuarios Premium / Premium Fit
        $multiplicador = in_array($user->tipo_usuario, ['premium', 'premiumFit'], true) ? 1.3 : 1.0;
        $totalFitcoins = (int) round($totalFitcoinsBase * $multiplicador);

        // Actualizar racha del usuario
        $racha = Racha::firstOrCreate(
            ['usuario_id' => $user->id],
            ['dias_consecutivos' => 0, 'ultima_actividad' => null, 'fitcoins_ganados' => 0]
        );

        if ($racha->ultima_actividad === $date) {
            // Ya contada hoy, solo sumar fitcoins
            $racha->fitcoins_ganados += $totalFitcoins;
        } else {
            $ayer = date('Y-m-d', strtotime($date . ' -1 day'));
            if ($racha->ultima_actividad === $ayer) {
                $racha->dias_consecutivos += 1;
            } else {
                $racha->dias_consecutivos = 1;
            }
            $racha->ultima_actividad = $date;
            $racha->fitcoins_ganados += $totalFitcoins;
        }

        $racha->save();

        return redirect()->route('exercises')->with('status', 'Has completado la rutina de hoy y ganado ' . $totalFitcoins . ' Fitcoins (multiplicador ' . $multiplicador . 'x aplicado).');
    }

    public function subirVideoEjercicioDiario(Request $request)
    {
        $user = auth()->user();
        $date = date('Y-m-d');

        $data = $request->validate([
            'ejercicio_id' => 'required|exists:ejercicios,id',
            'video' => 'required|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv|max:51200', // hasta ~50MB
            'comentario' => 'nullable|string|max:255',
        ]);

        // Verificar que el ejercicio pertenece al conjunto diario de hoy
        $all = Ejercicio::all();
        $count = 5;
        $dayHash = crc32($date);

        $exercisesToday = $all->sortBy(function ($item) use ($dayHash) {
            return crc32($item->id . $dayHash);
        })->take($count)->pluck('id')->values();

        if (! $exercisesToday->contains((int) $data['ejercicio_id'])) {
            return back()->with('status', 'Solo puedes subir vídeos para los ejercicios diarios de hoy.');
        }

        $path = $request->file('video')->store('videos_ejercicios', 'public');

        VideoEjercicioDiario::updateOrCreate(
            [
                'usuario_id' => $user->id,
                'ejercicio_id' => $data['ejercicio_id'],
                'fecha' => $date,
            ],
            [
                'ruta_video' => $path,
                'comentario' => $data['comentario'] ?? null,
            ]
        );

        return back()->with('status', 'Vídeo subido para este ejercicio diario.');
    }

    public function recompensas()
    {
        $user = auth()->user();
        $racha = optional($user->racha);
        $fitcoinsTotales = $racha->fitcoins_ganados ?? 0;

        $recompensas = Recompensa::all();

        return view('recompensas', [
            'recompensas' => $recompensas,
            'fitcoinsTotales' => $fitcoinsTotales,
        ]);
    }

    public function canjearRecompensa(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'recompensa_id' => 'required|exists:recompensas,id',
        ]);

        $recompensa = Recompensa::findOrFail($data['recompensa_id']);
        $racha = Racha::firstOrCreate(
            ['usuario_id' => $user->id],
            ['dias_consecutivos' => 0, 'ultima_actividad' => null, 'fitcoins_ganados' => 0]
        );

        if ($racha->fitcoins_ganados < $recompensa->costo_fitcoins) {
            return back()->with('status', 'No tienes suficientes Fitcoins para esta recompensa.');
        }

        // Descontar Fitcoins y registrar el canje
        $racha->fitcoins_ganados -= $recompensa->costo_fitcoins;
        $racha->save();

        Canjeo::create([
            'usuario_id' => $user->id,
            'recompensa_id' => $recompensa->id,
            'fecha' => now(),
        ]);

        return back()->with('status', 'Has canjeado "' . $recompensa->nombre . '" por ' . $recompensa->costo_fitcoins . ' Fitcoins.');
    }
}
