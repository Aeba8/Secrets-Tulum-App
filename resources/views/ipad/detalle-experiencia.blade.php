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

    <!-- Top Navbar -->
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

    <!-- Main Workspace -->
    <div class="flex-1 w-full max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-12 px-8 items-center py-6 z-10">

        <!-- COLUMNA IZQUIERDA: Imagen / Carrusel -->
        <div class="md:col-span-5 flex flex-col justify-center items-center w-full relative">
            <div
                class="w-full aspect-[16/11] overflow-hidden rounded-xl shadow-[0_25px_60px_-10px_rgba(0,0,0,0.95)] border border-white/5 bg-black relative">
                <div id="carouselTrack" class="carousel-track h-full w-full">
                    <div class="min-w-full h-full shrink-0">
                        <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=800"
                            class="w-full h-full object-cover" alt="Slide 1">
                    </div>
                    <div class="min-w-full h-full shrink-0">
                        <img src="https://images.unsplash.com/photo-1519690889869-e49694ae0ec2?q=80&w=800"
                            class="w-full h-full object-cover" alt="Slide 2">
                    </div>
                </div>

                <button onclick="moveCarousel(-1)"
                    class="absolute left-3 top-0 bottom-0 my-auto w-8 h-8 bg-black/50 hover:bg-black/80 border border-[#C5A059]/30 rounded-full flex items-center justify-center text-secrets-gold text-lg transition active:scale-90 cursor-pointer backdrop-blur-xs">‹</button>
                <button onclick="moveCarousel(1)"
                    class="absolute right-3 top-0 bottom-0 my-auto w-8 h-8 bg-black/50 hover:bg-black/80 border border-[#C5A059]/30 rounded-full flex items-center justify-center text-secrets-gold text-lg transition active:scale-90 cursor-pointer backdrop-blur-xs">›</button>
            </div>
        </div>

        <!-- COLUMNA DERECHA: Datos del Modelo (Distribuido exactamente como la imagen de referencia) -->
        <div class="md:col-span-7 flex flex-col justify-center h-full space-y-6 lg:pl-4">
            <div>
                <span class="text-xs tracking-[0.35em] text-secrets-gold font-semibold uppercase block mb-1 font-mono">
                    {{ request('lang') == 'en' ? 'EXCLUSIVE PROGRAM' : 'PROGRAMA EXCLUSIVO' }}
                </span>
                <h1 class="text-4xl font-extralight text-white tracking-wide leading-tight">
                    {{ $experiencia->Nombre ?? $experiencia->name }}
                </h1>

                <!-- Grid de Ficha Técnica Basado en image_0307bf.jpg -->
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

            <!-- Pestañas Interactivas Dinámicas -->
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

            <!-- Contenedores de Texto de las Pestañas -->
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

    <!-- Bottom Action Bar -->
    <div class="border-t border-[#C5A059]/20 p-5 backdrop-blur-xl shrink-0 bg-black/40">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-8">
            <button onclick="irAlMapaEspacial()"
                class="h-12 px-8 bg-secrets-gold hover:bg-[#B38F4B] text-black font-semibold text-[11px] uppercase tracking-[0.25em] rounded-md shadow-2xl transition-all duration-300 transform active:scale-[0.99] cursor-pointer">
                {{ request('lang') == 'en' ? 'BOOK EXPERIENCE' : 'RESERVAR EXPERIENCIA' }}
            </button>

            <div class="text-right">
                <p class="text-4xl font-light text-white tracking-[0.08em]">
                    <span
                        class="text-secrets-gold font-normal">${{ number_format($experiencia->Precio ?? $experiencia->price) }}</span>
                    <span class="text-xs font-mono text-stone-500 ml-1">MXN</span>
                </p>
                <p class="text-[9px] text-stone-500 font-medium mt-0.5 tracking-[0.2em] uppercase font-mono">
                    {{ request('lang') == 'en' ? 'Taxes & service included' : 'Impuestos y servicio incluidos' }}
                </p>
            </div>
        </div>
    </div>

    <!-- JavaScript de Control Operativo -->
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const currentLang = urlParams.get('lang') || 'es';

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

                // Aplicar opacidad de carga solo a elementos con texto corto o válidos
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
        const totalSlides = track.children.length;

        function moveCarousel(direction) {
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

        function irAlMapaEspacial() {
            window.location.href =
                "{{ route('mapa.espacios') }}?package={{ $experiencia->slug ?? $experiencia->Slug }}&lang=" + currentLang;
        }
    </script>
</body>

</html>
