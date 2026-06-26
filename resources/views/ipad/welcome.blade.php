<!DOCTYPE html>
<html lang="{{ request('lang', 'es') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secrets Tulum - Welcome</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap');

        .font-sans {
            font-family: 'Montserrat', sans-serif;
        }

        /* Animación de entrada inicial para toda la pantalla */
        .fade-in {
            animation: fadeInPage 0.8s ease-out forwards;
        }

        /* Clase de salida que se activará por JS */
        .page-exit {
            opacity: 0;
            transform: scale(1.05);
            transition: all 0.5s ease-in-out;
        }

        @keyframes fadeInPage {
            from {
                opacity: 0;
                transform: scale(0.98);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body id="welcome-body"
    class="font-sans h-screen w-screen overflow-hidden flex flex-col justify-between bg-cover bg-center bg-no-repeat fade-in select-none"
    style="background-image: url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=1920'); /* Cambia por la ruta local de tu imagen del lobby */">

    <div class="absolute inset-0 bg-black/45 z-0"></div>

    <div
        translate="no" class="relative z-10 w-full bg-[#A21B54] text-white text-center py-2 text-sm tracking-widest font-medium shadow-md">
        Secrets Tulum
    </div>

    <div class="relative z-10 flex flex-row items-center justify-center gap-24 my-auto">

        <button onclick="selectLanguage('es')"
            class="group flex flex-col items-center focus:outline-none cursor-pointer transform active:scale-95 transition-all">
            <div
                class="w-24 h-24 rounded-full bg-[#A21B54] border-2 border-white/20 flex items-center justify-center shadow-lg shadow-black/40 group-hover:bg-[#bc2364] group-hover:scale-105 transition-all duration-300">
                <span class="text-white font-semibold text-lg tracking-wider">ES</span>
            </div>
            <span
                class="text-white mt-4 font-light text-lg tracking-widest opacity-90 group-hover:opacity-100 group-hover:text-[#D4AF37] transition-all">
                Español
            </span>
        </button>

        <button onclick="selectLanguage('en')"
            class="group flex flex-col items-center focus:outline-none cursor-pointer transform active:scale-95 transition-all">
            <div
                class="w-24 h-24 rounded-full bg-[#A21B54] border-2 border-white/20 flex items-center justify-center shadow-lg shadow-black/40 group-hover:bg-[#bc2364] group-hover:scale-105 transition-all duration-300">
                <span class="text-white font-semibold text-lg tracking-wider">EN</span>
            </div>
            <span
                class="text-white mt-4 font-light text-lg tracking-widest opacity-90 group-hover:opacity-100 group-hover:text-[#D4AF37] transition-all">
                English
            </span>
        </button>

    </div>

    <div class="relative z-10 h-12"></div>

    <script>
        function selectLanguage(lang) {
            // Si eligen inglés, redirigimos o cambiamos el parámetro para que el navegador lo detecte
            if (lang === 'en') {
                document.documentElement.lang = 'en';
                // Alternativamente, puedes redirigirlos a tu catálogo
            } else {
                document.documentElement.lang = 'es';
            }

            const body = document.getElementById('welcome-body');
            body.classList.add('page-exit');

            setTimeout(() => {
                // Pasamos el idioma como parámetro en la URL (?lang=en) para que las siguientes páginas sepan qué idioma tiene
                window.location.href = "{{ route('catalogo') }}?lang=" + lang;
            }, 500);
        }
    </script>
</body>

</html>
