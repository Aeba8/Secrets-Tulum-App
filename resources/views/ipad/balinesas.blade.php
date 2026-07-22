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

        #balinesas-container {
            scrollbar-gutter: stable;
        }
        #balinesas-container::-webkit-scrollbar {
            width: 4px;
        }
        #balinesas-container::-webkit-scrollbar-track {
            background: transparent;
        }
        #balinesas-container::-webkit-scrollbar-thumb {
            background: rgba(197, 160, 89, 0.3);
            border-radius: 10px;
        }
        #balinesas-container::-webkit-scrollbar-thumb:hover {
            background: rgba(197, 160, 89, 0.6);
        }
    </style>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/images/icon-192.png">
    <link rel="manifest" href="/manifest.json">
</head>

<body id="balinesas-body"
    class="font-sans h-screen w-screen overflow-hidden flex flex-col justify-between bg-cover bg-center bg-no-repeat fade-in select-none"
    style="background-image: url('{{ asset('images/Balinesa.jpg') }}');">

    <div class="absolute inset-0 bg-black/60 z-0"></div>

    <!-- Top Navbar -->
    <div
        class="relative z-10 w-full bg-[#C5A059] text-[#2C1A11] flex items-center justify-between px-6 py-2 text-sm shadow-md">
        <button onclick="navigateWithAnimation('{{ route('catalogo') }}')"
            class="flex items-center gap-2 opacity-90 hover:opacity-100 transition-all cursor-pointer focus:outline-none font-medium">
            <i class="fa-solid fa-chevron-left text-xs"></i>
            <span data-key="back">Ir atrás</span>
        </button>

        <span data-key="top_title" class="tracking-[0.2em] font-medium uppercase text-xs md:text-sm">Reservación de
            Camas Balinesas</span>

        <button onclick="navigateWithAnimation('{{ route('welcome') }}')"
            class="flex items-center gap-2 opacity-90 hover:opacity-100 transition-all cursor-pointer focus:outline-none font-medium">
            <span data-key="home">Inicio</span>
            <i class="fa-solid fa-house text-xs"></i>
        </button>
    </div>

    <!-- Main Workspace -->
    <div class="relative z-10 flex flex-row flex-1 h-[calc(100vh-40px)] w-full">

        <!-- SIDEBAR DE FILTROS AVANZADOS -->
        <div
            class="w-1/4 min-w-[280px] max-w-[340px] bg-black/50 backdrop-blur-xl border-r border-white/10 p-5 flex flex-col gap-5 text-white overflow-y-auto no-scrollbar shadow-2xl">

            <!-- Buscador por Nombre de Paquete -->
            <div class="flex flex-col gap-2">
                <h2 data-key="search_lbl"
                    class="text-[11px] font-bold tracking-[0.2em] text-[#C5A059] uppercase flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass text-[10px]"></i> Buscar Paquete
                </h2>
                <input type="text" id="search-input" oninput="applyFiltersAndSorting()"
                    placeholder="Escribe un paquete o bebida..."
                    class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-xs text-white placeholder-white/30 focus:outline-none focus:border-[#C5A059] transition-all">
            </div>

            <hr class="border-white/10">

            <!-- Inclusiones (Bebida) -->
            <div>
                <h2 data-key="filter_title"
                    class="text-[11px] font-bold tracking-[0.2em] text-[#C5A059] uppercase mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-wine-bottle text-[10px]"></i> Inclusiones (Bebida)
                </h2>

                <div class="flex flex-col gap-2">
                    <button onclick="filterByInclusion('all')" id="inc-all"
                        class="inclusion-filter-btn flex items-center justify-between w-full p-2.5 rounded-lg border border-white/5 bg-white/5 text-white text-xs transition-all duration-300 cursor-pointer">
                        <span data-key="inc_all" class="font-medium">Cualquier paquete</span>
                        <div
                            class="w-3.5 h-3.5 rounded-full border-2 border-[#C5A059] flex items-center justify-center p-0.5">
                            <div id="radio-all" class="w-full h-full rounded-full bg-[#C5A059]"></div>
                        </div>
                    </button>

                    <button onclick="filterByInclusion('champagne')" id="inc-champagne"
                        class="inclusion-filter-btn flex items-center justify-between w-full p-2.5 rounded-lg border border-white/5 bg-transparent text-white/60 text-xs hover:text-white hover:bg-white/[0.02] transition-all duration-300 cursor-pointer">
                        <span data-key="inc_champagne">Con Champagne / Moet</span>
                        <div
                            class="w-3.5 h-3.5 rounded-full border border-white/30 flex items-center justify-center p-0.5">
                            <div id="radio-champagne" class="w-full h-full rounded-full bg-transparent"></div>
                        </div>
                    </button>

                    <button onclick="filterByInclusion('premium')" id="inc-premium"
                        class="inclusion-filter-btn flex items-center justify-between w-full p-2.5 rounded-lg border border-white/5 bg-transparent text-white/60 text-xs hover:text-white hover:bg-white/[0.02] transition-all duration-300 cursor-pointer">
                        <span data-key="inc_premium">Licores y Premium Botellas</span>
                        <div
                            class="w-3.5 h-3.5 rounded-full border border-white/30 flex items-center justify-center p-0.5">
                            <div id="radio-premium" class="w-full h-full rounded-full bg-transparent"></div>
                        </div>
                    </button>
                </div>
            </div>

            <hr class="border-white/10">

            <!-- Filtro por Número de Personas (Capacidad) -->
            <div class="flex flex-col gap-2">
                <h2 data-key="capacity_title"
                    class="text-[11px] font-bold tracking-[0.2em] text-[#C5A059] uppercase flex items-center gap-2">
                    <i class="fa-solid fa-users text-[10px]"></i> Capacidad de Personas
                </h2>
                <select id="pax-filter" onchange="applyFiltersAndSorting()"
                    class="w-full bg-black/40 border border-white/10 rounded-lg py-2 px-3 text-xs text-white/80 focus:outline-none focus:border-[#C5A059] cursor-pointer transition-all">
                    <option value="all" data-key="pax_all" class="bg-stone-900">Cualquier capacidad</option>
                    <option value="2" data-key="pax_up_to_2" class="bg-stone-900">Hasta 2 Personas</option>
                    <option value="4" data-key="pax_up_to_4" class="bg-stone-900">Hasta 4 Personas</option>
                    <option value="6" data-key="pax_6_or_more" class="bg-stone-900">6 o más Personas</option>
                </select>
            </div>

            <hr class="border-white/10">

            <!-- Rangos y Ordenamiento de Precios -->
            <div class="flex flex-col gap-4">
                <h2 data-key="search_title"
                    class="text-[11px] font-bold tracking-[0.2em] text-[#C5A059] uppercase flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-[10px]"></i> Rango & Preferencias
                </h2>

                <!-- Rango de Precios -->
                <div class="space-y-1.5">
                    <label data-key="range_lbl" class="text-[10px] uppercase tracking-wider text-white/40 block">Filtrar
                        por Rango</label>
                    <select id="price-range-filter" onchange="applyFiltersAndSorting()"
                        class="w-full bg-black/40 border border-white/10 rounded-lg py-2 px-3 text-xs text-white/80 focus:outline-none focus:border-[#C5A059] cursor-pointer transition-all">
                        <option value="all" data-key="price_all" class="bg-stone-900">Cualquier precio</option>
                        <option value="0-3000" data-key="price_up_to_3k" class="bg-stone-900">Hasta $3,000 MXN</option>
                        <option value="3000-6000" data-key="price_3k_to_6k" class="bg-stone-900">$3,000 MXN - $6,000 MXN
                        </option>
                        <option value="6000-plus" data-key="price_over_6k" class="bg-stone-900">Más de $6,000 MXN
                        </option>
                    </select>
                </div>

                <!-- Sentido de Ordenamiento -->
                <div class="space-y-1.5">
                    <label data-key="sort_lbl"
                        class="text-[10px] uppercase tracking-wider text-white/40 block">Ordenar por Precio</label>
                    <select id="price-sort" onchange="sortCatalog()"
                        class="w-full bg-black/40 border border-white/10 rounded-lg py-2 px-3 text-xs text-white/80 focus:outline-none focus:border-[#C5A059] cursor-pointer transition-all">
                        <option value="default" data-key="sort_default" class="bg-stone-900">Predeterminado</option>
                        <option value="low-high" data-key="sort_asc" class="bg-stone-900">📈 Menor a Mayor precio
                        </option>
                        <option value="high-low" data-key="sort_desc" class="bg-stone-900">📉 Mayor a Menor precio
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <!-- CONTENEDOR PRINCIPAL DE TARJETAS -->
        <div id="balinesas-container"
            class="flex-1 overflow-y-auto">
            <div class="p-6 flex flex-col gap-4 max-w-4xl mx-auto w-full">
                <div class="text-white/40 text-center py-10 tracking-widest text-xs uppercase">
                    <i class="fa-solid fa-circle-notch animate-spin mr-2"></i> <span data-key="loading">Cargando catálogo...</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        const translations = {
            es: {
                back: "Ir atrás",
                home: "Inicio",
                top_title: "Reservación de Camas Balinesas",
                filter_title: "Inclusiones (Bebida)",
                inc_all: "Cualquier paquete",
                inc_champagne: "Con Champagne / Moet",
                inc_premium: "Licores y Premium Botellas",
                search_lbl: "Buscar Paquete",
                capacity_title: "Capacidad de Personas",
                pax_all: "Cualquier capacidad",
                search_title: "Rango & Preferencias",
                range_lbl: "Filtrar por Rango",
                price_all: "Cualquier precio",
                sort_lbl: "Ordenar por Precio",
                sort_default: "Predeterminado",
                sort_asc: "📈 Menor a Mayor precio",
                sort_desc: "📉 Mayor a Menor precio",
                loading: "Cargando catálogo...",
                tap_view: "Tocar para ver",
                no_results: "No se encontraron paquetes con esta selección.",
                pax_up_to_2: "Hasta 2 Personas",
                pax_up_to_4: "Hasta 4 Personas",
                pax_6_or_more: "6 o más Personas",
                price_up_to_3k: "Hasta $3,000 MXN",
                price_3k_to_6k: "$3,000 MXN - $6,000 MXN",
                price_over_6k: "Más de $6,000 MXN"
            },
            en: {
                back: "Back",
                home: "Home",
                top_title: "Bali Beds Reservation",
                filter_title: "Inclusions (Beverage)",
                inc_all: "Any Package",
                inc_champagne: "With Champagne / Moet",
                inc_premium: "Liquors & Premium Bottles",
                search_lbl: "Search Package",
                capacity_title: "People Capacity",
                pax_all: "Any capacity",
                search_title: "Range & Preferences",
                range_lbl: "Filter by Range",
                price_all: "Any price",
                sort_lbl: "Sort by Price",
                sort_default: "Default",
                sort_asc: "📈 Lowest to Highest price",
                sort_desc: "📉 Highest to Lowest price",
                loading: "Loading catalog...",
                tap_view: "Tap to view",
                no_results: "No packages found with this selection.",
                pax_up_to_2: "Up to 2 People",
                pax_up_to_4: "Up to 4 People",
                pax_6_or_more: "6 or more People",
                price_up_to_3k: "Up to $3,000 MXN",
                price_3k_to_6k: "$3,000 MXN - $6,000 MXN",
                price_over_6k: "Over $5,000 MXN"
            }
        };

        const urlParams = new URLSearchParams(window.location.search);
        const currentLang = urlParams.get('lang') === 'en' ? 'en' : 'es';

        let allBalinesas = [];
        let currentFilterInclusion = 'all';

        // Traducir elementos estáticos
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

        // TRADUCCIÓN DINÁMICA DEL PLACEHOLDER
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.placeholder = currentLang === 'en' ? "Type a package or beverage..." :
                "Escribe un paquete o bebida...";
        }

        async function traducirTextoAIngles(textoOriginal) {
            if (!textoOriginal || textoOriginal.trim() === "") return "";
            try {
                const response = await fetch(
                    `https://api.mymemory.translated.net/get?q=${encodeURIComponent(textoOriginal)}&langpair=es|en`);
                const data = await response.json();
                return data.responseData?.translatedText || textoOriginal;
            } catch (error) {
                console.error("Error en la traducción automática:", error);
                return textoOriginal;
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            fetch('/api/hotel/catalog')
                .then(response => response.json())
                .then(async res => {
                    if (res.success) {
                        allBalinesas = await Promise.all(res.data.balinesas.map(async (balinesa) => {
                            const nombre = balinesa.nombre || balinesa.Nombre;

                            // Mapeo estricto de los campos que vienen de tu Base de Datos / Resource corregido
                            const dbHorario = balinesa.dias || balinesa.Dias || '';
                            const dbBotella = balinesa.ficha_tecnica || balinesa.Ficha_Tecnica || '';

                            // 🌟 EXTRAER LA PRIMERA IMAGEN DE FORMA SEGURA
                            let primeraImagen = 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=300'; // Imagen de respaldo si no hay
                            if (balinesa.imagenes) {
                                try {
                                    // Por si viene como string JSON desde el Resource de la API
                                    const arrayImg = typeof balinesa.imagenes === 'string' ? JSON.parse(balinesa.imagenes) : balinesa.imagenes;
                                    if (Array.isArray(arrayImg) && arrayImg.length > 0) {
                                        primeraImagen = arrayImg[0];
                                    }
                                } catch (e) {
                                    // Si ya es un array nativo o falla el parse, intentamos asignarlo directo
                                    if (Array.isArray(balinesa.imagenes) && balinesa.imagenes.length > 0) {
                                        primeraImagen = balinesa.imagenes[0];
                                    }
                                }
                            }

                            let horarioDisponible = dbHorario.trim() !== '' ?
                                dbHorario.trim() :
                                (currentLang === 'en' ? 'Every day' : 'Todos los días');

                            let botellaIncluida = dbBotella.trim() !== '' ?
                                dbBotella.trim() :
                                (currentLang === 'en' ? 'Premium bottle included' : 'Botella premium incluida');

                            const botellaOriginalEspañol = dbBotella.toLowerCase();

                            if (currentLang === 'en') {
                                const [horarioTraducido, botellaTraducida] = await Promise.all([
                                    traducirTextoAIngles(horarioDisponible),
                                    traducirTextoAIngles(botellaIncluida)
                                ]);
                                horarioDisponible = horarioTraducido;
                                botellaIncluida = botellaTraducida;
                            }

                            return {
                                slug: balinesa.slug,
                                nombre: nombre,
                                botellaIncluida: botellaIncluida,
                                botellaRaw: botellaOriginalEspañol,
                                horarioDisponible: horarioDisponible,
                                capacidad_maxima: balinesa.capacidad_maxima || balinesa.Capacidad_Maxima || 2,
                                precio: Number(balinesa.precio || balinesa.Precio),
                                imagen: primeraImagen // 🌟 Pasamos la URL limpia lista para el render
                            };
                        }));                                   

                        // Renderizar el catálogo original
                        renderCatalog(allBalinesas);
                    }
                })
                .catch(err => {
                    console.error("Error al obtener catálogo:", err);
                    document.getElementById('balinesas-container').innerHTML =
                        `<div class="p-6 flex flex-col gap-4 max-w-4xl mx-auto w-full"><div class="text-red-400 text-center py-10 text-xs uppercase">${currentLang === 'en' ? 'Error loading data.' : 'Error al cargar los datos.'}</div></div>`;
                });
        });

        function filterByInclusion(type) {
            currentFilterInclusion = type;

            document.querySelectorAll('.inclusion-filter-btn').forEach(btn => {
                btn.classList.remove('bg-white/5', 'text-white');
                btn.classList.add('bg-transparent', 'text-white/60');
            });
            document.querySelectorAll('.inclusion-filter-btn div div').forEach(radio => {
                radio.classList.remove('bg-[#C5A059]');
                radio.classList.add('bg-transparent');
            });

            const activeBtn = document.getElementById(`inc-${type}`);
            const activeRadio = document.getElementById(`radio-${type}`);
            if (activeBtn && activeRadio) {
                activeBtn.classList.add('bg-white/5', 'text-white');
                activeBtn.classList.remove('text-white/60');
                activeRadio.classList.add('bg-[#C5A059]');
                activeRadio.classList.remove('bg-transparent');
            }

            applyFiltersAndSorting();
        }

        function applyFiltersAndSorting() {
            if (!allBalinesas || allBalinesas.length === 0) return;

            const searchText = document.getElementById('search-input').value.toLowerCase().trim();
            const selectedPax = document.getElementById('pax-filter').value;
            const selectedPriceRange = document.getElementById('price-range-filter').value;

            // 1. Filtrado Cruzado Estricto
            let filtered = allBalinesas.filter(b => {

                let matchInclusion = true;
                if (currentFilterInclusion === 'champagne') {
                    matchInclusion = b.botellaRaw.includes('moet') || b.botellaRaw.includes('chandon') || b
                        .botellaRaw.includes('champagne') || b.botellaRaw.includes('brut');
                } else if (currentFilterInclusion === 'premium') {
                    matchInclusion = !b.botellaRaw.includes('moet') && !b.botellaRaw.includes('chandon') && !b
                        .botellaRaw.includes('champagne');
                }

                const matchText = !searchText ||
                    (b.nombre && b.nombre.toLowerCase().includes(searchText)) ||
                    (b.botellaIncluida && b.botellaIncluida.toLowerCase().includes(searchText));

                let matchPax = true;
                if (selectedPax !== 'all') {
                    if (selectedPax === '6') {
                        matchPax = (b.capacidad_maxima >= 6);
                    } else {
                        matchPax = (b.capacidad_maxima == selectedPax);
                    }
                }

                let matchPriceRange = true;
                if (selectedPriceRange !== 'all') {
                    const priceVal = b.precio;
                    if (selectedPriceRange === '0-3000') matchPriceRange = (priceVal <= 3000);
                    else if (selectedPriceRange === '3000-6000') matchPriceRange = (priceVal > 3000 && priceVal <=
                        6000);
                    else if (selectedPriceRange === '6000-plus') matchPriceRange = (priceVal > 6000);
                }

                return matchInclusion && matchText && matchPax && matchPriceRange;
            });

            // 2. Ordenamiento por precio
            const sortVal = document.getElementById('price-sort').value;
            if (sortVal === 'low-high') {
                filtered.sort((a, b) => a.precio - b.precio);
            } else if (sortVal === 'high-low') {
                filtered.sort((a, b) => b.precio - a.precio);
            }

            renderCatalog(filtered);
        }

        function sortCatalog() {
            applyFiltersAndSorting();
        }

        function renderCatalog(items) {
            const container = document.getElementById('balinesas-container');

            if (items.length === 0) {
                container.innerHTML =
                    `<div class="p-6 flex flex-col gap-4 max-w-4xl mx-auto w-full"><div class="text-white/40 text-center py-12 tracking-widest text-xs uppercase">${translations[currentLang].no_results}</div></div>`;
                return;
            }

            let html = '<div class="p-6 flex flex-col gap-4 max-w-4xl mx-auto w-full">';
            items.forEach(balinesa => {
                html += `
            <div onclick="selectBalinesa('${balinesa.slug}')" class="bg-black/40 hover:bg-black/60 backdrop-blur-md border border-white/10 hover:border-[#C5A059]/40 rounded-xl p-4 sm:p-5 flex flex-row justify-between items-center transition-all duration-300 cursor-pointer shadow-lg transform active:scale-[0.995] group gap-4 sm:gap-6">
                
                <div class="flex-1 text-white">
                    <h3 translate="no" class="text-base sm:text-lg font-medium tracking-wide mb-1.5 group-hover:text-[#C5A059] transition-colors">${balinesa.nombre}</h3>
                    <p class="text-[11px] sm:text-xs text-white/70 font-light leading-relaxed mb-3 line-clamp-2">${balinesa.botellaIncluida}</p>
                    
                    <div class="flex flex-col gap-1 text-[10px] sm:text-[11px] text-white/50">
                        <div>• <span>${currentLang === 'en' ? 'Availability' : 'Disponibilidad'}</span>: <span class="text-emerald-400/90 font-medium">${balinesa.horarioDisponible}</span></div>
                        <div>• <span>${currentLang === 'en' ? 'Max Capacity' : 'Capacidad Máxima'}</span>: <span>${balinesa.capacidad_maxima} Pax</span></div>
                    </div>
                    
                    <div class="text-lg sm:text-xl font-light text-[#C5A059] mt-3 tracking-wide">$${balinesa.precio.toLocaleString()} <span class="text-[10px] sm:text-xs text-white/40 font-light">MXN</span></div>
                </div>

                <div class="w-32 h-32 sm:w-44 sm:h-36 md:w-52 md:h-40 bg-stone-900 border border-white/10 rounded-lg shrink-0 overflow-hidden relative group-hover:border-[#C5A059]/40 transition-colors shadow-inner">
                    <img src="${balinesa.imagen}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90 group-hover:opacity-100" alt="${balinesa.nombre}">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex items-end justify-center pb-2.5">
                        <span class="text-[9px] sm:text-[10px] uppercase tracking-wider text-white/90 text-center font-medium group-hover:text-[#C5A059] transition-colors drop-shadow-lg flex items-center gap-1.5">
                            ${translations[currentLang].tap_view} 
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

        function navigateWithAnimation(targetUrl) {
            document.getElementById('balinesas-body').classList.add('page-exit');
            setTimeout(() => {
                window.location.href = targetUrl + (targetUrl.includes('?') ? '&' : '?') + "lang=" + currentLang;
            }, 400);
        }

        function selectBalinesa(slug) {
            document.getElementById('balinesas-body').classList.add('page-exit');
            setTimeout(() => {
                window.location.href = `/hotel/detalle-balinesa/${slug}?lang=${currentLang}`;
            }, 400);
        }
    </script>
    <script>
    if ('serviceWorker' in navigator) { navigator.serviceWorker.register('/sw.js'); }
    </script>
@include('ipad._back-prevention')
</body>

</html>
