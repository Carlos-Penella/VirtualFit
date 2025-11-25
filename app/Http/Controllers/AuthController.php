<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Usuario;
use App\Models\PasswordResetRequest;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showLoginTrainer()
    {
        return view('auth.login_trainer');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(['correo' => $credentials['correo'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'correo' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('correo');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'correo' => ['required', 'email', 'max:150', 'unique:usuarios,correo'],
            'tipo_usuario' => ['required', 'in:freemium,premium,premiumFit,ENTRENADOR,ADMIN'],
            'contraseña' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $userData = [
            'nombre' => $data['nombre'],
            'correo' => $data['correo'],
            'password' => bcrypt($data['contraseña']),
            'tipo_usuario' => $data['tipo_usuario'],
            'fecha_registro' => now(),
        ];

        // Asignar entrenador automáticamente solo a usuarios no entrenadores/admin
        if (! in_array($data['tipo_usuario'], ['ENTRENADOR', 'ADMIN'], true)) {
            $entrenador = Usuario::where('tipo_usuario', 'ENTRENADOR')
                ->withCount('clientes')
                ->orderBy('clientes_count', 'asc')
                ->first();

            if ($entrenador) {
                $userData['entrenador_id'] = $entrenador->id;
            }
        }

        $user = Usuario::create($userData);
        Auth::login($user);
        return redirect()->route('exercises');
    }

    public function updateTrainerPhoto(Request $request)
    {
        /** @var Usuario $user */
        $user = Auth::user();

        if (! $user || $user->tipo_usuario !== 'ENTRENADOR') {
            return redirect()->route('profile')->with('status', 'Solo los entrenadores pueden actualizar su foto.');
        }

        $data = $request->validate([
            'foto_entrenador' => ['required', 'image', 'max:2048'], // ~2MB
        ]);

        $path = $request->file('foto_entrenador')->store('trainers', 'public');

        $user->foto_entrenador = 'storage/' . $path;
        $user->save();

        return redirect()->route('profile')->with('status', 'Foto de entrenador actualizada correctamente.');
    }

    public function requestPasswordReset(Request $request)
    {
        $data = $request->validate([
            'correo' => ['required', 'email'],
        ]);

        $usuario = Usuario::where('correo', $data['correo'])->first();
        if (! $usuario) {
            return back()->withErrors(['correo' => 'No existe ningún usuario con ese correo.']);
        }

        PasswordResetRequest::firstOrCreate([
            'usuario_id' => $usuario->id,
            'estado' => 'pendiente',
        ]);

        return back()->with('status', 'Hemos enviado una solicitud al administrador para cambiar tu contraseña.');
    }
}
