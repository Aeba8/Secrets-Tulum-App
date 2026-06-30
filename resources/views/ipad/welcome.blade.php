<!DOCTYPE html>
<html lang="{{ request('lang', 'es') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secrets Tulum - Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@300;400;500&family=Montserrat:wght@100;200;300;400&display=swap');

        .font-sans {
            font-family: 'Montserrat', sans-serif;
        }
        
        .font-serif-luxury {
            font-family: 'Cinzel', serif;
        }

        /* Animación suave de aparición (Fade & Scale Premium) */
        .fade-in {
            animation: fadeInPage 1.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Animación de salida cinematográfica */
        .page-exit {
            opacity: 0;
            transform: scale(1.01);
            filter: blur(4px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeInPage {
            from {
                opacity: 0;
                transform: scale(1.03);
                filter: blur(10px);
            }
            to {
                opacity: 1;
                transform: scale(1);
                filter: blur(0px);
            }
        }
    </style>
</head>

<body id="welcome-body"
    class="font-sans h-screen w-screen overflow-hidden flex flex-col justify-between bg-black fade-in select-none antialiased"
    style="background-image: url('{{  asset('storage/Secrets Tulum.jpg') }}'); background-size: cover; background-position: center;">

    <!-- Capa de oscurecimiento profundo (Aumentada a un 75% de opacidad para máximo contraste negro/oro) -->
    <div class="absolute inset-0 bg-gradient-to-b from-black/85 via-black/60 to-black/80 z-0"></div>

    <!-- Líneas decorativas ultrafinas para dar aspecto de app de diseño arquitectónico -->
    <div class="absolute inset-x-12 top-0 bottom-0 border-l border-r border-white/[0.02] pointer-events-none z-0"></div>

    <!-- Barra Superior Minimalista -->
    <div translate="no" class="relative z-10 w-full text-center pt-8 text-[12px] tracking-[0.6em] font-light uppercase text-[#D4AF37]/60">
        Secrets Tulum Resort & Beach Club
    </div>

    <!-- Bloque Central: Experiencia Editorial Emocionante -->
    <div class="relative z-10 flex flex-col items-center justify-center text-center my-auto px-6 space-y-16">
        
        <div class="space-y-4">
            <span class="text-[15px] tracking-[0.5em] text-[#D4AF37] font-light uppercase block">
                Welcome • Bienvenido
            </span>
            <h1 class="font-serif-luxury text-5xl md:text-6xl lg:text-7xl font-extralight text-white tracking-[0.2em] leading-tight drop-shadow-2xl">
                SECRETS PAD
            </h1>
            <div class="w-16 h-[1px] bg-gradient-to-r from-transparent via-[#D4AF37]/50 to-transparent mx-auto mt-8"></div>
        </div>

        <!-- NUEVO DISEÑO DE BOTONES: Interfaz Lineal Minimalista de Ultra Lujo -->
        <div class="flex flex-row items-center justify-center h-20 relative px-8 bg-black/10 backdrop-blur-md border border-white/[0.03] rounded-full shadow-2xl">
            
            <!-- Botón ESPAÑOL -->
            <button onclick="selectLanguage('es')"
                class="group flex flex-col items-center justify-center px-10 h-full focus:outline-none cursor-pointer transition-all duration-300 relative">
                <span class="text-white/80 font-light text-[13px] tracking-[0.3em] uppercase group-hover:text-[#D4AF37] group-hover:-translate-y-0.5 transition-all duration-500">
                    Español
                </span>
                <!-- Línea de acento inferior animada -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 w-0 h-[1px] bg-[#D4AF37] group-hover:w-8 transition-all duration-500 opacity-0 group-hover:opacity-100"></div>
            </button>

            <!-- Divisor Central Fino -->
            <div class="w-[1px] h-6 bg-gradient-to-b from-transparent via-white/20 to-transparent"></div>

            <!-- Botón ENGLISH -->
            <button onclick="selectLanguage('en')"
                class="group flex flex-col items-center justify-center px-10 h-full focus:outline-none cursor-pointer transition-all duration-300 relative">
                <span class="text-white/80 font-light text-[13px] tracking-[0.3em] uppercase group-hover:text-[#D4AF37] group-hover:-translate-y-0.5 transition-all duration-500">
                    English
                </span>
                <!-- Línea de acento inferior animada -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 w-0 h-[1px] bg-[#D4AF37] group-hover:w-8 transition-all duration-500 opacity-0 group-hover:opacity-100"></div>
            </button>

        </div>
    </div>

    <!-- Footer de protección de marca -->
    <div class="relative z-10 pb-8 text-center text-[8px] text-stone-600 tracking-[0.4em] uppercase font-light">
        Exclusive Guest Interface
    </div>

    <script>
        function selectLanguage(lang) {
            if (lang === 'en') {
                document.documentElement.lang = 'en';
            } else {
                document.documentElement.lang = 'es';
            }

            const body = document.getElementById('welcome-body');
            body.classList.add('page-exit');

            setTimeout(() => {
                window.location.href = "{{ route('catalogo') }}?lang=" + lang;
            }, 750);
        }
    </script>
</body>

</html>