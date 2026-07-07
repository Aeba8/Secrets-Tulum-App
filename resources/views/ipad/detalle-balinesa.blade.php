@php
    // Dividimos la Ficha_Tecnica original del modelo usando tu separador '|'
    $parts = explode('|', $balinesa->Ficha_Tecnica ?? ($balinesa->ficha_tecnica ?? ''));
    $horario_disponible = isset($parts[0]) && !empty(trim($parts[0])) ? trim($parts[0]) : 'Todos los días';
    $botella_incluida =
        isset($parts[1]) && !empty(trim($parts[1])) ? trim($parts[1]) : '1 botella de Moët & Chandon Brut 750ml';
@endphp

<!DOCTYPE html>
<html lang="{{ request('lang', 'es') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secrets Pad - Detalle del Paquete</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Fondo premium de doble tono: negro absoluto y degradado inmersivo */
        .secrets-bg {
            background-image: linear-gradient(to right, rgba(10, 8, 9, 0.86) 5%, rgba(24, 18, 19, 0.85) 100%), url('{{ asset('storage/Balinesa.jpg') }}');
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

        /* Paleta Dorada Corporativa de Secrets */
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


        /* Animación sutil para cuando el texto traducido aparezca */
        .fade-text {
            transition: opacity 0.4s ease-in-out;
        }
    </style>
</head>

<body class="secrets-bg text-stone-200 font-sans min-h-screen flex flex-col justify-between select-none overflow-hidden">

    <!-- Navbar Superior -->
    <div
        class="bg-[#0C090A] h-14 flex justify-between items-center px-8 shadow-2xl border-b border-[#C5A059]/20 shrink-0 z-20">
        <button onclick="regresarAlPaquete()"
            class="text-stone-300 hover:text-white text-sm font-medium flex items-center space-x-1.5 active:scale-95 transition cursor-pointer">
            <span class="text-xl text-secrets-gold">‹</span>
            <span>{{ request('lang') == 'en' ? 'Back' : 'Ir atrás' }}</span>
        </button>
        <span class="text-secrets-gold text-xs font-semibold tracking-[0.3em] uppercase font-mono">
            {{ request('lang') == 'en' ? 'Exclusive Experience' : 'Experiencia Exclusiva' }}
        </span>
        <!-- Busca este botón en tu código actual y reemplázalo -->
        <button onclick="navigateWithAnimation('{{ route('welcome') }}')"
            class="flex items-center gap-2 opacity-90 hover:opacity-100 transition-all cursor-pointer focus:outline-none text-stone-400 hover:text-white text-sm font-medium tracking-wider">
            <span>{{ request('lang') == 'en' ? 'Home' : 'Inicio' }}</span>
            <i class="fa-solid fa-house text-xs"></i>
        </button>
    </div>

    <!-- Contenedor Principal (Distribución equilibrada 50/50) -->
    <div class="flex-1 w-full max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-12 px-8 items-center py-6 z-10">

        <!-- COLUMNA IZQUIERDA: Carrusel Inmersivo -->
        <div class="md:col-span-6 flex flex-col justify-center items-center w-full relative group">
            <div
                class="w-full aspect-[16/10] overflow-hidden rounded-xl shadow-[0_25px_60px_-10px_rgba(0,0,0,0.95)] border border-white/5 bg-black relative">

                <div id="carouselTrack" class="carousel-track h-full w-full">
                    @forelse($balinesa->imagenes ?? [] as $foto)
                    <div class="min-w-full h-full shrink-0">
                        <img src="{{ $foto }}" class="w-full h-full object-cover" alt="Slide {{ $loop->iteration }}">
                    </div>
                    @empty
                    <div class="min-w-full h-full shrink-0">
                        <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=800"
                            class="w-full h-full object-cover" alt="Slide 1">
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
                    @forelse($balinesa->imagenes ?? [] as $foto)
                    <span class="w-2 h-2 rounded-full {{ $loop->first ? 'bg-white' : 'bg-white/40' }} transition-all duration-300 cursor-pointer"
                        onclick="setSlide({{ $loop->index }})"></span>
                    @empty
                    <span class="w-2 h-2 rounded-full bg-white transition-all duration-300 cursor-pointer"
                        onclick="setSlide(0)"></span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: Detalles del Paquete -->
        <div class="md:col-span-6 flex flex-col justify-center h-full space-y-8 lg:pl-4">

            <div>
                <span class="text-xs tracking-[0.35em] text-secrets-gold font-semibold uppercase block mb-2 font-mono">
                    {{ request('lang') == 'en' ? 'EXPERIENCE PACKAGE' : 'PAQUETE DE EXPERIENCIA' }}
                </span>
                <h1 class="text-4xl lg:text-5xl font-extralight text-white tracking-wide leading-tight font-sans">
                    {{ $balinesa->Nombre }}
                </h1>

                <!-- Grid de Características -->
                <div class="grid grid-cols-2 gap-5 mt-8 border-b border-white/10 pb-6">
                    <!-- Horario -->
                    <div class="bg-white/[0.02] p-3.5 rounded-lg border border-white/5 shadow-2xl">
                        <p class="text-[10px] text-secrets-gold font-medium uppercase tracking-[0.2em] font-mono">
                            {{ request('lang') == 'en' ? 'Schedule:' : 'Horario:' }}
                        </p>
                        <!-- 🌟 Agregamos ID para traducción dinámica -->
                        <p id="txt-horario" class="text-base font-light text-stone-100 mt-1 fade-text">
                            {{ $horario_disponible }}</p>
                    </div>
                    <!-- Capacidad -->
                    <div class="bg-white/[0.02] p-3.5 rounded-lg border border-white/5 shadow-2xl">
                        <p class="text-[10px] text-secrets-gold font-medium uppercase tracking-[0.2em] font-mono">
                            {{ request('lang') == 'en' ? 'Max Capacity:' : 'Capacidad Máxima:' }}</p>
                        <p class="text-base font-light text-stone-100 mt-1">{{ $balinesa->capacidad_maxima }} pax</p>
                    </div>
                    <!-- Botella Incluida -->
                    <div class="col-span-2 bg-white/[0.02] p-3.5 rounded-lg border border-white/5 shadow-2xl">
                        <p class="text-[10px] text-secrets-gold font-medium uppercase tracking-[0.2em] font-mono">
                            {{ request('lang') == 'en' ? 'Included Bottle:' : 'Botella Incluida:' }}
                        </p>
                        <!-- 🌟 Agregamos ID para traducción dinámica -->
                        <p id="txt-botella" class="text-base font-light text-stone-100 mt-1 fade-text">
                            {{ $botella_incluida }}</p>
                    </div>
                </div>
            </div>

            <!-- Menús Interactivos (Tabs) -->
            <div class="flex space-x-10 text-xs uppercase font-bold border-b border-white/5 tracking-[0.25em]">
                <button id="tab-descripcion" onclick="switchTab('descripcion')"
                    class="border-b-2 border-secrets-gold pb-3 text-white transition duration-200 cursor-pointer">
                    {{ request('lang') == 'en' ? 'DESCRIPTION' : 'DESCRIPCIÓN' }}
                </button>
                <button id="tab-alimentos" onclick="switchTab('alimentos')"
                    class="pb-3 text-stone-500 hover:text-stone-300 transition duration-200 cursor-pointer">
                    {{ request('lang') == 'en' ? 'FOOD & BEVERAGES' : 'Alimentos o Bebidas' }}
                </button>
            </div>

            <!-- Descripciones dinámicas -->
            <div
                class="text-base text-stone-300/90 leading-relaxed max-w-xl h-[130px] overflow-y-auto no-scrollbar pr-1">

                <!-- Contenido: Descripción (🌟 Agregamos ID) -->
                <div id="content-descripcion" class="block">
                    <p id="txt-descripcion" class="font-light text-stone-300/90 text-justify tracking-wide fade-text">
                        {{ $balinesa->Descripcion ?? 'Disfruta de una velada mágica en la comodidad de una cama balinesa exclusiva. Déjate envolver por la brisa y el sonido del mar mientras brindas con una selecta botella de champagne y disfrutas de fresas con chocolate. El escenario perfecto para celebrar el amor en total privacidad.' }}
                    </p>
                </div>

                <!-- Contenido: Alimentos o Bebidas (🌟 Agregamos ID) -->
                <div id="content-alimentos" class="hidden">
                    <p id="txt-productos" class="font-light text-stone-300/90 text-justify tracking-wide fade-text">
                        {{ $balinesa->Productos ?? 'Disfruta de una velada mágica en la comodidad de una cama balinesa exclusiva. Déjate envolver por la brisa y el sonido del mar mientras brindas con una selecta botella de champagne y disfrutas de fresas con chocolate. El escenario perfecto para celebrar el amor en total privacidad.' }}
                    </p>
                </div>

            </div>
        </div>
    </div>

    <!-- Cierre Inferior -->
    <div class="border-t border-[#C5A059]/20 p-6 backdrop-blur-xl shrink-0 bg-black/40">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-8">

            <button onclick="irAlMapaEspacial()"
                class="h-12 px-8 bg-secrets-gold hover:bg-[#B38F4B] text-black font-semibold text-[11px] uppercase tracking-[0.25em] rounded-md shadow-2xl transition-all duration-300 transform active:scale-[0.99] cursor-pointer">
                {{ request('lang') == 'en' ? 'RESERVE SPOT' : 'RESERVAR ESPACIO' }}
            </button>

            <div class="text-right">
                <p class="text-4xl font-light text-white tracking-[0.08em]">
                    <span class="text-secrets-gold font-normal">${{ number_format($balinesa->Precio) }}</span>
                    <span class="text-xs font-mono text-stone-500 ml-1">MXN</span>
                </p>
                <p class="text-[9px] text-stone-500 font-medium mt-1 tracking-[0.2em] uppercase font-mono">
                    {{ request('lang') == 'en' ? 'Taxes & service included' : 'Impuestos y servicio incluidos' }}
                </p>
            </div>

        </div>
    </div>

    <!-- Script de Control Operativo -->
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const currentLang = urlParams.get('lang') || 'es';

        // 🌟 FUNCIÓN DE TRADUCCIÓN AUTOMÁTICA EN VIVO
        async function traducirTextoAIngles(textoOriginal) {
            if (!textoOriginal || textoOriginal.trim() === "") return "";
            try {
                const response = await fetch(
                    `https://api.mymemory.translated.net/get?q=${encodeURIComponent(textoOriginal)}&langpair=es|en`);
                const data = await response.json();
                if (data.responseData && data.responseData.translatedText) {
                    return data.responseData.translatedText;
                }
                return textoOriginal;
            } catch (error) {
                console.error("Error al traducir de forma automática:", error);
                return textoOriginal;
            }
        }

        // Ejecutar traducciones al cargar el DOM si está seleccionado 'en'
        document.addEventListener("DOMContentLoaded", async function() {
            if (currentLang === 'en') {
                // Seleccionamos los contenedores de texto de la DB
                const elHorario = document.getElementById('txt-horario');
                const elBotella = document.getElementById('txt-botella');
                const elDescripcion = document.getElementById('txt-descripcion');
                const elProductos = document.getElementById('txt-productos');

                // Aplicamos una opacidad baja temporal para denotar carga fluida
                if (elHorario) elHorario.style.opacity = '0.4';
                if (elBotella) elBotella.style.opacity = '0.4';
                if (elDescripcion) elDescripcion.style.opacity = '0.4';
                if (elProductos) elProductos.style.opacity = '0.4';

                // Realizamos las llamadas asíncronas en paralelo para mayor velocidad
                const [targetHorario, targetBotella, targetDescripcion, targetProductos] = await Promise.all([
                    traducirTextoAIngles(elHorario?.innerText),
                    traducirTextoAIngles(elBotella?.innerText),
                    traducirTextoAIngles(elDescripcion?.innerText),
                    traducirTextoAIngles(elProductos?.innerText)
                ]);

                // Asignamos los textos traducidos y devolvemos la opacidad original
                if (elHorario) {
                    elHorario.innerText = targetHorario;
                    elHorario.style.opacity = '1';
                }
                if (elBotella) {
                    elBotella.innerText = targetBotella;
                    elBotella.style.opacity = '1';
                }
                if (elDescripcion) {
                    elDescripcion.innerText = targetDescripcion;
                    elDescripcion.style.opacity = '1';
                }
                if (elProductos) {
                    elProductos.innerText = targetProductos;
                    elProductos.style.opacity = '1';
                }
            }
        });

        // Lógica del Carrusel Dinámico
        let currentSlide = 0;
        const track = document.getElementById('carouselTrack');
        const dots = document.getElementById('carouselDots').children;
        const totalSlides = track.children.length;

        function updateCarousel() {
            track.style.transform = `translateX(-${currentSlide * 100}%)`;
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

        // Lógica de Pestañas Interactivas
        function switchTab(tab) {
            const tabDesc = document.getElementById('tab-descripcion');
            const tabAlim = document.getElementById('tab-alimentos');
            const contentDesc = document.getElementById('content-descripcion');
            const contentAlim = document.getElementById('content-alimentos');

            if (tab === 'descripcion') {
                tabDesc.classList.add('border-b-2', 'border-secrets-gold', 'text-white');
                tabDesc.classList.remove('text-stone-500');
                tabAlim.classList.remove('border-b-2', 'border-secrets-gold', 'text-white');
                tabAlim.classList.add('text-stone-500');
                contentDesc.classList.remove('hidden');
                contentAlim.classList.add('hidden');
            } else {
                tabAlim.classList.add('border-b-2', 'border-secrets-gold', 'text-white');
                tabAlim.classList.remove('text-stone-500');
                tabDesc.classList.remove('border-b-2', 'border-secrets-gold', 'text-white');
                tabDesc.classList.add('text-stone-500');
                contentAlim.classList.remove('hidden');
                contentDesc.classList.add('hidden');
            }
        }

        // Agrega o edita estas funciones dentro de tus etiquetas <script>
        function navigateWithAnimation(targetUrl) {
            const body = document.querySelector('body');
            if (body) {
                body.classList.add('page-exit');
            }
            setTimeout(() => {
                window.location.href = targetUrl + (targetUrl.includes('?') ? '&' : '?') + "lang=" +
                currentLang;
            }, 400);
        }

        // Opcional: Si quieres que el botón "Ir atrás" también tenga el mismo efecto premium:
        function regresarAlPaquete() {
            navigateWithAnimation("{{ route('paquetes.balinesas') }}");
        }

        function irAlMapaEspacial() {
            window.location.href = "{{ route('mapa.espacios') }}?package={{ $balinesa->slug }}&package_id={{ $balinesa->Id }}";
        }
    </script>

</body>

</html>
