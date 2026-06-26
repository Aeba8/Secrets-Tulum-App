<!DOCTYPE html>
<html lang="{{ request('lang', 'es') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secrets Tulum - Experiencias VIP</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght=300;400;500;600&display=swap');

        .font-sans {
            font-family: 'Montserrat', sans-serif;
        }

        .fade-in {
            animation: fadeInPage 0.6s ease-out forwards;
        }

        .page-exit {
            opacity: 0;
            transform: scale(1.02);
            transition: all 0.4s ease-in-out;
        }

        @keyframes fadeInPage {
            from {
                opacity: 0;
                transform: scale(0.99);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body id="experiencias-body"
    class="font-sans h-screen w-screen overflow-hidden flex flex-col justify-between bg-cover bg-center bg-no-repeat fade-in select-none"
    style="background-image: url('https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=1920');">

    <div class="absolute inset-0 bg-black/60 z-0"></div>

    <div
        class="relative z-10 w-full bg-[#A21B54] text-white flex items-center justify-between px-6 py-2 text-sm shadow-md">
        <button onclick="navigateWithAnimation('{{ route('catalogo') }}')"
            class="flex items-center gap-2 opacity-90 hover:opacity-100 transition-all cursor-pointer focus:outline-none">
            <i class="fa-solid fa-chevron-left text-xs"></i>
            <span data-key="back">Ir atrás</span>
        </button>

        <span data-key="top_title" class="tracking-widest font-medium uppercase text-xs md:text-sm">Reservación de Experiencias VIP</span>

        <button onclick="navigateWithAnimation('{{ route('welcome') }}')"
            class="flex items-center gap-2 opacity-90 hover:opacity-100 transition-all cursor-pointer focus:outline-none">
            <span data-key="home">Inicio</span>
            <i class="fa-solid fa-house text-xs"></i>
        </button>
    </div>

    <div class="relative z-10 flex flex-row flex-1 h-[calc(100vh-40px)] w-full">

        <div
            class="w-1/4 min-w-[260px] max-w-[320px] bg-black/40 backdrop-blur-md border-r border-white/10 p-6 flex flex-col gap-6 text-white">
            <div>
                <h2 data-key="filter_title" class="text-sm font-semibold tracking-wider text-white/60 uppercase mb-4">Tipo de Actividad</h2>
                <div class="flex flex-col gap-3">
                    <label class="flex items-center gap-3 cursor-pointer group text-sm">
                        <div class="w-5 h-5 rounded-full border-2 border-[#A21B54] flex items-center justify-center">
                            <div class="w-2.5 h-2.5 rounded-full bg-[#A21B54]"></div>
                        </div>
                        <span data-key="type_all" class="font-medium text-white">Todas las categorías</span>
                    </label>
                    <label
                        class="flex items-center gap-3 cursor-pointer group text-sm text-white/60 hover:text-white transition-all">
                        <div
                            class="w-5 h-5 rounded-full border-2 border-white/30 group-hover:border-white/60 transition-all">
                        </div>
                        <span data-key="type_wellness">Spa & Bienestar</span>
                    </label>
                    <label
                        class="flex items-center gap-3 cursor-pointer group text-sm text-white/60 hover:text-white transition-all">
                        <div
                            class="w-5 h-5 rounded-full border-2 border-white/30 group-hover:border-white/60 transition-all">
                        </div>
                        <span data-key="type_mixology">Talleres & Mixología</span>
                    </label>
                </div>
            </div>

            <hr class="border-white/10">

            <div class="flex flex-col gap-4">
                <h2 data-key="search_title" class="text-sm font-semibold tracking-wider text-white/60 uppercase">Filtros Avanzados</h2>
                <button
                    class="w-full text-left py-2 px-3 bg-white/5 hover:bg-white/10 rounded border border-white/10 transition-all text-xs flex justify-between items-center text-white/70">
                    <span data-key="filter_duration">Duración de la Actividad</span>
                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </button>
                <button
                    class="w-full text-left py-2 px-3 bg-white/5 hover:bg-white/10 rounded border border-white/10 transition-all text-xs flex justify-between items-center text-white/70">
                    <span data-key="filter_price">Rango de Precio</span>
                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </button>
            </div>
        </div>

        <div id="experiencias-container"
            class="flex-1 p-6 overflow-y-auto no-scrollbar flex flex-col gap-4 max-w-4xl mx-auto w-full">
            <div class="text-white/40 text-center py-10 tracking-widest text-xs uppercase"><i
                    class="fa-solid fa-circle-notch animate-spin mr-2"></i> Cargando experiencias...</div>
        </div>

        <script>
            const translations = {
                es: {
                    back: "Ir atrás",
                    home: "Inicio",
                    top_title: "Reservación de Experiencias VIP",
                    filter_title: "Tipo de Actividad",
                    type_all: "Todas las categorías",
                    type_wellness: "Spa & Bienestar",
                    type_mixology: "Talleres & Mixología",
                    search_title: "Filtros Avanzados",
                    filter_duration: "Duración de la Actividad",
                    filter_price: "Rango de Precio",
                    loading: "Cargando experiencias...",
                    tap_view: "Tocar para ver",
                    duration_lbl: "Duración",
                    place_lbl: "Lugar",
                    default_place: "Instalaciones del Hotel"
                },
                en: {
                    back: "Back",
                    home: "Home",
                    top_title: "VIP Experiences Reservation",
                    filter_title: "Activity Type",
                    type_all: "All Categories",
                    type_wellness: "Spa & Wellness",
                    type_mixology: "Workshops & Mixology",
                    search_title: "Advanced Filters",
                    filter_duration: "Activity Duration",
                    filter_price: "Price Range",
                    loading: "Loading experiences...",
                    tap_view: "Tap to view",
                    duration_lbl: "Duration",
                    place_lbl: "Location",
                    default_place: "Hotel Facilities"
                }
            };

            const urlParams = new URLSearchParams(window.location.search);
            const currentLang = urlParams.get('lang') === 'en' ? 'en' : 'es';

            // Traducir textos estáticos
            document.querySelectorAll('[data-key]').forEach(element => {
                const key = element.getAttribute('data-key');
                if (translations[currentLang] && translations[currentLang][key]) {
                    const textSpan = element.querySelector('span');
                    if (textSpan) {
                        textSpan.innerText = translations[currentLang][key];
                    } else {
                        element.innerText = translations[currentLang][key];
                    }
                }
            });

            // Función de traducción automática
            async function traducirTextoAIngles(textoOriginal) {
                if (!textoOriginal) return '';
                try {
                    const response = await fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(textoOriginal)}&langpair=es|en`);
                    const data = await response.json();
                    if (data.responseData && data.responseData.translatedText) {
                        return data.responseData.translatedText;
                    }
                    return textoOriginal;
                } catch (error) {
                    console.error("Error al traducir automáticamente:", error);
                    return textoOriginal;
                }
            }

            document.addEventListener("DOMContentLoaded", function() {
                const container = document.getElementById('experiencias-container');
                container.innerHTML = `<div class="text-white/40 text-center py-10 tracking-widest text-xs uppercase"><i class="fa-solid fa-circle-notch animate-spin mr-2"></i> ${translations[currentLang].loading}</div>`;

                fetch('/api/hotel/catalog')
                    .then(response => response.json())
                    .then(async res => {
                        if (res.success) {
                            container.innerHTML = '';

                            for (const exp of res.data.experiencias) {
                                const nombre = currentLang === 'en' ? (exp.name || exp.nombre) : exp.nombre;
                                const icono = exp.tipo === 'Spa & Bienestar' ? 'fa-spa' : 'fa-martini-glass-citrus';
                                
                                // Campos originales en español de la BD
                                let descripcion = exp.ficha_tecnica || exp.Descripcion || '';
                                let duracion = exp.duracion || '';
                                let lugar = exp.lugar || translations[currentLang].default_place;

                                // Traducción en vivo si está en inglés
                                if (currentLang === 'en') {
                                    descripcion = await traducirTextoAIngles(descripcion);
                                    duracion = await traducirTextoAIngles(duracion);
                                    if(exp.lugar) {
                                        lugar = await traducirTextoAIngles(lugar);
                                    }
                                }

                                const t = translations[currentLang];

                                const tarjeta = `
                                    <div onclick="selectExperiencia('${exp.slug}')" class="bg-black/60 hover:bg-black/70 backdrop-blur-sm border border-white/10 hover:border-[#A21B54]/50 rounded-xl p-5 flex flex-row justify-between items-center transition-all duration-300 cursor-pointer shadow-lg transform active:scale-[0.99] group">
                                        <div class="flex-1 pr-6 text-white">
                                            <h3 translate="no" class="text-lg font-semibold tracking-wide mb-2 group-hover:text-[#D4AF37] transition-colors">${nombre}</h3>
                                            <p class="text-xs text-white/70 font-light leading-relaxed mb-3">${descripcion}</p>
                                            <div class="flex flex-col gap-1 text-[11px] text-white/60">
                                                <div>• <span>${t.duration_lbl}</span>: <span>${duracion}</span></div>
                                                <div>• <span>${t.place_lbl}</span>: <span>${lugar}</span></div>
                                            </div>
                                            <div class="text-xl font-semibold text-[#D4AF37] mt-3 tracking-wide">$${Number(exp.precio).toLocaleString()} <span class="text-xs text-white/50 font-light">MXN</span></div>
                                        </div>
                                        <div class="w-24 h-24 bg-white/5 border border-white/10 rounded-lg flex flex-col justify-center items-center gap-2 p-2 shrink-0">
                                            <i class="fa-solid ${icono} text-2xl text-[#D4AF37]"></i>
                                            <span class="text-[9px] uppercase tracking-wider text-white/40 text-center font-medium group-hover:text-white transition-colors">${t.tap_view}</span>
                                        </div>
                                    </div>
                                `;
                                container.innerHTML += tarjeta;
                            }
                        }
                    })
                    .catch(err => {
                        console.error("Error al obtener experiencias:", err);
                        container.innerHTML = `<div class="text-red-400 text-center py-10 text-xs uppercase">${currentLang === 'en' ? 'Error loading data.' : 'Error al cargar los datos.'}</div>`;
                    });
            });

            function navigateWithAnimation(url) {
                const body = document.getElementById('experiencias-body');
                body.classList.add('page-exit');
                setTimeout(() => {
                    window.location.href = url + '?lang=' + currentLang;
                }, 400);
            }

            function selectExperiencia(slug) {
                const body = document.getElementById('experiencias-body');
                body.classList.add('page-exit');
                setTimeout(() => {
                    window.location.href = "{{ route('catalogo') }}?experience=" + slug + "&lang=" + currentLang;
                }, 400);
            }
        </script>
</body>

</html>