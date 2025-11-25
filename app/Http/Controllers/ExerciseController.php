<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ejercicio;

class ExerciseController extends Controller
{
    public function index(Request $request)
    {
        // Filtros opcionales por dificultad (nivel) y grupo de músculos
        $nivel = $request->query('nivel');
        $musculo = $request->query('musculo');

        $query = Ejercicio::query();

        if ($nivel) {
            $query->where('dificultad', $nivel);
        }

        if ($musculo) {
            $query->where('grupo_musculos', $musculo);
        }

        $exercises = $query->get();

        // Valores únicos para poblar los selects de filtro
        $niveles = Ejercicio::select('dificultad')
            ->whereNotNull('dificultad')
            ->distinct()
            ->orderBy('dificultad')
            ->pluck('dificultad');

        $musculos = Ejercicio::select('grupo_musculos')
            ->whereNotNull('grupo_musculos')
            ->distinct()
            ->orderBy('grupo_musculos')
            ->pluck('grupo_musculos');

        // Si no se ha filtrado por nivel, agrupar solo por grupo muscular
        if (!$nivel) {
            $grouped = $exercises->groupBy(function ($exercise) {
                return $exercise->grupo_musculos ?? 'General';
            })->mapWithKeys(function ($items, $musculo) {
                return [
                    $musculo => [
                        'nivel' => null,
                        'musculo' => $musculo,
                        'ejercicios' => $items,
                    ],
                ];
            });
        } else {
            // Agrupar por dificultad (nivel) y grupo de músculos
            $grouped = $exercises->groupBy(function ($exercise) {
                $nivel = $exercise->dificultad ?? 'Sin nivel';
                $musculo = $exercise->grupo_musculos ?? 'General';
                return $nivel . '|' . $musculo;
            })->mapWithKeys(function ($items, $key) {
                [$nivel, $musculo] = explode('|', $key);
                return [
                    $key => [
                        'nivel' => $nivel,
                        'musculo' => $musculo,
                        'ejercicios' => $items,
                    ],
                ];
            });
        }

        return view('exercises', [
            'groups' => $grouped,
            'niveles' => $niveles,
            'musculos' => $musculos,
            'filtroNivel' => $nivel,
            'filtroMusculo' => $musculo,
        ]);
    }
}
