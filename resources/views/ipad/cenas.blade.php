<!DOCTYPE html>
<html lang="{{ request('lang', 'es') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secrets Tulum - Cenas Especiales</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght=300;400;500;600&display=swap');

        :root {
            --font-sans: 'Montserrat', sans-serif;
        }

        body {
            font-family: var(--font-sans);
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

        #cenas-container {
            scrollbar-gutter: stable;
        }
        #cenas-container::-webkit-scrollbar {
            width: 4px;
        }
        #cenas-container::-webkit-scrollbar-track {
            background: transparent;
        }
        #cenas-container::-webkit-scrollbar-thumb {
            background: rgba(197, 160, 89, 0.3);
            border-radius: 10px;
        }
        #cenas-container::-webkit-scrollbar-thumb:hover {
            background: rgba(197, 160, 89, 0.6);
        }
    </style>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/images/icon-192.png">
    <link rel="manifest" href="/manifest.json">
</head>

<body id="cenas-body"
    class="h-screen w-screen overflow-hidden flex flex-col justify-between bg-cover bg-center bg-no-repeat fade-in select-none"
    style="background-image: url('{{ asset('images/Cena.jpeg') }}');">

    <div class="absolute inset-0 bg-black/60 z-0"></div>

    <div
        class="relative z-10 w-full bg-[#C5A059] text-white flex items-center justify-between px-6 py-2 text-sm shadow-md">
        <button onclick="navigateWithAnimation('{{ route('catalogo') }}')"
            class="flex items-center gap-2 opacity-90 hover:opacity-100 transition-all cursor-pointer focus:outline-none">
            <i class="fa-solid fa-chevron-left text-xs"></i>
            <span data-key="back">Ir atrás</span>
        </button>
        <span data-key="top_title" class="tracking-widest font-medium uppercase text-xs md:text-sm">Reservación de Cenas
            Especiales</span>
        <button onclick="navigateWithAnimation('{{ route('welcome') }}')"
            class="flex items-center gap-2 opacity-90 hover:opacity-100 transition-all cursor-pointer focus:outline-none">
            <span data-key="home">Inicio</span>
            <i class="fa-solid fa-house text-xs"></i>
        </button>
    </div>

    <div class="relative z-10 flex flex-row flex-1 h-[calc(100vh-40px)] w-full">

        <div
            class="w-1/4 min-w-[280px] max-w-[340px] bg-black/40 backdrop-blur-md border-r border-white/10 p-5 flex flex-col gap-5 text-white overflow-y-auto no-scrollbar">

            <div class="flex flex-col gap-2">
                <h2 data-key="search_title_label" class="text-xs font-semibold tracking-wider text-white/60 uppercase">
                    Buscar Cena</h2>
                <div class="relative">
                    <input type="text" id="search-input" oninput="applyFilters()"
                        placeholder="Escribe un platillo o nombre..."
                        class="w-full bg-white/5 border border-white/10 rounded px-3 py-1.5 text-xs text-white placeholder-white/30 focus:outline-none focus:border-[#C5A059] transition-all">
                    <i class="fa-solid fa-magnifying-glass absolute right-3 top-2.5 text-xs text-white/30"></i>
                </div>
            </div>

            <hr class="border-white/10">

            <div>
                <h2 data-key="filter_title" class="text-xs font-semibold tracking-wider text-white/60 uppercase mb-3">
                    Restaurantes / Spots</h2>
                <div class="flex flex-col gap-2.5 max-h-[180px] overflow-y-auto pr-1 no-scrollbar"
                    id="restaurant-filters-container">
                    <label class="flex items-center gap-3 cursor-pointer text-xs group">
                        <input type="radio" name="restaurant-filter" value="all" checked onchange="applyFilters()"
                            class="hidden peer">
                        <div
                            class="w-4 h-4 rounded-full border border-[#C5A059] flex items-center justify-center peer-checked:bg-[#C5A059]">
                            <div
                                class="w-1.5 h-1.5 rounded-full bg-black scale-0 peer-checked:scale-100 transition-all">
                            </div>
                        </div>
                        <span data-key="loc_all"
                            class="font-medium text-white group-hover:text-[#C5A059] transition-colors">Todos los
                            restaurantes</span>
                    </label>

                    @foreach (['Blue Water', 'Jasmine', 'Bordeaux', 'The grotto', 'Portofino', 'Gazebo', 'Terraza Willy\'s', 'Rose water', 'La taqueria', 'The Market Cafe'] as $rest)
                        <label
                            class="flex items-center gap-3 cursor-pointer text-xs text-white/60 hover:text-white transition-all group">
                            <input type="radio" name="restaurant-filter" value="{{ $rest }}"
                                onchange="applyFilters()" class="hidden peer">
                            <div
                                class="w-4 h-4 rounded-full border border-white/30 group-hover:border-[#C5A059] flex items-center justify-center peer-checked:border-[#C5A059] peer-checked:bg-[#C5A059]">
                                <div
                                    class="w-1.5 h-1.5 rounded-full bg-black scale-0 peer-checked:scale-100 transition-all">
                                </div>
                            </div>
                            <span class="peer-checked:text-white translate-no">{{ $rest }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <hr class="border-white/10">

            <div class="flex flex-col gap-2">
                <h2 data-key="capacity_title"
                    class="text-[11px] font-bold tracking-[0.2em] text-[#C5A059] uppercase flex items-center gap-2">
                    <i class="fa-solid fa-users text-[10px]"></i> Capacidad de Personas
                </h2>
                <select id="pax-filter" onchange="applyFilters()"
                    class="w-full bg-black/40 border border-white/10 rounded-lg py-2 px-3 text-xs text-white/80 focus:outline-none focus:border-[#C5A059] cursor-pointer transition-all">
                    <option value="all" data-key="pax_all" class="bg-stone-900">Cualquier capacidad</option>
                    <option value="2" data-key="pax_2" class="bg-stone-900">Para 2 Personas</option>
                    <option value="group" data-key="pax_group" class="bg-stone-900">Grupos / Familias</option>
                </select>
            </div>

            <hr class="border-white/10">

            <div class="flex flex-col gap-4">
                <h2 data-key="search_title"
                    class="text-[11px] font-bold tracking-[0.2em] text-[#C5A059] uppercase flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-[10px]"></i> Rango & Preferencias
                </h2>

                <div class="space-y-1.5">
                    <label data-key="range_lbl" class="text-[10px] uppercase tracking-wider text-white/40 block">Filtrar
                        por Rango</label>
                    <select id="price-range-filter" onchange="applyFilters()"
                        class="w-full bg-black/40 border border-white/10 rounded-lg py-2 px-3 text-xs text-white/80 focus:outline-none focus:border-[#C5A059] cursor-pointer transition-all">
                        <option value="all" data-key="price_all" class="bg-stone-900">Cualquier precio</option>
                        <option value="0-2500" data-key="price_low" class="bg-stone-900">Hasta $2,500 MXN</option>
                        <option value="2500-5000" data-key="price_mid" class="bg-stone-900">$2,500 MXN - $5,000 MXN
                        </option>
                        <option value="5000-plus" data-key="price_high" class="bg-stone-900">Más de $5,000 MXN</option>
                    </select>
                </div>
            </div>

        </div>

        <div id="cenas-container"
            class="flex-1 overflow-y-auto">
            <div class="p-6 flex flex-col gap-4 max-w-4xl mx-auto w-full">
                <div class="text-white/40 text-center py-10 tracking-widest text-xs uppercase">
                    <i class="fa-solid fa-circle-notch animate-spin mr-2"></i> <span data-key="loading">Cargando menú...</span>
                </div>
            </div>
        </div>

    </div>

    <script>
        const translations = {
            es: {
                back: "Ir atrás",
                home: "Inicio",
                top_title: "Experiencias Gastronómicas Privadas",
                search_title_label: "Buscar Cena",
                filter_title: "Seleccionar Restaurante",
                loc_all: "Todos los restaurantes",
                capacity_title: "Capacidad de Personas",
                pax_all: "Cualquier capacidad",
                pax_2: "Para 2 Personas",
                pax_group: "Grupos / Familias",
                search_title: "Rango & Preferencias",
                range_lbl: "Filtrar por Rango",
                price_all: "Cualquier precio",
                price_low: "Hasta $2,500 MXN",
                price_mid: "$2,500 MXN - $5,000 MXN",
                price_high: "Más de $5,000 MXN",
                loading: "Cargando menú...",
                tap_view: "Ver Menú",
                no_results: "No se encontraron cenas con esta selección."
            },
            en: {
                back: "Back",
                home: "Home",
                top_title: "Private Dining Experiences",
                search_title_label: "Search Dinner",
                filter_title: "Select Restaurant",
                loc_all: "All Restaurants",
                capacity_title: "People Capacity",
                pax_all: "Any capacity",
                pax_2: "For 2 People",
                pax_group: "Groups / Families",
                search_title: "Range & Preferences",
                range_lbl: "Filter by Range",
                price_all: "Any price",
                price_low: "Up to $2,500 MXN",
                price_mid: "$2,500 MXN - $5,000 MXN",
                price_high: "Over $5,000 MXN",
                loading: "Loading menu...",
                tap_view: "View Menu",
                no_results: "No dinners found with this selection."
            }
        };

        const urlParams = new URLSearchParams(window.location.search);
        const currentLang = urlParams.get('lang') === 'en' ? 'en' : 'es';
        let allCenas = [];

        // Traducir interfaz fija e inputs/placeholders
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

        // Traducir dinámicamente el placeholder del buscador si está en inglés
        if (currentLang === 'en') {
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.placeholder = "Type a dish or name...";
            }
        }

        async function traducirTextoAIngles(textoOriginal) {
            if (!textoOriginal) return '';
            try {
                const response = await fetch(
                    `https://api.mymemory.translated.net/get?q=${encodeURIComponent(textoOriginal)}&langpair=es|en`);
                const data = await response.json();
                return data.responseData?.translatedText || textoOriginal;
            } catch (error) {
                console.error("Error al traducir:", error);
                return textoOriginal;
            }
        }

        function navigateWithAnimation(url) {
            const body = document.getElementById('cenas-body');
            body.classList.add('page-exit');
            setTimeout(() => {
                const targetUrl = new URL(url, window.location.origin);
                if (currentLang === 'en') targetUrl.searchParams.set('lang', 'en');
                window.location.href = targetUrl.toString();
            }, 400);
        }

        // Carga de Datos Segura con extracción de imágenes
        // Carga de Datos Segura y unificada para Cenas Especiales
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById('cenas-container');

            fetch('/api/hotel/catalog')
                .then(response => response.json())
                .then(async res => {
                    if (res.success && res.data && res.data.cenas_especiales) {
                        const directData = res.data.cenas_especiales;

                        // 1. Mapeamos primero los datos base e imágenes de forma síncrona
                        allCenas = directData.map(cena => {
                            let primeraImagen =
                                'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=400'; // Respaldo
                            if (cena.imagenes) {
                                try {
                                    const arrayImg = typeof cena.imagenes === 'string' ? JSON.parse(
                                        cena.imagenes) : cena.imagenes;
                                    if (Array.isArray(arrayImg) && arrayImg.length > 0) {
                                        primeraImagen = arrayImg[0];
                                    }
                                } catch (e) {
                                    if (Array.isArray(cena.imagenes) && cena.imagenes.length > 0) {
                                        primeraImagen = cena.imagenes[0];
                                    }
                                }
                            }

                            return {
                                ...cena,
                                imagen: primeraImagen,
                                renderedNombre: cena.nombre,
                                renderedEntrada: cena.entrada,
                                renderedCrema: cena.crema,
                                renderedPlatoFuerte: cena.plato_fuerte,
                                renderedPostre: cena.postre
                            };
                        });

                        // 2. Si el idioma es inglés, resolvemos las traducciones de forma ordenada antes de renderizar
                        if (currentLang === 'en') {
                            for (let cena of allCenas) {
                                cena.renderedNombre = cena.name || cena.nombre;
                                cena.renderedEntrada = await traducirTextoAIngles(cena.entrada);
                                cena.renderedCrema = await traducirTextoAIngles(cena.crema);
                                cena.renderedPlatoFuerte = await traducirTextoAIngles(cena.plato_fuerte);
                                cena.renderedPostre = await traducirTextoAIngles(cena.postre);
                            }
                        }

                        // 3. Un solo render final con todo listo
                        renderCenas(allCenas);

                    } else {
                        container.innerHTML =
                            `<div class="p-6 flex flex-col gap-4 max-w-4xl mx-auto w-full"><div class="text-white/40 text-center py-10 text-xs uppercase">${translations[currentLang].no_results}</div></div>`;
                    }
                })
                .catch(err => {
                    console.error("Error crítico de catálogo:", err);
                    container.innerHTML =
                        `<div class="p-6 flex flex-col gap-4 max-w-4xl mx-auto w-full"><div class="text-red-400 text-center py-10 text-xs uppercase">${currentLang === 'en' ? 'Error loading catalog data.' : 'Error al cargar los datos del catálogo.'}</div></div>`;
                });
        });

        function renderCenas(cenasFiltradas) {
            const container = document.getElementById('cenas-container');
            if (!container) return;

            if (!cenasFiltradas || cenasFiltradas.length === 0) {
                container.innerHTML =
                    `<div class="p-6 flex flex-col gap-4 max-w-4xl mx-auto w-full"><div class="text-white/40 text-center py-12 tracking-wide text-xs uppercase">${translations[currentLang].no_results}</div></div>`;
                return;
            }

            const t = translations[currentLang];

            const menu_lbl = currentLang === 'en' ? 'Menu' : 'Menú';
            const starter_lbl = currentLang === 'en' ? 'Starter' : 'Entrada';
            const soup_lbl = currentLang === 'en' ? 'Soup/Cream' : 'Crema';
            const main_lbl = currentLang === 'en' ? 'Main Course' : 'Plato Fuerte';
            const dessert_lbl = currentLang === 'en' ? 'Dessert' : 'Postre';
            const location_lbl = currentLang === 'en' ? 'Location' : 'Ubicación';
            const table_lbl = currentLang === 'en' ? 'Capacity' : 'Capacidad';

            let html = '<div class="p-6 flex flex-col gap-4 max-w-4xl mx-auto w-full">';
            cenasFiltradas.forEach(cena => {
                const descripcionMenu =
                    `<strong>${menu_lbl}:</strong> ${starter_lbl}: ${cena.renderedEntrada || ''} • ${soup_lbl}: ${cena.renderedCrema || ''} • ${main_lbl}: ${cena.renderedPlatoFuerte || ''} • ${dessert_lbl}: ${cena.renderedPostre || ''}.`;
                const restauranteStr = cena.restaurant || 'Hotel';

                html += `
            <div onclick="selectCena('${cena.slug}')" class="bg-black/40 hover:bg-black/60 backdrop-blur-md border border-white/10 hover:border-[#C5A059]/40 rounded-xl p-4 sm:p-5 flex flex-row justify-between items-center transition-all duration-300 cursor-pointer shadow-lg transform active:scale-[0.995] group gap-4 sm:gap-6">
                
                <!-- Información Izquierda -->
                <div class="flex-1 text-white">
                    <h3 translate="no" class="text-base sm:text-lg font-medium tracking-wide mb-1.5 group-hover:text-[#C5A059] transition-colors">${cena.renderedNombre}</h3>
                    <p class="text-[11px] sm:text-xs text-white/70 font-light leading-relaxed mb-3 line-clamp-2">${descripcionMenu}</p>
                    
                    <div class="flex flex-col gap-1 text-[10px] sm:text-[11px] text-white/50">
                        <div>• <span>${location_lbl}</span>: <span class="translate-no font-medium text-white/80">${restauranteStr}</span></div>
                        <div>• <span>${table_lbl}</span>: <span class="font-medium text-white/80">${cena.numero_personas} Pax</span></div>
                    </div>
                    
                    <div class="text-lg sm:text-xl font-light text-[#C5A059] mt-3 tracking-wide">$${Number(cena.precio).toLocaleString()} <span class="text-[10px] sm:text-xs text-white/40 font-light">MXN</span></div>
                </div>

                <!-- Contenedor de Imagen de Paquete Derecho -->
                <div class="w-32 h-32 sm:w-44 sm:h-36 md:w-52 md:h-40 bg-stone-900 border border-white/10 rounded-lg shrink-0 overflow-hidden relative group-hover:border-[#C5A059]/40 transition-colors shadow-inner">
                    <img src="${cena.imagen}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90 group-hover:opacity-100" alt="${cena.renderedNombre}">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex items-end justify-center pb-2.5">
                        <span class="text-[9px] sm:text-[10px] uppercase tracking-wider text-white/90 text-center font-medium group-hover:text-[#C5A059] transition-colors drop-shadow-lg flex items-center gap-1.5">
                            ${t.tap_view} 
                            <i class="fa-solid fa-arrow-right text-[9px] opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all duration-300"></i>
                        </span>
                    </div>
                </div>

            </div>
        `;
            });
            html += '</div>';
            container.innerHTML = html;
        }

        function applyFilters() {
            if (!allCenas || allCenas.length === 0) return;

            const searchText = document.getElementById('search-input').value.toLowerCase().trim();
            const selectedRestaurantElement = document.querySelector('input[name="restaurant-filter"]:checked');
            const selectedRestaurant = selectedRestaurantElement ? selectedRestaurantElement.value : 'all';
            const selectedPax = document.getElementById('pax-filter').value;
            const selectedPriceRange = document.getElementById('price-range-filter').value;

            // Filtrado Cruzado Estricto
            let resultados = allCenas.filter(cena => {
                const matchRest = (selectedRestaurant === 'all' || (cena.restaurant && cena.restaurant
                    .toLowerCase() === selectedRestaurant.toLowerCase()));

                let matchPax = true;
                if (selectedPax !== 'all') {
                    if (selectedPax === 'group') {
                        matchPax = (cena.numero_personas >= 3);
                    } else {
                        matchPax = (cena.numero_personas == selectedPax);
                    }
                }

                let matchPrice = true;
                if (selectedPriceRange !== 'all') {
                    const precioCena = parseFloat(cena.precio) || 0;
                    if (selectedPriceRange === '0-2500') matchPrice = (precioCena <= 2500);
                    else if (selectedPriceRange === '2500-5000') matchPrice = (precioCena > 2500 && precioCena <=
                        5000);
                    else if (selectedPriceRange === '5000-plus') matchPrice = (precioCena > 5000);
                }

                const matchText = !searchText ||
                    (cena.renderedNombre && cena.renderedNombre.toLowerCase().includes(searchText)) ||
                    (cena.renderedEntrada && cena.renderedEntrada.toLowerCase().includes(searchText)) ||
                    (cena.renderedPlatoFuerte && cena.renderedPlatoFuerte.toLowerCase().includes(searchText)) ||
                    (cena.restaurant && cena.restaurant.toLowerCase().includes(searchText));

                return matchRest && matchPax && matchPrice && matchText;
            });

            renderCenas(resultados);
        }

        function selectCena(slug) {
            document.getElementById('cenas-body').classList.add('page-exit');
            setTimeout(() => {
                window.location.href = `/hotel/detalle-cena/${slug}?lang=${currentLang}`;
            }, 400);
        }
    </script>
    <script>
    if ('serviceWorker' in navigator) { navigator.serviceWorker.register('/sw.js'); }
    </script>
@include('ipad._back-prevention')
</body>

</html>
