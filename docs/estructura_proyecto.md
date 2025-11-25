# Estructura del Proyecto VirtualFit

## 1. Visión general

VirtualFit es una aplicación web desarrollada con Laravel que gestiona usuarios, entrenadores, clases, ejercicios diarios, rachas/Fitcoins, chat y paneles específicos para entrenadores y administradores.

La aplicación se organiza en varios módulos lógicos:
- Autenticación y usuarios
- Entrenadores y panel de control
- Clases y calendario
- Ejercicios diarios y rachas
- Chat usuario–entrenador
- Vídeos de ejercicios diarios
- Pagos y planes
- Contenido informativo / público

---

## 2. Rutas web principales (`routes/web.php`)

### 2.1. Home y páginas públicas
- `GET /` → `GymController@home` (`home.blade.php`)
- `GET /classes` → `GymController@classes`
- `GET /trainers` → `GymController@trainers`
- `GET /contact` → `GymController@contact`
- `GET /about` → `GymController@about`
- `GET /planes` → `PaymentController@plans`
- Vistas estáticas:
  - `GET /faq` → `faq.blade.php`
  - `GET /terminos` → `terminos.blade.php`
  - `GET /privacidad` → `privacidad.blade.php`

### 2.2. Autenticación y perfil
- `GET /login` → `AuthController@showLogin`
- `POST /login` → `AuthController@login`
- `GET /login-trainer` → `AuthController@showLoginTrainer`
- `POST /login-trainer` → `AuthController@login`
- `POST /logout` → `AuthController@logout`
- `GET /register` → `AuthController@showRegister`
- `POST /register` → `AuthController@register`
- `POST /password/request-reset` → `AuthController@requestPasswordReset`
- `GET /profile` → vista `profile` (requiere autenticación)
- `POST /profile/foto-entrenador` → `AuthController@updateTrainerPhoto` (requiere autenticación, solo entrenadores)

### 2.3. Ejercicios diarios y recompensas
- `GET /exercises` → `GymController@exercises` (auth)
- `POST /exercises/complete` → `GymController@completeDailyExercises` (auth)
- `POST /exercises/daily/video` → `GymController@subirVideoEjercicioDiario` (auth)
- `GET /exercises/all` → `ExerciseController@index` (auth)
- `GET /recompensas` → `GymController@recompensas` (auth)
- `POST /recompensas/canjear` → `GymController@canjearRecompensa` (auth)

### 2.4. Clases y calendario
- `GET /calendar` → `ClassCalendarController@index` (auth)
- `POST /calendar/{clase}/inscribirse` → `ClassCalendarController@inscribirse` (auth)
- `POST /calendar/{clase}/baja` → `ClassCalendarController@baja` (auth)

#### Gestión de clases (entrenadores/admin)
- `GET /classes/manage/create` → `ClassManagementController@create` (auth)
- `POST /classes/manage` → `ClassManagementController@store` (auth)
- `GET /classes/manage/{clase}/edit` → `ClassManagementController@edit` (auth)
- `PUT /classes/manage/{clase}` → `ClassManagementController@update` (auth)
- `DELETE /classes/manage/{clase}` → `ClassManagementController@destroy` (auth)

#### Clases del usuario
- `GET /mis-clases` → vista `mis_clases` con las clases del usuario autenticado

### 2.5. Chat usuario–entrenador
- `POST /chat/message` → `ChatController@reply`
- `GET /chat/conversation` → `ChatController@conversation` (auth)

### 2.6. Panel entrenador y administración
- `GET /trainer/panel` → `TrainerPanelController@index`
- `GET /trainer/videos-diarios` → `TrainerPanelController@videosEjerciciosDiarios` (auth)
- `GET /trainer/chat/{usuario}` → `TrainerPanelController@chatUsuario` (auth)
- `POST /trainer/mensajes/{mensaje}/respuesta` → `TrainerPanelController@responder` (auth)

#### Administración de usuarios (ADMIN)
- `GET /admin/usuarios` → `TrainerPanelController@adminUsuarios` (auth)
- `POST /admin/usuarios/{usuario}/cancelar` → `TrainerPanelController@cancelarSuscripcion` (auth)
- `POST /admin/usuarios/{usuario}/password` → `TrainerPanelController@cambiarPassword` (auth)

### 2.7. Pagos y suscripciones
- `GET /mis-suscripciones` → vista `mis_suscripciones` (auth)
- `GET /checkout/{plan}` → `PaymentController@showCheckout` (auth)
- `POST /checkout` → `PaymentController@processCheckout` (auth)

---

## 3. Controladores y responsabilidades

### 3.1. `GymController`
Responsable de:
- Página principal y secciones públicas (clases, entrenadores, contacto, about).
- Pantalla de ejercicios diarios del usuario.
- Registro de finalización de ejercicios diarios.
- Subida de vídeos de ejercicios diarios.
- Gestión de recompensas y canje de Fitcoins.

### 3.2. `AuthController`
Responsable de:
- Formularios de login/registro (usuarios y entrenadores).
- Inicio y cierre de sesión basados en sesión.
- Registro de nuevos usuarios, incluyendo la asignación automática de un entrenador.
- Actualización de la foto de perfil del entrenador (`foto_entrenador`).
- Creación de solicitudes de reseteo de contraseña para que un admin las gestione.

### 3.3. `ExerciseController`
Responsable de:
- Listado general de ejercicios disponibles (`/exercises/all`).

### 3.4. `ClassCalendarController`
Responsable de:
- Mostrar el calendario semanal de clases.
- Inscribir al usuario autenticado en una clase, respetando plazas y tipo de plan.
- Dar de baja al usuario de una clase futura.
- Preparar el listado de entrenadores con sus fotos para mostrarlos en el calendario.

### 3.5. `ClassManagementController`
Responsable de:
- CRUD de clases (crear, editar, actualizar, eliminar) para entrenadores y administradores.

### 3.6. `ChatController`
Responsable de:
- Recibir mensajes del usuario y generar respuesta en el contexto del chat.
- Devolver el historial de conversación entre el usuario y su entrenador asignado.

### 3.7. `TrainerPanelController`
Responsable de:
- Panel general para entrenadores.
- Visualizar y gestionar vídeos de ejercicios diarios de los clientes asignados al entrenador.
- Gestionar conversaciones de chat desde el lado del entrenador.
- Funciones de administración de usuarios (solo ADMIN): listado, cancelación de suscripciones, cambio de contraseña.

### 3.8. `PaymentController`
Responsable de:
- Mostrar planes de suscripción (freemium, premium, premiumFit).
- Proceso de "checkout" simulado para actualizar el plan del usuario.

---

## 4. Modelos principales y relaciones

### 4.1. `Usuario`
- Tabla: `usuarios`.
- Campos clave: `nombre`, `correo`, `password`, `tipo_usuario` (freemium, premium, premiumFit, ENTRENADOR, ADMIN), `entrenador_id`, `foto_entrenador`, `fecha_registro`.
- Relaciones destacadas:
  - `entrenador()` → `belongsTo(Usuario::class, 'entrenador_id')`.
  - `clientes()` → `hasMany(Usuario::class, 'entrenador_id')`.
  - `clases()` → relación muchos-a-muchos con `Clase` vía tabla pivot.
  - Relaciones con `Membresia`, `Pago`, `RegistroEjercicio`, `Racha`, `Canjeo`, `Seguimiento`, etc.

### 4.2. `Clase`
- Tabla: `clases`.
- Campos: `nombre`, `descripcion`, `fecha`, `hora_inicio`, `hora_fin`, `aforo_max`, `entrenador_id`, etc.
- Relaciones:
  - `entrenador()` → `belongsTo(Usuario::class, 'entrenador_id')`.
  - `usuarios()` → relación muchos-a-muchos con `Usuario` vía tabla `clases_usuarios`.
- Métodos auxiliares:
  - `plazasRestantes()` → calcula plazas según aforo y usuarios inscritos.

### 4.3. Ejercicios y progreso
- `Ejercicio`: catálogo de ejercicios.
- `RegistroEjercicio`: registro diario de ejercicios completados por usuario.
- `Racha`: rachas de días entrenados.
- `Recompensa` y `Canjeo`: sistema de Fitcoins y canjes.
- `Seguimiento`: seguimiento de progreso del usuario.

### 4.4. Chat y vídeos
- `ChatMensaje`: mensajes del chat usuario–entrenador.
- `VideoEjercicioDiario`: vídeos subidos por el usuario sobre sus ejercicios diarios.

### 4.5. Pagos y planes
- `Membresia`: información del plan actual del usuario.
- `Pago`: registros de pagos o cambios de plan.

---

## 5. Vistas principales (`resources/views`)

- Layout general: `layouts/app.blade.php`
- Páginas públicas:
  - `home.blade.php`
  - `classes.blade.php`
  - `trainers.blade.php`
  - `contact.blade.php`
  - `about.blade.php`
- Autenticación:
  - `auth/login.blade.php`
  - `auth/register.blade.php`
- Calendario de clases:
  - `calendar.blade.php` (incluye también tarjetas con entrenadores y sus fotos).
- Ejercicios:
  - `exercises.blade.php`
  - `exercises_daily.blade.php`
- Paneles / otros:
  - `profile.blade.php`
  - `mis_clases.blade.php`
  - `mis_suscripciones.blade.php`
  - Vistas del panel de entrenador y administración (dentro de la carpeta correspondiente).

---

## 6. Notas sobre archivos y almacenamiento

- Imágenes de entrenadores:
  - Se guardan en `storage/app/public/trainers` usando el disco `public`.
  - En la base de datos, el campo `foto_entrenador` almacena rutas tipo `storage/trainers/NombreArchivo.jpg`.
  - En las vistas se usan con `asset($usuario->foto_entrenador)` para generar la URL pública.
- Vídeos de ejercicios diarios:
  - Se guardan en `storage/app/public` bajo una carpeta específica (por ejemplo, `videos_diarios/`).
  - Se muestran a los entrenadores en el panel `TrainerPanelController@videosEjerciciosDiarios`.

---

## 7. APIs (`routes/api.php`)

Actualmente solo existe una ruta básica de ejemplo:

- `GET /api/user` → devuelve el usuario autenticado (usando `auth:sanctum`).

Se pueden añadir más endpoints REST bajo `/api` si se quiere exponer funcionalidades de clases, ejercicios, chat, etc.

---

Este documento sirve como guía rápida de la estructura del proyecto para desarrollo, mantenimiento y entrega de documentación.
