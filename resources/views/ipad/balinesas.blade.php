<!DOCTYPE html>
<html lang="{{ request('lang', 'es') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secrets Tulum - Camas Balinesas</title>
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

<body id="balinesas-body"
    class="font-sans h-screen w-screen overflow-hidden flex flex-col justify-between bg-cover bg-center bg-no-repeat fade-in select-none"
    style="background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1920');">

    <div class="absolute inset-0 bg-black/55 z-0"></div>

    <div
        class="relative z-10 w-full bg-[#A21B54] text-white flex items-center justify-between px-6 py-2 text-sm shadow-md">
        <button onclick="navigateWithAnimation('{{ route('catalogo') }}')"
            class="flex items-center gap-2 opacity-90 hover:opacity-100 transition-all cursor-pointer focus:outline-none">
            <i class="fa-solid fa-chevron-left text-xs"></i>
            <span data-key="back">Ir atrás</span>
        </button>

        <span data-key="top_title" class="tracking-widest font-medium uppercase text-xs md:text-sm">Reservación de Camas Balinesas</span>

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
                <h2 data-key="filter_title" class="text-sm font-semibold tracking-wider text-white/60 uppercase mb-4">Ubicación</h2>
                <div class="flex flex-col gap-3">
                    <label class="flex items-center gap-3 cursor-pointer group text-sm">
                        <div class="w-5 h-5 rounded-full border-2 border-[#A21B54] flex items-center justify-center">
                            <div class="w-2.5 h-2.5 rounded-full bg-[#A21B54]"></div>
                        </div>
                        <span data-key="loc_all" class="font-medium text-white">Todas las zonas</span>
                    </label>
                    <label
                        class="flex items-center gap-3 cursor-pointer group text-sm text-white/60 hover:text-white transition-all">
                        <div
                            class="w-5 h-5 rounded-full border-2 border-white/30 group-hover:border-white/60 transition-all">
                        </div>
                        <span data-key="loc_beach">Frente a la Playa</span>
                    </label>
                    <label
                        class="flex items-center gap-3 cursor-pointer group text-sm text-white/60 hover:text-white transition-all">
                        <div
                            class="w-5 h-5 rounded-full border-2 border-white/30 group-hover:border-white/60 transition-all">
                        </div>
                        <span data-key="loc_pool">Zona de Piscina</span>
                    </label>
                </div>
            </div>

            <hr class="border-white/10">

            <div class="flex flex-col gap-4">
                <h2 data-key="search_title" class="text-sm font-semibold tracking-wider text-white/60 uppercase">Preferencias</h2>
                <button
                    class="w-full text-left py-2 px-3 bg-white/5 hover:bg-white/10 rounded border border-white/10 transition-all text-xs flex justify-between items-center text-white/70">
                    <span data-key="filter_amenity">Inclusiones (Champagne / Frutas)</span>
                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </button>
                <button
                    class="w-full text-left py-2 px-3 bg-white/5 hover:bg-white/10 rounded border border-white/10 transition-all text-xs flex justify-between items-center text-white/70">
                    <span data-key="filter_price">Rango de Precio</span>
                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </button>
            </div>
        </div>

        <div id="balinesas-container"
            class="flex-1 p-6 overflow-y-auto no-scrollbar flex flex-col gap-4 max-w-4xl mx-auto w-full">
            <div class="text-white/40 text-center py-10 tracking-widest text-xs uppercase">
                <i class="fa-solid fa-circle-notch animate-spin mr-2"></i> Cargando catálogo...
            </div>
        </div>
    </div>

    <script>
    const translations = {
        es: {
            back: "Ir atrás",
            home: "Inicio",
            top_title: "Reservación de Camas Balinesas",
            filter_title: "Ubicación",
            loc_all: "Todas las zonas",
            loc_beach: "Frente a la Playa",
            loc_pool: "Zona de Piscina",
            search_title: "Preferencias",
            filter_amenity: "Inclusiones (Champagne / Frutas)",
            filter_price: "Rango de Precio",
            loading: "Cargando catálogo...",
            tap_view: "Tocar para ver"
        },
        en: {
            back: "Back",
            home: "Home",
            top_title: "Bali Beds Reservation",
            filter_title: "Location",
            loc_all: "All Zones",
            loc_beach: "Beachfront",
            loc_pool: "Pool Area",
            search_title: "Preferences",
            filter_amenity: "Inclusions (Champagne / Fruits)",
            filter_price: "Price Range",
            loading: "Loading catalog...",
            tap_view: "Tap to view"
        }
    };

    const urlParams = new URLSearchParams(window.location.search);
    const currentLang = urlParams.get('lang') === 'en' ? 'en' : 'es';

    // Traducir los elementos estáticos con data-key
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

    // 🌟 FUNCIÓN AUXILIAR DE TRADUCCIÓN AUTOMÁTICA Y GRATUITA
    async function traducirTextoAIngles(textoOriginal) {
        try {
            // Utilizamos la API pública y gratuita de MyMemory para traducir de español (es) a inglés (en)
            const response = await fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(textoOriginal)}&langpair=es|en`);
            const data = await response.json();
            if (data.responseData && data.responseData.translatedText) {
                return data.responseData.translatedText;
            }
            return textoOriginal;
        } catch (error) {
            console.error("Error en la traducción automática:", error);
            return textoOriginal; // Si falla la red, regresa el texto original en español
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const loadingContainer = document.getElementById('balinesas-container');
        loadingContainer.innerHTML = `<div class="text-white/40 text-center py-10 tracking-widest text-xs uppercase"><i class="fa-solid fa-circle-notch animate-spin mr-2"></i> ${translations[currentLang].loading}</div>`;

        fetch('/api/hotel/catalog')
            .then(response => response.json())
            .then(async res => { // 🌟 Convertimos a async para poder esperar la traducción automática
                if (res.success) {
                    loadingContainer.innerHTML = '';

                    // Usamos un bucle for...of para procesar las traducciones en orden
                    for (const balinesa of res.data.balinesas) {
                        
                        // El título siempre se queda fiel al nombre de la Base de Datos en español
                        const nombre = balinesa.nombre || balinesa.Nombre; 
                        
                        // Extraemos la descripción original en español que viene de la base de datos
                        let descripcion = balinesa.ficha_tecnica || balinesa.Descripcion;

                        // 🌟 TRADUCCIÓN AUTOMÁTICA AL VUELO: Si el idioma seleccionado es inglés, se traduce en el momento
                        if (currentLang === 'en' && descripcion) {
                            descripcion = await traducirTextoAIngles(descripcion);
                        }

                        const tarjeta = `
                            <div onclick="selectBalinesa('${balinesa.slug}')" class="bg-black/60 hover:bg-black/70 backdrop-blur-sm border border-white/10 hover:border-[#A21B54]/50 rounded-xl p-5 flex flex-row justify-between items-center transition-all duration-300 cursor-pointer shadow-lg transform active:scale-[0.99] group">
                                <div class="flex-1 pr-6 text-white">
                                    <h3 translate="no" class="text-lg font-semibold tracking-wide mb-2 group-hover:text-[#D4AF37] transition-colors">${nombre}</h3>
                                    <p class="text-xs text-white/70 font-light leading-relaxed mb-3">${descripcion}</p>
                                    <div class="flex flex-col gap-1 text-[11px] text-white/60">
                                        <div>• <span>${currentLang === 'en' ? 'Availability' : 'Disponibilidad'}</span>: <span class="text-emerald-400 font-medium">${currentLang === 'en' ? 'Immediate' : 'Inmediata'}</span></div>
                                        <div>• <span>${currentLang === 'en' ? 'Max Capacity' : 'Capacidad Máxima'}</span>: <span>${balinesa.capacidad_maxima || balinesa.Capacidad_Maxima || 4} Pax</span></div>
                                    </div>
                                    <div class="text-xl font-semibold text-[#D4AF37] mt-3 tracking-wide">$${Number(balinesa.precio || balinesa.Precio).toLocaleString()} <span class="text-xs text-white/50 font-light">MXN</span></div>
                                </div>
                                <div class="w-24 h-24 bg-white/5 border border-white/10 rounded-lg flex flex-col justify-center items-center gap-2 p-2 shrink-0">
                                    <i class="fa-solid fa-umbrella-beach text-2xl text-[#D4AF37]"></i>
                                    <span class="text-[9px] uppercase tracking-wider text-white/40 text-center font-medium group-hover:text-white transition-colors">${translations[currentLang].tap_view}</span>
                                </div>
                            </div>
                        `;
                        loadingContainer.innerHTML += tarjeta;
                    }
                }
            })
            .catch(err => {
                console.error("Error al obtener catálogo:", err);
                loadingContainer.innerHTML = `<div class="text-red-400 text-center py-10 text-xs uppercase">${currentLang === 'en' ? 'Error loading data.' : 'Error al cargar los datos.'}</div>`;
            });
    });

    function navigateWithAnimation(targetUrl) {
        const body = document.getElementById('balinesas-body');
        body.classList.add('page-exit');
        setTimeout(() => {
            window.location.href = targetUrl + "?lang=" + currentLang;
        }, 400);
    }

    function selectBalinesa(packageId) {
        const body = document.getElementById('balinesas-body');
        body.classList.add('page-exit');
        setTimeout(() => {
            window.location.href = "{{ route('mapa.espacios') }}?package=" + packageId + "&lang=" + currentLang;
        }, 400);
    }
</script>
</body>

</html>