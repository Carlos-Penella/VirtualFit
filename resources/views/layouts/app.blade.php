<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VirtualFit - @yield('title', 'Inicio')</title>
    <link rel="stylesheet" href="{{ asset('css/gym.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="container" style="display:flex;align-items:center;justify-content:space-between;gap:2rem;">
            <!-- Zona izquierda: logo + navegación principal -->
            <div style="display:flex;align-items:center;gap:2rem;flex:1;">
                <h1 class="brand"><a href="{{ route('home') }}">VirtualFit</a></h1>
                <nav class="main-nav" style="display:flex;align-items:center;gap:1.2rem;">
                    <a href="{{ route('home') }}">Inicio</a>
                    <a href="{{ route('classes') }}">Clases</a>
                    <a href="{{ route('about') }}">Sobre nosotros</a>
                    <a href="{{ route('planes') }}">Suscripciones</a>
                    @auth
                        <a href="{{ route('exercises.all') }}">Ejercicios</a>
                        <a href="{{ route('calendar') }}">Calendario</a>
                        @if(auth()->user()->tipo_usuario === 'ADMIN')
                            <a href="{{ route('admin.usuarios') }}">Usuarios</a>
                        @endif
                    @endauth
                </nav>
            </div>

            <!-- Zona derecha: fitcoins/racha + avatar usuario -->
            <div style="display:flex;align-items:center;gap:1.5rem;">
                @auth
                    <div style="font-weight:bold;color:var(--accent);background:#eef;padding:.4rem 1rem;border-radius:6px;min-width:180px;display:flex;align-items:center;gap:1rem;">
                        <span style="font-size:1.1em;">&#x1F4B0; {{ optional(auth()->user()->racha)->fitcoins_ganados ?? 0 }}</span>
                        <span style="font-size:1.1em;">&#x1F4C5; {{ optional(auth()->user()->racha)->dias_consecutivos ?? 0 }} días</span>
                    </div>

                    <div class="user-menu" style="position:relative;">
                        <button class="user-avatar" id="user-menu-toggle" type="button">
                            {{ strtoupper(substr(auth()->user()->nombre,0,1)) }}
                        </button>
                        <div class="user-menu-dropdown" id="user-menu-dropdown" style="display:none;">
                            <div class="user-menu-email">{{ auth()->user()->correo }}</div>
                            <div class="user-menu-email">Plan: {{ auth()->user()->tipo_usuario }}</div>
                            <a href="{{ route('profile') }}" class="user-menu-link">👤 Mi perfil</a>
                            <a href="{{ route('mis.clases') }}" class="user-menu-link">📚 Mis clases</a>
                            <form method="post" action="{{ route('logout') }}" style="margin-top:.5rem;">
                                @csrf
                                <button type="submit" style="background:none;border:0;color:var(--muted);cursor:pointer;width:100%;text-align:left;">Cerrar sesión</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-login">Iniciar sesión</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} VirtualFit. Todos los derechos reservados.
                &nbsp;|&nbsp;
                <a href="{{ route('faq') }}">FAQ</a>
                &nbsp;|&nbsp;
                <a href="{{ route('terminos') }}">Términos</a>
                &nbsp;|&nbsp;
                <a href="{{ route('privacidad') }}">Privacidad</a>
            </p>
        </div>
    </footer>
</body>
@php
    $user = auth()->user();
    $tipo = $user->tipo_usuario ?? null;
    $isPremiumChat = in_array($tipo, ['PREMIUM', 'PREMIUMFIT'], true);
    $entrenadoresChat = \App\Models\Usuario::where('tipo_usuario', 'ENTRENADOR')->orderBy('nombre')->get();
@endphp

<div id="chat-widget" class="chat-widget">
    <button id="chat-toggle" class="chat-toggle">Chat</button>
    <div id="chat-window" class="chat-window" style="display:none;">
        <div class="chat-header">
            <span id="chat-mode-label">Asistente VirtualFit</span>
            <select id="chat-mode" class="chat-mode-select">
                <option value="assistant" selected>Asistente</option>
                <option value="trainer">Entrenador</option>
            </select>
            @auth
                @php
                    $miEntrenador = $user->entrenador ?? null;
                @endphp
                @if($miEntrenador)
                    <span style="margin-left:.5rem;font-size:.85rem;color:var(--muted);">
                        Tu entrenador: <strong>{{ $miEntrenador->nombre }}</strong>
                    </span>
                @endif
            @endauth
        </div>
        <div id="chat-messages" class="chat-messages">
            <div class="chat-message bot">Hola, soy el asistente de VirtualFit. Pregúntame sobre clases, ejercicios o cuenta premium.</div>
            @if(! $isPremiumChat)
                <div class="chat-message bot">
                    El modo <strong>Entrenador</strong> está disponible solo para usuarios Premium o Premium_Fit.
                    Si intentas escribir en ese modo te llevaremos a la página de planes para que puedas mejorarlo.
                </div>
            @endif
        </div>
        <form id="chat-form" class="chat-form">
            <input type="text" id="chat-input" placeholder="Escribe tu mensaje..." autocomplete="off" />
            <button type="submit">Enviar</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // User menu toggle
    const userToggle = document.getElementById('user-menu-toggle');
    const userDropdown = document.getElementById('user-menu-dropdown');

    if (userToggle && userDropdown) {
        userToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            const isHidden = userDropdown.style.display === 'none' || userDropdown.style.display === '';
            userDropdown.style.display = isHidden ? 'block' : 'none';
        });

        document.addEventListener('click', function () {
            userDropdown.style.display = 'none';
        });
    }

    const toggle = document.getElementById('chat-toggle');
    const windowEl = document.getElementById('chat-window');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const messages = document.getElementById('chat-messages');
    const modeSelect = document.getElementById('chat-mode');
    const modeLabel = document.getElementById('chat-mode-label');
    const trainerSelect = null; // ya no se elige entrenador manualmente
    const isPremium = {{ $isPremiumChat ? 'true' : 'false' }};

    if (!toggle || !windowEl || !form || !input || !messages) return;

    toggle.addEventListener('click', function () {
        const isHidden = windowEl.style.display === 'none' || windowEl.style.display === '';
        windowEl.style.display = isHidden ? 'flex' : 'none';
    });

    if (modeSelect && modeLabel) {
        modeSelect.addEventListener('change', function () {
            if (modeSelect.value === 'trainer') {
                modeLabel.textContent = 'Chat con entrenador';
                if (isPremium) {
                    appendMessage('bot', 'Estás en el modo Entrenador. Cargando tus consultas y respuestas anteriores...');
                    loadConversation('trainer');
                } else {
                    appendMessage('bot', 'El modo Entrenador es solo para usuarios Premium o Premium Fit. Si quieres usarlo, pulsa en "Mejorar mi plan" en la página de planes.');
                }
            } else {
                modeLabel.textContent = 'Asistente VirtualFit';
                appendMessage('bot', 'Has vuelto al modo Asistente. Puedo ayudarte con información general de la plataforma.');
            }
        });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const text = input.value.trim();
        if (!text) return;

        const mode = modeSelect ? modeSelect.value : 'assistant';

        // Si no es premium y está en modo entrenador, redirigir a planes
        if (!isPremium && mode === 'trainer') {
            appendMessage('bot', 'Para enviar mensajes a entrenadores necesitas un plan Premium. Te redirijo a la página de planes.');
            window.location.href = '{{ route('planes') }}';
            return;
        }

        appendMessage('user', text);
        input.value = '';

        fetch('{{ route('chat.message') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message: text, mode: mode })
        })
        .then(r => r.json())
        .then(data => {
            appendMessage('bot', data.reply || 'No he podido procesar tu mensaje.');
        })
        .catch(() => {
            appendMessage('bot', 'Ha ocurrido un error al enviar tu mensaje. Inténtalo de nuevo más tarde.');
        });
    });

    if (trainerSelect) {
        trainerSelect.addEventListener('change', function () {
            if (modeSelect && modeSelect.value === 'trainer' && isPremium) {
                // Al cambiar de entrenador en modo entrenador, recargar la conversación
                appendMessage('bot', 'Cargando historial con este entrenador...');
                loadConversation('trainer');
            }
        });
    }

    function appendMessage(type, text) {
        const div = document.createElement('div');
        div.className = 'chat-message ' + (type === 'user' ? 'user' : 'bot');
        div.textContent = text;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    function loadConversation(mode) {
        fetch(`{{ route('chat.conversation') }}?mode=${encodeURIComponent(mode)}`, {
            headers: {
                'Accept': 'application/json',
            },
        })
            .then(r => r.json())
            .then(data => {
                if (!data.mensajes) return;
                // Limpiar mensajes previos (menos el saludo inicial)
                while (messages.children.length > 0) {
                    messages.removeChild(messages.firstChild);
                }

                data.mensajes.forEach(item => {
                    appendMessage('user', item.mensaje);
                    if (item.respuesta_entrenador) {
                        appendMessage('bot', 'Respuesta del entrenador: ' + item.respuesta_entrenador);
                    }
                });
            })
            .catch(() => {
                appendMessage('bot', 'No se ha podido cargar el historial con tu entrenador.');
            });
    }
});
</script>
</html>
