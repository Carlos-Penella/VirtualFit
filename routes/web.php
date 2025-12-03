<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GymController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ClassCalendarController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TrainerPanelController;
use App\Http\Controllers\ClassManagementController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [GymController::class, 'home'])->name('home');
// Auth routes (simple session-based login)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/password/request-reset', [AuthController::class, 'requestPasswordReset'])->name('password.request-reset');
Route::get('/login-trainer', [AuthController::class, 'showLoginTrainer'])->name('login.trainer');
Route::post('/login-trainer', [AuthController::class, 'login'])->name('login.trainer.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/classes', [GymController::class, 'classes'])->name('classes');
Route::get('/trainers', [GymController::class, 'trainers'])->name('trainers');
Route::get('/contact', [GymController::class, 'contact'])->name('contact');
Route::get('/about', [GymController::class, 'about'])->name('about');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Página de planes y suscripciones
Route::get('/planes', [PaymentController::class, 'plans'])->name('planes');

// Contenido informativo
Route::view('/faq', 'faq')->name('faq');
Route::view('/terminos', 'terminos')->name('terminos');
Route::view('/privacidad', 'privacidad')->name('privacidad');

// Perfil de usuario
Route::get('/profile', function () {
	$user = auth()->user()->load(['canjeos.recompensa']);
	return view('profile', compact('user'));
})->middleware('auth')->name('profile');

// Actualizar foto de entrenador (solo entrenadores)
Route::post('/profile/foto-entrenador', [AuthController::class, 'updateTrainerPhoto'])
	->middleware('auth')
	->name('profile.foto-entrenador');

// Exercises page - only for authenticated users
Route::get('/exercises', [GymController::class, 'exercises'])->name('exercises')->middleware('auth');
Route::post('/exercises/complete', [GymController::class, 'completeDailyExercises'])->name('exercises.complete')->middleware('auth');
Route::post('/exercises/daily/video', [GymController::class, 'subirVideoEjercicioDiario'])->name('exercises.daily.video')->middleware('auth');
Route::get('/exercises/all', [ExerciseController::class, 'index'])->name('exercises.all')->middleware('auth');

// Calendario de clases
Route::get('/calendar', [ClassCalendarController::class, 'index'])->name('calendar')->middleware('auth');
Route::post('/calendar/{clase}/inscribirse', [ClassCalendarController::class, 'inscribirse'])->name('calendar.inscribirse')->middleware('auth');

// Darse de baja de una clase
Route::post('/calendar/{clase}/baja', [ClassCalendarController::class, 'baja'])->name('calendar.baja')->middleware('auth');

// Chatbot básico
Route::post('/chat/message', [ChatController::class, 'reply'])->name('chat.message');

// Historial de conversación del chat (usuario autenticado)
Route::get('/chat/conversation', [ChatController::class, 'conversation'])
	->middleware('auth')
	->name('chat.conversation');

// Panel para entrenadores/admin (consultas de chat)
Route::get('/trainer/panel', [TrainerPanelController::class, 'index'])->name('trainer.panel');

// Vídeos de ejercicios diarios (entrenadores/admin)
Route::get('/trainer/videos-diarios', [TrainerPanelController::class, 'videosEjerciciosDiarios'])
	->middleware('auth')
	->name('trainer.videos.diarios');

Route::post('/trainer/videos-diarios/{video}/recompensar', [TrainerPanelController::class, 'recompensarVideo'])
	->middleware('auth')
	->name('trainer.videos.recompensar');

// Conversación con un usuario concreto (entrenador)
Route::get('/trainer/chat/{usuario}', [TrainerPanelController::class, 'chatUsuario'])
	->middleware('auth')
	->name('trainer.chat.usuario');

// Responder a mensaje de usuario (solo entrenadores)
Route::post('/trainer/mensajes/{mensaje}/respuesta', [TrainerPanelController::class, 'responder'])
	->middleware('auth')
	->name('trainer.responder');

// Panel de administración de usuarios (solo ADMIN)
Route::get('/admin/usuarios', [TrainerPanelController::class, 'adminUsuarios'])
	->middleware('auth')
	->name('admin.usuarios');
Route::post('/admin/usuarios/{usuario}/cancelar', [TrainerPanelController::class, 'cancelarSuscripcion'])
	->middleware('auth')
	->name('admin.usuarios.cancelar');
Route::post('/admin/usuarios/{usuario}/password', [TrainerPanelController::class, 'cambiarPassword'])
	->middleware('auth')
	->name('admin.usuarios.password');

// Crear entrenador desde el panel de administración (solo ADMIN)
Route::post('/admin/usuarios/entrenador', [TrainerPanelController::class, 'crearEntrenador'])
	->middleware('auth')
	->name('admin.usuarios.entrenador');

// Gestión de clases (solo entrenadores/admin)
Route::get('/classes/manage/create', [ClassManagementController::class, 'create'])->name('classes.create')->middleware('auth');
Route::post('/classes/manage', [ClassManagementController::class, 'store'])->name('classes.store')->middleware('auth');
Route::get('/classes/manage/{clase}/edit', [ClassManagementController::class, 'edit'])->name('classes.edit')->middleware('auth');
Route::put('/classes/manage/{clase}', [ClassManagementController::class, 'update'])->name('classes.update')->middleware('auth');
Route::delete('/classes/manage/{clase}', [ClassManagementController::class, 'destroy'])->name('classes.destroy')->middleware('auth');

// Mis clases del usuario
Route::get('/mis-clases', function () {
	$user = auth()->user();
	$clases = $user ? $user->clases()->orderBy('fecha')->orderBy('hora_inicio')->get() : collect();
	return view('mis_clases', compact('clases'));
})->middleware('auth')->name('mis.clases');

// Mis suscripciones
Route::view('/mis-suscripciones', 'mis_suscripciones')->middleware('auth')->name('mis.suscripciones');

// Recompensas y canje de Fitcoins
Route::get('/recompensas', [GymController::class, 'recompensas'])->middleware('auth')->name('recompensas');
Route::post('/recompensas/canjear', [GymController::class, 'canjearRecompensa'])->middleware('auth')->name('recompensas.canjear');

// Checkout simulado de pago para planes Premium / Premium Fit
Route::get('/checkout/{plan}', [PaymentController::class, 'showCheckout'])->middleware('auth')->name('checkout.show');
Route::post('/checkout', [PaymentController::class, 'processCheckout'])->middleware('auth')->name('checkout.process');



