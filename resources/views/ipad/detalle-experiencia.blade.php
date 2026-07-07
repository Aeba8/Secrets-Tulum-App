@php
    // 1. FICHA TÉCNICA (Priorizamos la propiedad nativa 'Ficha_Tecnica')
    $ficha_objeto = $experiencia->Ficha_Tecnica ?? $experiencia->ficha_tecnica;
    $ficha_cruda = '';

    if (is_array($ficha_objeto)) {
        // Evaluamos si viene un JSON con llave interna o el primer elemento directo
        $ficha_cruda = $ficha_objeto['ficha_tecnica'] ?? ($ficha_objeto[0] ?? '');
    } else {
        $ficha_cruda = is_string($ficha_objeto) ? $ficha_objeto : '';
    }

    // Si viene vacío, usamos el fallback manual estándar
    if (empty($ficha_cruda)) {
        $ficha_cruda = 'Casa Madero 2V|Spa 40 minutos';
    }

    // Separamos la información usando el pipe '|'
    $partes = explode('|', $ficha_cruda);

    // Asignación de acuerdo a tu formato: [0] Botella | [1] Servicio Extra
    $botella = isset($partes[0]) && trim($partes[0]) !== '' ? trim($partes[0]) : 'No especificado';
    $servicios_extra = isset($partes[1]) && trim($partes[1]) !== '' ? trim($partes[1]) : 'No especificado';

    // 2. DESCRIPCIÓN (Prioridad a 'Descripcion' con D mayúscula)
    $descripcion =
        $experiencia->Descripcion ??
        ($experiencia->descripcion ?? 'Experiencia exclusiva diseñada para revitalizar cuerpo y alma.');

    // 3. PRODUCTOS / ALIMENTOS (Prioridad a 'Productos' con P mayúscula)
    $productos_data = $experiencia->Productos ?? $experiencia->productos;
    if (is_array($productos_data)) {
        // Si es un array de elementos del menú, los unimos limpiamente
        $alimentos = implode(', ', array_map('trim', $productos_data));
    } elseif (is_string($productos_data) && trim($productos_data) !== '') {
        $alimentos = trim($productos_data);
    } else {
        $alimentos = ''; // Lo dejamos vacío temporalmente para evaluar fallbacks
    }

    // Si sigue vacío tras la validación, ponemos el fallback de cortesía
    if (empty($alimentos)) {
        $alimentos = 'Incluye amenidades premium seleccionadas para esta sesión.';
    }

    // 4. TIPO (Prioridad a 'Tipo' con T mayúscula)
    $tipo_experiencia = $experiencia->Tipo ?? ($experiencia->tipo ?? 'No especificado');

    // 5. LUGAR, DURACIÓN Y HORARIO (Garantizamos compatibilidad de mayúsculas)
    $lugar_experiencia = $experiencia->Lugar ?? ($experiencia->lugar ?? 'No especificado');
    $duracion_experiencia = $experiencia->Duracion ?? ($experiencia->duracion ?? 'No especificado');
    $horario_experiencia = $experiencia->Horario ?? ($experiencia->horario ?? 'No especificado');
@endphp

<!DOCTYPE html>
<html lang="{{ request('lang', 'es') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secrets Pad - Detalle de Experiencia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght=300;400;500;600&display=swap');

        .font-sans {
            font-family: 'Montserrat', sans-serif;
        }

        .secrets-bg {
            background-image: linear-gradient(to right, rgba(10, 8, 9, 0.88) 5%, rgba(24, 18, 19, 0.85) 100%), url('https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=1920');
            background-size: cover;
            background-position: center;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .carousel-track {
            display: flex;
            transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .text-secrets-gold {
            color: #C5A059;
        }

        .border-secrets-gold {
            border-color: #C5A059;
        }

        .bg-secrets-gold {
            background-color: #C5A059;
        }

        .page-exit {
            opacity: 0;
            transform: scale(1.02);
            transition: all 0.4s ease-in-out;
        }

        .fade-text {
            transition: opacity 0.4s ease-in-out;
        }
    </style>
</head>

<body class="secrets-bg text-stone-200 font-sans min-h-screen flex flex-col justify-between select-none overflow-hidden">

    <div
        class="bg-[#0C090A] h-14 flex justify-between items-center px-8 shadow-2xl border-b border-[#C5A059]/20 shrink-0 z-20">
        <button onclick="regresarAlCatálogo()"
            class="text-stone-300 hover:text-white text-sm font-medium flex items-center space-x-1.5 active:scale-95 transition cursor-pointer">
            <span class="text-xl text-secrets-gold">‹</span>
            <span>{{ request('lang') == 'en' ? 'Back' : 'Ir atrás' }}</span>
        </button>
        <span class="text-secrets-gold text-xs font-semibold tracking-[0.3em] uppercase font-mono">
            {{ request('lang') == 'en' ? 'VIP EXPERIENCE' : 'EXPERIENCIA VIP' }}
        </span>
        <button onclick="navigateWithAnimation('{{ route('welcome') }}')"
            class="flex items-center gap-2 text-stone-400 hover:text-white text-sm font-medium tracking-wider focus:outline-none">
            <span>{{ request('lang') == 'en' ? 'Home' : 'Inicio' }}</span>
            <i class="fa-solid fa-house text-xs"></i>
        </button>
    </div>

    <div class="flex-1 w-full max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-12 px-8 items-center py-6 z-10">

        <div class="md:col-span-5 flex flex-col justify-center items-center w-full relative">
            <div
                class="w-full aspect-[16/11] overflow-hidden rounded-xl shadow-[0_25px_60px_-10px_rgba(0,0,0,0.95)] border border-white/5 bg-black relative">
                <div id="carouselTrack" class="carousel-track h-full w-full">
                    @forelse($experiencia->imagenes ?? [] as $foto)
                    <div class="min-w-full h-full shrink-0">
                        <img src="{{ $foto }}" class="w-full h-full object-cover" alt="Slide {{ $loop->iteration }}">
                    </div>
                    @empty
                    <div class="min-w-full h-full shrink-0">
                        <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=800"
                            class="w-full h-full object-cover" alt="Slide 1">
                    </div>
                    @endforelse
                </div>

                <button onclick="moveCarousel(-1)"
                    class="absolute left-3 top-0 bottom-0 my-auto w-8 h-8 bg-black/50 hover:bg-black/80 border border-[#C5A059]/30 rounded-full flex items-center justify-center text-secrets-gold text-lg transition active:scale-90 cursor-pointer backdrop-blur-xs">‹</button>
                <button onclick="moveCarousel(1)"
                    class="absolute right-3 top-0 bottom-0 my-auto w-8 h-8 bg-black/50 hover:bg-black/80 border border-[#C5A059]/30 rounded-full flex items-center justify-center text-secrets-gold text-lg transition active:scale-90 cursor-pointer backdrop-blur-xs">›</button>
            </div>
        </div>

        <div class="md:col-span-7 flex flex-col justify-center h-full space-y-6 lg:pl-4">
            <div>
                <span class="text-xs tracking-[0.35em] text-secrets-gold font-semibold uppercase block mb-1 font-mono">
                    {{ request('lang') == 'en' ? 'EXCLUSIVE PROGRAM' : 'PROGRAMA EXCLUSIVO' }}
                </span>
                <h1 class="text-4xl font-extralight text-white tracking-wide leading-tight">
                    {{ $experiencia->Nombre ?? $experiencia->name }}
                </h1>

                <div class="grid grid-cols-2 gap-4 mt-6 border-b border-white/10 pb-6 text-sm">
                    <div>
                        <p class="text-[10px] text-secrets-gold font-medium uppercase tracking-[0.15em] font-mono">
                            {{ request('lang') == 'en' ? 'Type:' : 'Tipo:' }}</p>
                        <p id="txt-tipo" class="text-stone-100 font-light mt-0.5 fade-text">
                            {{ $tipo_experiencia }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-secrets-gold font-medium uppercase tracking-[0.15em] font-mono">
                            {{ request('lang') == 'en' ? 'Max Capacity:' : 'Número de personas:' }}</p>
                        <p class="text-stone-100 font-light mt-0.5">
                            {{ $experiencia->Numero_Personas ?? $experiencia->numero_personas }} pax</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-secrets-gold font-medium uppercase tracking-[0.15em] font-mono">
                            {{ request('lang') == 'en' ? 'Location:' : 'Locación:' }}</p>
                        <p id="txt-lugar" class="text-stone-100 font-light mt-0.5 fade-text">{{ $lugar_experiencia }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] text-secrets-gold font-medium uppercase tracking-[0.15em] font-mono">
                            {{ request('lang') == 'en' ? 'Included Beverage:' : 'Botella:' }}</p>
                        <p id="txt-botella" class="text-stone-100 font-light mt-0.5 text-xs fade-text">
                            {{ $botella }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-secrets-gold font-medium uppercase tracking-[0.15em] font-mono">
                            {{ request('lang') == 'en' ? 'Duration:' : 'Duración:' }}</p>
                        <p id="txt-duracion" class="text-stone-100 font-light mt-0.5 fade-text">
                            {{ $duracion_experiencia }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-secrets-gold font-medium uppercase tracking-[0.15em] font-mono">
                            {{ request('lang') == 'en' ? 'Available Schedule:' : 'Horario Disponible:' }}</p>
                        <p id="txt-horario" class="text-stone-100 font-light mt-0.5 fade-text">
                            {{ $horario_experiencia }}</p>
                    </div>
                </div>
            </div>

            <div class="flex space-x-8 text-xs uppercase font-bold border-b border-white/5 tracking-[0.2em]">
                <button id="tab-descripcion" onclick="switchTab('descripcion')"
                    class="border-b-2 border-secrets-gold pb-2 text-white transition duration-200 cursor-pointer">
                    {{ request('lang') == 'en' ? 'DESCRIPTION' : 'DESCRIPCIÓN' }}
                </button>
                <button id="tab-alimentos" onclick="switchTab('alimentos')"
                    class="pb-2 text-stone-500 hover:text-stone-300 transition duration-200 cursor-pointer">
                    {{ request('lang') == 'en' ? 'FOOD & DRINKS' : 'ALIMENTOS O BEBIDAS' }}
                </button>
                <button id="tab-extras" onclick="switchTab('extras')"
                    class="pb-2 text-stone-500 hover:text-stone-300 transition duration-200 cursor-pointer">
                    {{ request('lang') == 'en' ? 'ADDITIONAL' : 'SERVICIOS EXTRA' }}
                </button>
            </div>

            <div class="text-sm text-stone-300/90 leading-relaxed max-w-xl h-[110px] overflow-y-auto no-scrollbar pr-1">
                <div id="content-descripcion" class="block">
                    <p id="txt-descripcion" class="font-light text-justify tracking-wide fade-text">
                        {{ $descripcion }}
                    </p>
                </div>
                <div id="content-alimentos" class="hidden">
                    <p id="txt-alimentos" class="font-light text-justify tracking-wide fade-text">
                        {{ $alimentos }}
                    </p>
                </div>
                <div id="content-extras" class="hidden">
                    <p id="txt-extras" class="font-light text-justify tracking-wide fade-text">
                        {{ $servicios_extra }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-[#C5A059]/20 p-5 backdrop-blur-xl shrink-0 bg-black/40">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-8">
            <button onclick="abrirModalReserva()"
                class="h-12 px-8 bg-secrets-gold hover:bg-[#B38F4B] text-black font-semibold text-[11px] uppercase tracking-[0.25em] rounded-md shadow-2xl transition-all duration-300 transform active:scale-[0.99] cursor-pointer">
                {{ request('lang') == 'en' ? 'BOOK EXPERIENCE' : 'RESERVAR EXPERIENCIA' }}
            </button>

            <div class="text-right">
                <p class="text-4xl font-light text-white tracking-[0.08em]">
                    <span
                        class="text-secrets-gold font-normal">${{ number_format($experiencia->Precio ?? ($experiencia->price ?? 0)) }}</span>
                    <span class="text-xs font-mono text-stone-500 ml-1">MXN</span>
                </p>
                <p class="text-[9px] text-stone-500 font-medium mt-0.5 tracking-[0.2em] uppercase font-mono">
                    {{ request('lang') == 'en' ? 'Taxes & service included' : 'Impuestos y servicio incluidos' }}
                </p>
            </div>
        </div>
    </div>

    <div id="modalReservaDirecta"
        class="fixed inset-0 bg-black/85 backdrop-blur-md hidden items-center justify-center z-50 p-4 animate-fade-in">
        <div
            class="bg-[#0D0F0E] border border-[#C5A059]/30 rounded-xl w-full max-w-md p-6 shadow-[0_0_50px_rgba(0,0,0,0.8)] flex flex-col space-y-4">

            <div class="flex justify-between items-center border-b border-white/5 pb-3">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 rounded-full bg-amber-500 shadow-[0_0_10px_#f59e0b]"></div>
                    <h3 class="text-white font-medium text-base uppercase tracking-wider text-secrets-gold">
                        {{ request('lang') == 'en' ? 'Booking Details' : 'Detalles de Reservación' }}
                    </h3>
                </div>
                <button onclick="cancelarOperacion()"
                    class="text-stone-400 hover:text-white text-xl cursor-pointer active:scale-90 transition">&times;</button>
            </div>

            <div class="space-y-4 text-sm">
                <div>
                    <label
                        class="block text-xs text-stone-400 uppercase tracking-widest mb-1.5 font-mono font-semibold">
                        * {{ request('lang') == 'en' ? 'Date:' : 'Fecha de la Experiencia:' }}
                    </label>
                    <div class="relative">
                        <input type="date" id="input-fecha"
                            class="w-full bg-white/[0.04] border border-white/10 rounded px-3 py-2.5 text-white focus:outline-none focus:border-[#C5A059] focus:ring-1 focus:ring-[#C5A059] transition color-scheme-dark">
                    </div>
                </div>

                <div>
                    <label class="block text-xs text-stone-400 uppercase tracking-widest mb-1.5 font-mono">
                        * {{ request('lang') == 'en' ? 'Room Number (4 digits):' : 'Número de Habitación (4 dígitos):' }}
                    </label>
                    <input type="text" id="input-habitacion" maxlength="4" inputmode="numeric" placeholder="e.g. 4123" 
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="w-full bg-white/[0.02] border border-white/10 rounded px-3 py-2.5 text-white placeholder-stone-700 focus:outline-none focus:border-[#C5A059] transition">
                </div>

                <div>
                    <label class="block text-xs text-stone-400 uppercase tracking-widest mb-1.5 font-mono">
                        * {{ request('lang') == 'en' ? 'Seller ID (6 digits):' : 'Número de Colaborador (6 dígitos):' }}
                    </label>
                    <input type="text" id="input-vendedor" maxlength="6" inputmode="numeric" placeholder="e.g. 102405" 
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="w-full bg-white/[0.02] border border-white/10 rounded px-3 py-2.5 text-white placeholder-stone-700 focus:outline-none focus:border-[#C5A059] transition">
                </div>

                <div>
                    <label class="block text-xs text-stone-400 uppercase tracking-widest mb-1.5 font-mono">
                        {{ request('lang') == 'en' ? 'Notes:' : 'Observaciones Adicionales:' }}
                    </label>
                    <textarea id="input-observaciones" rows="2" placeholder="..."
                        class="w-full bg-white/[0.02] border border-white/10 rounded px-3 py-2 text-white placeholder-stone-700 resize-none focus:outline-none focus:border-[#C5A059] transition"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <button onclick="cancelarOperacion()"
                    class="w-full h-11 bg-neutral-900 hover:bg-neutral-800 text-stone-400 border border-white/10 font-medium text-xs uppercase tracking-widest rounded transition active:scale-[0.98] cursor-pointer">
                    {{ request('lang') == 'en' ? 'Cancel' : 'Cancelar' }}
                </button>
                <button onclick="solicitarConfirmacion()"
                    class="w-full h-11 bg-secrets-gold hover:bg-[#B38F4B] text-black font-semibold text-xs uppercase tracking-widest rounded shadow-xl transition active:scale-[0.98] cursor-pointer">
                    {{ request('lang') == 'en' ? 'Book' : 'Reservar' }}
                </button>
            </div>
        </div>
    </div>

    <style>
        .color-scheme-dark {
            color-scheme: dark;
        }
    </style>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const currentLang = urlParams.get('lang') || 'es';

        // Mixin de SweetAlert2 con la paleta de colores de SecretsPad (Fondo oscuro y acentos dorados)
        const swalCustomButtons = Swal.mixin({
            customClass: {
                confirmButton: 'px-5 py-2.5 mx-2 rounded-lg bg-[#C5A059] text-black font-bold text-xs uppercase tracking-widest hover:bg-[#b08e4f] transition-all shadow-lg cursor-pointer',
                cancelButton: 'px-5 py-2.5 mx-2 rounded-lg bg-neutral-800 text-stone-300 border border-white/10 font-bold text-xs uppercase tracking-widest hover:bg-neutral-700 transition-all cursor-pointer'
            },
            buttonsStyling: false,
            background: 'linear-gradient(to bottom, #141414, #0d0f0e)',
            color: '#e7e5e4'
        });

        async function traducirTextoAIngles(textoOriginal) {
            if (!textoOriginal || textoOriginal.trim() === "") return "";
            try {
                const response = await fetch(
                    `https://api.mymemory.translated.net/get?q=${encodeURIComponent(textoOriginal)}&langpair=es|en`);
                const data = await response.json();
                return data.responseData?.translatedText || textoOriginal;
            } catch (error) {
                return textoOriginal;
            }
        }

        document.addEventListener("DOMContentLoaded", async function() {
            if (currentLang === 'en') {
                const elementos = ['txt-tipo', 'txt-lugar', 'txt-duracion', 'txt-horario', 'txt-descripcion',
                    'txt-alimentos', 'txt-extras'
                ];

                elementos.forEach(id => {
                    const el = document.getElementById(id);
                    if (el && el.innerText.trim() !== "") el.style.opacity = '0.4';
                });

                for (const id of elementos) {
                    const el = document.getElementById(id);
                    if (el && el.innerText.trim() !== "") {
                        try {
                            const textoTraducido = await traducirTextoAIngles(el.innerText);
                            el.innerText = textoTraducido;
                        } catch (e) {
                            console.error("Error traduciendo el elemento: " + id, e);
                        } finally {
                            el.style.opacity = '1';
                        }
                    }
                }
            }
        });

        // Carrusel
        let currentSlide = 0;
        const track = document.getElementById('carouselTrack');
        const totalSlides = track?.children?.length || 0;

        function moveCarousel(direction) {
            if(totalSlides === 0) return;
            currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
            track.style.transform = `translateX(-${currentSlide * 100}%)`;
        }

        // Sistema de Pestañas (Tabs)
        function switchTab(tab) {
            const tabs = ['descripcion', 'alimentos', 'extras'];
            tabs.forEach(t => {
                const btn = document.getElementById(`tab-${t}`);
                const content = document.getElementById(`content-${t}`);
                if (t === tab) {
                    btn.classList.add('border-b-2', 'border-secrets-gold', 'text-white');
                    btn.classList.remove('text-stone-500');
                    content.classList.remove('hidden');
                } else {
                    btn.classList.remove('border-b-2', 'border-secrets-gold', 'text-white');
                    btn.classList.add('text-stone-500');
                    content.classList.add('hidden');
                }
            });
        }

        function navigateWithAnimation(targetUrl) {
            document.querySelector('body').classList.add('page-exit');
            setTimeout(() => {
                window.location.href = targetUrl + (targetUrl.includes('?') ? '&' : '?') + "lang=" + currentLang;
            }, 400);
        }

        function regresarAlCatálogo() {
            navigateWithAnimation("{{ route('paquetes.experiencias') }}");
        }

        function abrirModalReserva() {
            document.getElementById('modalReservaDirecta').classList.remove('hidden');
            document.getElementById('modalReservaDirecta').classList.add('flex');

            const hoy = new Date();
            const offset = hoy.getTimezoneOffset();
            const hoyLocal = new Date(hoy.getTime() - (offset * 60 * 1000)).toISOString().split('T')[0];

            const inputFecha = document.getElementById('input-fecha');
            inputFecha.value = hoyLocal;
            inputFecha.min = hoyLocal; 
        }

        function cerrarModalReserva() {
            document.getElementById('modalReservaDirecta').classList.remove('flex');
            document.getElementById('modalReservaDirecta').classList.add('hidden');
        }

        /**
         * Alerta de cancelación estética al cerrar o dar clic en salir
         */
        function cancelarOperacion() {
            swalCustomButtons.fire({
                title: currentLang === 'en' ? 'ABANDON RESERVATION?' : '¿CANCELAR RESERVA?',
                text: currentLang === 'en' ? 'Any progress entered will be lost.' : 'Se perderán los datos capturados en este formulario.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: currentLang === 'en' ? 'YES, QUIT' : 'SÍ, CANCELAR',
                cancelButtonText: currentLang === 'en' ? 'KEEP EDITING' : 'SEGUIR AQUÍ',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    cerrarModalReserva();
                }
            });
        }

        /**
         * Solicita confirmación explícita del usuario y valida los 4 y 6 dígitos estrictos
         */
        function solicitarConfirmacion() {
            const txtFecha = document.getElementById('input-fecha').value;
            const txtHabitacion = document.getElementById('input-habitacion').value.trim();
            const txtVendedor = document.getElementById('input-vendedor').value.trim();
            const txtObservaciones = document.getElementById('input-observaciones').value;

            // 1. Validar campos obligatorios vacíos
            if (!txtFecha || !txtHabitacion || !txtVendedor) {
                swalCustomButtons.fire({
                    icon: 'error',
                    title: currentLang === 'en' ? 'MISSING FIELDS' : 'CAMPOS INCOMPLETOS',
                    text: currentLang === 'en' ? 'Please fill out all required fields (*).' : 'Por favor, completa todos los campos marcados como obligatorios (*).'
                });
                return;
            }

            // 🌟 2. Validación Estricta: Exactamente 4 números para Habitación
            if (!/^\d{4}$/.test(txtHabitacion)) {
                swalCustomButtons.fire({
                    icon: 'warning',
                    title: currentLang === 'en' ? 'INVALID ROOM' : 'HABITACIÓN INVÁLIDA',
                    text: currentLang === 'en' ? 'The room number must contain exactly 4 digits (e.g., 4123).' : 'El número de habitación debe ser exactamente de 4 dígitos (ejemplo: 4123).'
                });
                return;
            }

            // 🌟 3. Validación Estricta: Exactamente 6 números para Colaborador
            if (!/^\d{6}$/.test(txtVendedor)) {
                swalCustomButtons.fire({
                    icon: 'warning',
                    title: currentLang === 'en' ? 'INVALID COLLABORATOR' : 'COLABORADOR INVÁLIDO',
                    text: currentLang === 'en' ? 'The collaborator ID must contain exactly 6 digits (e.g., 102405).' : 'El número de colaborador debe ser exactamente de 6 dígitos (ejemplo: 102405).'
                });
                return;
            }

            // 🌟 4. Modal estético previo a insertar en SQL Server
            swalCustomButtons.fire({
                title: currentLang === 'en' ? 'CONFIRM RESERVATION?' : '¿CONFIRMAR RESERVACIÓN?',
                html: `
                    <div class="text-left bg-black/40 border border-white/5 p-4 rounded-xl mt-3 space-y-2 text-xs tracking-wider uppercase text-stone-300 font-mono">
                        <p><span class="text-stone-500">Habitación:</span> <strong class="text-white">${txtHabitacion}</strong></p>
                        <p><span class="text-stone-500">Fecha Servicio:</span> <strong class="text-[#C5A059]">${txtFecha}</strong></p>
                        <p><span class="text-stone-500">Colaborador:</span> <strong class="text-white">${txtVendedor}</strong></p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: currentLang === 'en' ? 'CONFIRM AND BOOK' : 'CONFIRMAR Y RESERVAR',
                cancelButtonText: currentLang === 'en' ? 'REVIEW DATA' : 'REVISAR DATOS',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    enviarReservaAlServidor(txtFecha, txtHabitacion, txtVendedor, txtObservaciones);
                }
            });
        }

        /**
         * Ejecuta la petición asíncrona hacia el controlador dedicado
         */
        async function enviarReservaAlServidor(txtFecha, txtHabitacion, txtVendedor, txtObservaciones) {
            // Animación de carga para congelar la iPad y evitar doble submit accidental
            Swal.fire({
                title: currentLang === 'en' ? 'Processing...' : 'Procesando...',
                text: currentLang === 'en' ? 'Registering your reservation' : 'Guardando la reservación en el sistema...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                background: 'linear-gradient(to bottom, #141414, #0d0f0e)',
                color: '#fff',
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const datosReserva = {
                serviciable_type: "App\\Models\\Experiencia",
                serviciable_id: parseInt("{{ $experiencia->Id ?? ($experiencia->id ?? 1) }}"),
                fecha: txtFecha,
                habitacion: txtHabitacion,
                numero_colaborador_vendedor: txtVendedor,
                observaciones: txtObservaciones || null
            };

            try {
                // Apunta directamente a la nueva API exclusiva para experiencias
                const response = await fetch("{{ route('api.experiencias.reservar') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'include', 
                    body: JSON.stringify(datosReserva)
                });

                if (!response.ok) {
                    const resultadoError = await response.json();
                    throw new Error(resultadoError.message || "Error 500");
                }

                const resultado = await response.json();

                if (resultado.success) {
                    // Toast de éxito temporal
                    Swal.fire({
                        icon: 'success',
                        title: currentLang === 'en' ? 'SUCCESSFULLY BOOKED!' : '¡RESERVA EXITOSA!',
                        text: currentLang === 'en' ? 'The experience has been secured.' : 'La experiencia ha sido reservada de forma correcta.',
                        timer: 2000,
                        showConfirmButton: false,
                        background: 'linear-gradient(to bottom, #141414, #0d0f0e)',
                        color: '#fff'
                    });

                    document.body.classList.add('page-exit');
                    setTimeout(() => {
                        window.location.href = "{{ route('welcome') }}?lang=" + currentLang;
                    }, 2000);
                } else {
                    swalCustomButtons.fire({
                        icon: 'error',
                        title: 'Ups...',
                        text: resultado.message
                    });
                }
            } catch (error) {
                console.error("Error crítico en la petición:", error);
                swalCustomButtons.fire({
                    icon: 'error',
                    title: 'Error de Servidor',
                    text: 'No se pudo registrar la reserva. Detalle: ' + error.message
                });
            }
        }
    </script>
</body>

</html>