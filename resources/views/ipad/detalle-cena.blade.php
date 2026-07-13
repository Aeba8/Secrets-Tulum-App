@php
    // Usamos el desglose directo del modelo de Cenas Especiales según tu API Resource
    $entrada = $cena->Entrada ?? ($cena->entrada ?? '');
    $crema = $cena->Crema ?? ($cena->crema ?? '');
    $plato_fuerte = $cena->Plato_fuerte ?? ($cena->plato_fuerte ?? '');
    $postre = $cena->Postre ?? ($cena->postre ?? '');

    // Ficha técnica para detalles adicionales (Ej: Botella de cortesía, etc.)
    $parts = explode('|', $cena->ficha_tecnica ?? ($cena->Ficha_Tecnica ?? ''));
    $detalle_adicional =
        isset($parts[0]) && !empty(trim($parts[0])) ? trim($parts[0]) : '1 botella de Moet & Chandon Brut 750ml';
@endphp

<!DOCTYPE html>
<html lang="{{ request('lang', 'es') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secrets Pad - Detalle de Cena Romántica</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght=300;400;500;600&display=swap');

        .font-sans {
            font-family: 'Montserrat', sans-serif;
        }

        /* Fondo difuminado inmersivo con escena de cena de gala */
        .secrets-bg {
            background-image: linear-gradient(to right, rgba(10, 8, 9, 0.88) 5%, rgba(24, 18, 19, 0.85) 100%), url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1920');
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
            width: 100%;
            /* 🌟 Fuerza el ancho base riguroso */
            will-change: transform;
            /* 🌟 Prepara la aceleración por hardware en la tarjeta gráfica */
            transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1);
        }

        /* Identidad de Marca Dorada de Secrets */
        .text-secrets-gold {
            color: #C5A059;
        }

        .border-secrets-gold {
            border-color: #C5A059;
        }

        .bg-secrets-gold {
            background-color: #C5A059;
        }

        .hover\:bg-secrets-gold-dark:hover {
            background-color: #B38F4B;
        }

        .page-exit {
            opacity: 0;
            transform: scale(1.02);
            transition: all 0.4s ease-in-out;
        }

        .fade-text {
            transition: opacity 0.4s ease-in-out;
        }
        .fs-modal { backdrop-filter: blur(6px); }
        .fs-modal img { touch-action: manipulation; }
    </style>
</head>

<body class="secrets-bg text-stone-200 font-sans min-h-screen flex flex-col justify-between select-none overflow-hidden">

    <!-- Top Navbar -->
    <div
        class="bg-[#0C090A] h-14 flex justify-between items-center px-8 shadow-2xl border-b border-[#C5A059]/20 shrink-0 z-20">
        <button onclick="regresarAlPaquete()"
            class="text-stone-300 hover:text-white text-sm font-medium flex items-center space-x-1.5 active:scale-95 transition cursor-pointer">
            <span class="text-xl text-secrets-gold">‹</span>
            <span>{{ request('lang') == 'en' ? 'Back' : 'Ir atrás' }}</span>
        </button>
        <span class="text-secrets-gold text-xs font-semibold tracking-[0.3em] uppercase font-mono">
            {{ request('lang') == 'en' ? 'Romantic Dining Experience' : 'Experiencia Gastronómica Romántica' }}
        </span>
        <button onclick="navigateWithAnimation('{{ route('welcome') }}')"
            class="flex items-center gap-2 opacity-90 hover:opacity-100 transition-all cursor-pointer focus:outline-none text-stone-400 hover:text-white text-sm font-medium tracking-wider">
            <span>{{ request('lang') == 'en' ? 'Home' : 'Inicio' }}</span>
            <i class="fa-solid fa-house text-xs"></i>
        </button>
    </div>

    <!-- Main Workspace (Distribución 50/50 idéntica) -->
    <div class="flex-1 w-full max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-12 px-8 items-center py-6 z-10">

        <!-- COLUMNA IZQUIERDA: Carrusel Inmersivo con Imagen de Muestra -->
        <div class="md:col-span-6 flex flex-col justify-center items-center w-full relative group">
            <div
                class="w-full aspect-[16/10] overflow-hidden rounded-xl shadow-[0_25px_60px_-10px_rgba(0,0,0,0.95)] border border-white/5 bg-black relative">

                <div id="carouselTrack" class="carousel-track h-full w-full">
                    @forelse($cena->imagenes ?? [] as $foto)
                        <div class="w-full min-w-full h-full shrink-0">
                            <img src="{{ $foto }}" class="w-full h-full object-cover cursor-pointer"
                                alt="Slide {{ $loop->iteration }}"
                                onclick="abrirFullscreen({{ $loop->index }})">
                        </div>
                    @empty
                        <div class="w-full min-w-full h-full shrink-0">
                            <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=800"
                                class="w-full h-full object-cover cursor-pointer" alt="Slide 1"
                                onclick="abrirFullscreen(0)">
                        </div>
                    @endforelse
                </div>

                <button onclick="moveCarousel(-1)"
                    class="absolute left-4 top-0 bottom-0 my-auto w-10 h-10 bg-black/50 hover:bg-black/80 border border-[#C5A059]/30 rounded-full flex items-center justify-center text-secrets-gold text-xl transition active:scale-90 cursor-pointer backdrop-blur-xs">
                    ‹
                </button>
                <button onclick="moveCarousel(1)"
                    class="absolute right-4 top-0 bottom-0 my-auto w-10 h-10 bg-black/50 hover:bg-black/80 border border-[#C5A059]/30 rounded-full flex items-center justify-center text-secrets-gold text-xl transition active:scale-90 cursor-pointer backdrop-blur-xs">
                    ›
                </button>

                <div id="carouselDots" class="absolute bottom-5 left-0 right-0 flex justify-center space-x-2.5 z-10">
                    @forelse($cena->imagenes ?? [] as $foto)
                        <span
                            class="w-2 h-2 rounded-full {{ $loop->first ? 'bg-white' : 'bg-white/40' }} transition-all duration-300 cursor-pointer"
                            onclick="setSlide({{ $loop->index }})"></span>
                    @empty
                        <span class="w-2 h-2 rounded-full bg-white transition-all duration-300 cursor-pointer"
                            onclick="setSlide(0)"></span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: Datos del Menú Extrapolados de image_f6c89b.jpg -->
        <div class="md:col-span-6 flex flex-col justify-center h-full space-y-8 lg:pl-4">
            <div>
                <span class="text-xs tracking-[0.35em] text-secrets-gold font-semibold uppercase block mb-2 font-mono">
                    {{ request('lang') == 'en' ? 'ROMANTIC EXPERIENCE' : 'CENAS ROMÁNTICAS GROTTO' }}
                </span>
                <h1 class="text-4xl lg:text-5xl font-extralight text-white tracking-wide leading-tight font-sans">
                    {{ $cena->Nombre ?? ($cena->nombre ?? 'Cena Exclusiva') }}
                </h1>

                <!-- Grid de Características -->
                <div class="grid grid-cols-2 gap-5 mt-8 border-b border-white/10 pb-6">
                    <!-- Restaurante / Locación -->
                    <div class="bg-white/[0.02] p-3.5 rounded-lg border border-white/5 shadow-2xl">
                        <p class="text-[10px] text-secrets-gold font-medium uppercase tracking-[0.2em] font-mono">
                            {{ request('lang') == 'en' ? 'Restaurant / Venue:' : 'Restaurante:' }}
                        </p>
                        <p id="txt-restaurante" class="text-base font-light text-stone-100 mt-1 fade-text">
                            {{ $cena->restaurant ?? 'Grotto' }}
                        </p>
                    </div>
                    <!-- Número de Personas -->
                    <div class="bg-white/[0.02] p-3.5 rounded-lg border border-white/5 shadow-2xl">
                        <p class="text-[10px] text-secrets-gold font-medium uppercase tracking-[0.2em] font-mono">
                            {{ request('lang') == 'en' ? 'Guests:' : 'Número de personas:' }}
                        </p>
                        <p class="text-base font-light text-stone-100 mt-1">
                            {{ $cena->numero_personas ?? 2 }} Pax
                        </p>
                    </div>
                    <!-- Botella Incluida -->
                    <div class="col-span-2 bg-white/[0.02] p-3.5 rounded-lg border border-white/5 shadow-2xl">
                        <p class="text-[10px] text-secrets-gold font-medium uppercase tracking-[0.2em] font-mono">
                            {{ request('lang') == 'en' ? 'Included Bottle:' : 'Botella:' }}
                        </p>
                        <p id="txt-botella" class="text-base font-light text-stone-100 mt-1 fade-text">
                            {{ $detalle_adicional }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tabs de navegación basadas en los 4 Tiempos de image_f6c89b.jpg -->
            <div
                class="flex flex-wrap gap-x-6 gap-y-2 text-xs uppercase font-bold border-b border-white/5 tracking-[0.2em]">
                <button id="tab-entrada" onclick="switchTab('entrada')"
                    class="border-b-2 border-secrets-gold pb-3 text-white transition duration-200 cursor-pointer">
                    {{ request('lang') == 'en' ? 'STARTER' : 'ENTRADA' }}
                </button>
                <button id="tab-crema" onclick="switchTab('crema')"
                    class="pb-3 text-stone-500 hover:text-stone-300 transition duration-200 cursor-pointer">
                    {{ request('lang') == 'en' ? 'SOUP / CREAM' : 'CREMA' }}
                </button>
                <button id="tab-fuerte" onclick="switchTab('fuerte')"
                    class="pb-3 text-stone-500 hover:text-stone-300 transition duration-200 cursor-pointer">
                    {{ request('lang') == 'en' ? 'MAIN COURSE' : 'PLATO FUERTE' }}
                </button>
                <button id="tab-postre" onclick="switchTab('postre')"
                    class="pb-3 text-stone-500 hover:text-stone-300 transition duration-200 cursor-pointer">
                    {{ request('lang') == 'en' ? 'DESSERT' : 'POSTRE' }}
                </button>
            </div>

            <!-- Contenedores Dinámicos de los Platos -->
            <div
                class="text-base text-stone-300/90 leading-relaxed max-w-xl h-[130px] overflow-y-auto no-scrollbar pr-1">

                <div id="content-entrada" class="block">
                    <p id="txt-entrada" class="font-light text-stone-300/90 text-justify tracking-wide fade-text">
                        {{ $entrada ?: 'Mar y bosque - Sea and Forest. Lingote de atún ennegrecido, emulsión de aguacate con menta, jalea de frutos rojos del bosque y chile serrano.' }}
                    </p>
                </div>

                <div id="content-crema" class="hidden">
                    <p id="txt-crema" class="font-light text-stone-300/90 text-justify tracking-wide fade-text">
                        {{ $crema ?: 'Crema gourmet sugerida por el Chef a base de ingredientes locales y de estación.' }}
                    </p>
                </div>

                <div id="content-fuerte" class="hidden">
                    <p id="txt-fuerte" class="font-light text-stone-300/90 text-justify tracking-wide fade-text">
                        {{ $plato_fuerte ?: 'Corte premium de elección o pesca del día sobre cama de vegetales orgánicos glaseados.' }}
                    </p>
                </div>

                <div id="content-postre" class="hidden">
                    <p id="txt-postre" class="font-light text-stone-300/90 text-justify tracking-wide fade-text">
                        {{ $postre ?: 'Postre artesanal de autor acompañado de sutiles notas dulces y texturas balanceadas.' }}
                    </p>
                </div>

            </div>
        </div>
    </div>

    <!-- Cierre Inferior (Precios y Enrutamiento al mapa de Cenas) -->
    <div class="border-t border-[#C5A059]/20 p-6 backdrop-blur-xl shrink-0 bg-black/40">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-8">

            <button onclick="irAlMapaEspacial()"
                class="h-12 px-8 bg-secrets-gold hover:bg-[#B38F4B] text-black font-semibold text-[11px] uppercase tracking-[0.25em] rounded-md shadow-2xl transition-all duration-300 transform active:scale-[0.99] cursor-pointer">
                {{ request('lang') == 'en' ? 'RESERVE EXPERIENCE' : 'RESERVAR EXPERIENCIA' }}
            </button>

            <div class="text-right">
                <p class="text-4xl font-light text-white tracking-[0.08em]">
                    <span
                        class="text-secrets-gold font-normal">${{ number_format($cena->Precio ?? ($cena->precio ?? 0)) }}</span>
                    <span class="text-xs font-mono text-stone-500 ml-1">MXN</span>
                </p>
                <p class="text-[9px] text-stone-500 font-medium mt-1 tracking-[0.2em] uppercase font-mono">
                    {{ request('lang') == 'en' ? 'Taxes & service included' : 'Impuestos y servicio incluidos' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Fullscreen Zoom Modal -->
    <div id="fsModal" class="fixed inset-0 bg-black/95 fs-modal z-50 hidden items-center justify-center" onclick="cerrarFullscreen(event)">
        <button onclick="event.stopPropagation(); cerrarFullscreen(event)"
            class="absolute top-4 right-4 z-10 w-10 h-10 bg-white/10 hover:bg-white/25 rounded-full flex items-center justify-center text-white text-xl transition cursor-pointer backdrop-blur-sm border border-white/10">✕</button>
        <button onclick="event.stopPropagation(); moveFsCarousel(-1)"
            class="absolute left-4 top-0 bottom-0 my-auto w-12 h-12 bg-white/10 hover:bg-white/25 rounded-full flex items-center justify-center text-white text-2xl transition cursor-pointer backdrop-blur-sm border border-white/10 z-10">‹</button>
        <button onclick="event.stopPropagation(); moveFsCarousel(1)"
            class="absolute right-4 top-0 bottom-0 my-auto w-12 h-12 bg-white/10 hover:bg-white/25 rounded-full flex items-center justify-center text-white text-2xl transition cursor-pointer backdrop-blur-sm border border-white/10 z-10">›</button>
        <div id="fsWrapper" class="w-full h-full flex items-center justify-center overflow-hidden cursor-grab active:cursor-grabbing">
            <img id="fsImg" class="max-w-full max-h-full object-contain select-none" style="transition: transform 0.2s ease;">
        </div>
        <div id="fsCounter" class="absolute bottom-6 left-0 right-0 text-center text-white/50 text-sm font-mono tracking-wider"></div>
    </div>

    <!-- Script de Control Operativo y Traducción API en Vivo -->
    <script>
        // Lee los parámetros directamente de la URL del iPad
        const urlParams = new URLSearchParams(window.location.search);
        const packageSlug = urlParams.get('package') || '';
        // Busca 'package_id' o 'id' desde la URL, y si no hay ninguno, usa 1
        const packageId = parseInt(urlParams.get('package_id')) || parseInt(urlParams.get('id')) || 1;
        const currentLang = urlParams.get('lang') || 'es';

        const serviciableType = "App\\Models\\CenaEspecial";

        // Motor asíncrono de traducción
        async function traducirTextoAIngles(textoOriginal) {
            if (!textoOriginal || textoOriginal.trim() === "") return "";
            try {
                const response = await fetch(
                    `https://api.mymemory.translated.net/get?q=${encodeURIComponent(textoOriginal)}&langpair=es|en`);
                const data = await response.json();
                return data.responseData?.translatedText || textoOriginal;
            } catch (error) {
                console.error("Error al traducir de forma automática:", error);
                return textoOriginal;
            }
        }

        document.addEventListener("DOMContentLoaded", async function() {
            if (currentLang === 'en') {
                const elRestaurante = document.getElementById('txt-restaurante');
                const elBotella = document.getElementById('txt-botella');
                const elEntrada = document.getElementById('txt-entrada');
                const elCrema = document.getElementById('txt-crema');
                const elFuerte = document.getElementById('txt-fuerte');
                const elPostre = document.getElementById('txt-postre');

                const elementos = [elRestaurante, elBotella, elEntrada, elCrema, elFuerte, elPostre];
                elementos.forEach(el => {
                    if (el) el.style.opacity = '0.4';
                });

                const [tRest, tBot, tEnt, tCre, tFuer, tPost] = await Promise.all([
                    traducirTextoAIngles(elRestaurante?.innerText),
                    traducirTextoAIngles(elBotella?.innerText),
                    traducirTextoAIngles(elEntrada?.innerText),
                    traducirTextoAIngles(elCrema?.innerText),
                    traducirTextoAIngles(elFuerte?.innerText),
                    traducirTextoAIngles(elPostre?.innerText)
                ]);

                if (elRestaurante) {
                    elRestaurante.innerText = tRest;
                    elRestaurante.style.opacity = '1';
                }
                if (elBotella) {
                    elBotella.innerText = tBot;
                    elBotella.style.opacity = '1';
                }
                if (elEntrada) {
                    elEntrada.innerText = tEnt;
                    elEntrada.style.opacity = '1';
                }
                if (elCrema) {
                    elCrema.innerText = tCre;
                    elCrema.style.opacity = '1';
                }
                if (elFuerte) {
                    elFuerte.innerText = tFuer;
                    elFuerte.style.opacity = '1';
                }
                if (elPostre) {
                    elPostre.innerText = tPost;
                    elPostre.style.opacity = '1';
                }
            }
        });

        // Lógica del Carrusel Dinámico
        let currentSlide = 0;
        const track = document.getElementById('carouselTrack');
        const dots = document.getElementById('carouselDots').children;
        const totalSlides = track.children.length;

        function updateCarousel() {
            // 🌟 CAMBIA ESTA LÍNEA (Usamos translate3d en lugar de translateX)
            track.style.transform = `translate3d(-${currentSlide * 100}%, 0, 0)`;

            for (let i = 0; i < dots.length; i++) {
                if (i === currentSlide) {
                    dots[i].classList.remove('bg-white/40');
                    dots[i].classList.add('bg-white', 'w-5');
                } else {
                    dots[i].classList.remove('bg-white', 'w-5');
                    dots[i].classList.add('bg-white/40');
                }
            }
        }

        function moveCarousel(direction) {
            currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
            updateCarousel();
        }

        function setSlide(index) {
            currentSlide = index;
            updateCarousel();
        }
        dots[0].classList.add('w-5');

        // ── Drag / Swipe táctil ──
        let startX = 0, isDragging = false, dragDistance = 0;
        const container = track.parentElement;

        container.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
            track.style.transition = 'none';
        }, { passive: true });

        container.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            const currentX = e.touches[0].clientX;
            dragDistance = ((startX - currentX) / container.offsetWidth) * 100;
            track.style.transform = `translate3d(-${(currentSlide * 100) + dragDistance}%, 0, 0)`;
        }, { passive: true });

        container.addEventListener('touchend', () => {
            if (!isDragging) return;
            isDragging = false;
            track.style.transition = '';
            if (Math.abs(dragDistance) > 20) {
                moveCarousel(dragDistance > 0 ? 1 : -1);
            } else {
                updateCarousel();
            }
            dragDistance = 0;
        });

        container.addEventListener('mousedown', (e) => {
            startX = e.clientX;
            isDragging = true;
            dragDistance = 0;
            track.style.transition = 'none';
            e.preventDefault();
        });

        container.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            const currentX = e.clientX;
            dragDistance = ((startX - currentX) / container.offsetWidth) * 100;
            track.style.transform = `translate3d(-${(currentSlide * 100) + dragDistance}%, 0, 0)`;
        });

        container.addEventListener('mouseup', () => {
            if (!isDragging) return;
            isDragging = false;
            track.style.transition = '';
            if (Math.abs(dragDistance) > 20) {
                moveCarousel(dragDistance > 0 ? 1 : -1);
            } else {
                updateCarousel();
            }
            dragDistance = 0;
        });

        container.addEventListener('mouseleave', () => {
            if (!isDragging) return;
            isDragging = false;
            track.style.transition = '';
            updateCarousel();
            dragDistance = 0;
        });

        // Control Dinámico de Pestañas (4 Tiempos Gastronómicos)
        function switchTab(activeTab) {
            const tabs = ['entrada', 'crema', 'fuerte', 'postre'];

            tabs.forEach(tab => {
                const tabElement = document.getElementById(`tab-${tab}`);
                const contentElement = document.getElementById(`content-${tab}`);

                if (tab === activeTab) {
                    tabElement.classList.add('border-b-2', 'border-secrets-gold', 'text-white');
                    tabElement.classList.remove('text-stone-500');
                    contentElement.classList.remove('hidden');
                } else {
                    tabElement.classList.remove('border-b-2', 'border-secrets-gold', 'text-white');
                    tabElement.classList.add('text-stone-500');
                    contentElement.classList.add('hidden');
                }
            });
        }

        function navigateWithAnimation(targetUrl) {
            const body = document.querySelector('body');
            if (body) body.classList.add('page-exit');
            setTimeout(() => {
                window.location.href = targetUrl + (targetUrl.includes('?') ? '&' : '?') + "lang=" + currentLang;
            }, 400);
        }

        function regresarAlPaquete() {
            navigateWithAnimation("{{ route('paquetes.cenas') }}");
        }

        function irAlMapaEspacial() {
            const packageId = "{{ $cena->Id ?? ($cena->id ?? 1) }}";
            const packageSlug = "{{ $cena->Slug ?? ($cena->slug ?? '') }}";
            window.location.href = "{{ route('mapa.mesas') }}?package=" + packageSlug +
                "&package_id=" + packageId + "&lang=" + currentLang;
        }

        // ── Fullscreen Zoom ──
        let fsSlide = 0;
        const fsImgs = [...document.querySelectorAll('#carouselTrack img')].map(i => i.src);
        const fsModal = document.getElementById('fsModal');
        const fsImg = document.getElementById('fsImg');
        const fsCounter = document.getElementById('fsCounter');
        let fsScale = 1, fsPanX = 0, fsPanY = 0, fsPanStartX, fsPanStartY, fsIsPanning = false;

        function abrirFullscreen(index) {
            fsSlide = index; fsScale = 1; fsPanX = 0; fsPanY = 0;
            actualizarFsImg();
            fsModal.classList.remove('hidden');
            fsModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function cerrarFullscreen(e) {
            if (e && e.target !== e.currentTarget) return;
            fsModal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        function moveFsCarousel(dir) {
            if (!fsImgs.length) return;
            fsSlide = (fsSlide + dir + fsImgs.length) % fsImgs.length;
            fsScale = 1; fsPanX = 0; fsPanY = 0;
            actualizarFsImg();
        }

        function actualizarFsImg() {
            if (!fsImgs.length) return;
            fsImg.src = fsImgs[fsSlide];
            fsImg.style.transform = `translate(${fsPanX}px, ${fsPanY}px) scale(${fsScale})`;
            fsCounter.textContent = `${fsSlide + 1} / ${fsImgs.length}`;
        }

        document.addEventListener('dblclick', (e) => {
            if (fsModal.classList.contains('hidden')) return;
            if (!e.target.closest('#fsImg') && !e.target.closest('#fsModal button')) return;
            if (fsScale > 1) {
                fsScale = 1; fsPanX = 0; fsPanY = 0;
            } else {
                fsScale = 2.5;
                const rect = fsImg.getBoundingClientRect();
                const x = (e.clientX - rect.left) / rect.width;
                const y = (e.clientY - rect.top) / rect.height;
                fsPanX = -(x - 0.5) * fsImg.naturalWidth * (fsScale - 1) * 0.3;
                fsPanY = -(y - 0.5) * fsImg.naturalHeight * (fsScale - 1) * 0.3;
            }
            actualizarFsImg();
        });

        document.getElementById('fsWrapper').addEventListener('mousedown', (e) => {
            if (fsScale <= 1) return;
            fsIsPanning = true;
            fsPanStartX = e.clientX - fsPanX;
            fsPanStartY = e.clientY - fsPanY;
            e.currentTarget.style.cursor = 'grabbing';
        });
        window.addEventListener('mousemove', (e) => {
            if (!fsIsPanning) return;
            fsPanX = e.clientX - fsPanStartX;
            fsPanY = e.clientY - fsPanStartY;
            actualizarFsImg();
        });
        window.addEventListener('mouseup', () => { fsIsPanning = false; document.getElementById('fsWrapper').style.cursor = 'grab'; });

        document.getElementById('fsWrapper').addEventListener('touchstart', (e) => {
            if (fsScale <= 1) return;
            const t = e.touches[0];
            fsPanStartX = t.clientX - fsPanX;
            fsPanStartY = t.clientY - fsPanY;
        }, { passive: true });
        document.getElementById('fsWrapper').addEventListener('touchmove', (e) => {
            if (fsScale <= 1) return;
            const t = e.touches[0];
            fsPanX = t.clientX - fsPanStartX;
            fsPanY = t.clientY - fsPanStartY;
            actualizarFsImg();
        }, { passive: true });

        document.addEventListener('keydown', (e) => {
            if (fsModal.classList.contains('hidden')) return;
            if (e.key === 'Escape') cerrarFullscreen(e);
            if (e.key === 'ArrowLeft') moveFsCarousel(-1);
            if (e.key === 'ArrowRight') moveFsCarousel(1);
        });
    </script>

</body>

</html>
