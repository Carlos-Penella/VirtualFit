<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Clase;

class ClassManagementController extends Controller
{
    protected function ensureTrainerOrAdmin()
    {
        $user = Auth::user();

if (! $user || ! in_array($user->tipo_usuario, ['ENTRENADOR', 'ADMIN'], true)) {            abort(403);
        }
        return $user;
    }

    public function create(Request $request)
    {
        $this->ensureTrainerOrAdmin();

        $date = $request->query('date');

        return view('class_form', [
            'clase' => new Clase(),
            'date' => $date,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureTrainerOrAdmin();

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'aforo_max' => 'nullable|integer|min:1',
        ]);

        // Evitar solapamiento exacto: ya existe una clase en misma fecha y hora_inicio
        $exists = Clase::where('fecha', $data['fecha'])
            ->where('hora_inicio', $data['hora_inicio'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['hora_inicio' => 'Ya existe una clase en esa fecha y hora de inicio.']);
        }

        // Todas las clases duran 1h: calculamos hora_fin a partir de hora_inicio
        $data['hora_fin'] = date('H:i:s', strtotime($data['hora_inicio'] . ' +1 hour'));

        Clase::create($data);

        return redirect()->route('calendar', ['start' => $data['fecha']])
            ->with('status', 'Clase creada correctamente.');
    }

    public function edit(Clase $clase)
    {
        $this->ensureTrainerOrAdmin();

        return view('class_form', [
            'clase' => $clase,
            'date' => $clase->fecha,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Clase $clase)
    {
        $this->ensureTrainerOrAdmin();

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'aforo_max' => 'nullable|integer|min:1',
        ]);

        // Evitar solapamiento al editar: otra clase distinta con misma fecha y hora_inicio
        $exists = Clase::where('fecha', $data['fecha'])
            ->where('hora_inicio', $data['hora_inicio'])
            ->where('id', '!=', $clase->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['hora_inicio' => 'Ya existe otra clase en esa fecha y hora de inicio.']);
        }

        // Recalcular hora_fin al actualizar la hora de inicio
        $data['hora_fin'] = date('H:i:s', strtotime($data['hora_inicio'] . ' +1 hour'));

        $clase->update($data);

        return redirect()->route('calendar', ['start' => $data['fecha']])
            ->with('status', 'Clase actualizada correctamente.');
    }

    public function destroy(Clase $clase)
    {
        $this->ensureTrainerOrAdmin();

        $fecha = $clase->fecha;

        // Eliminar primero todas las inscripciones relacionadas para evitar error de clave foránea
        $clase->usuarios()->detach();

        $clase->delete();

        return redirect()->route('calendar', ['start' => $fecha])
            ->with('status', 'Clase eliminada correctamente.');
    }
}
