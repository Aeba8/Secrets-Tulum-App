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

        #experiencias-container {
            scrollbar-gutter: stable;
        }
        #experiencias-container::-webkit-scrollbar {
            width: 4px;
        }
        #experiencias-container::-webkit-scrollbar-track {
            background: transparent;
        }
        #experiencias-container::-webkit-scrollbar-thumb {
            background: rgba(197, 160, 89, 0.3);
            border-radius: 10px;
        }
        #experiencias-container::-webkit-scrollbar-thumb:hover {
            background: rgba(197, 160, 89, 0.6);
        }
    </style>
</head>

<body id="experiencias-body"
    class="font-sans h-screen w-screen overflow-hidden flex flex-col justify-between bg-cover bg-center bg-no-repeat fade-in select-none"
    style="background-image: url('{{ asset('storage/Experiencia.jpg') }}');">

    <div class="absolute inset-0 bg-black/60 z-0"></div>

    <!-- Top Navbar -->
    <div
        class="relative z-10 w-full bg-[#C5A059] text-white flex items-center justify-between px-6 py-2 text-sm shadow-md">
        <button onclick="navigateWithAnimation('{{ route('catalogo') }}')"
            class="flex items-center gap-2 opacity-90 hover:opacity-100 transition-all cursor-pointer focus:outline-none">
            <i class="fa-solid fa-chevron-left text-xs"></i>
            <span data-key="back">Ir atrás</span>
        </button>

        <span data-key="top_title" class="tracking-widest font-medium uppercase text-xs md:text-sm">Reservación de
            Experiencias VIP</span>

        <button onclick="navigateWithAnimation('{{ route('welcome') }}')"
            class="flex items-center gap-2 opacity-90 hover:opacity-100 transition-all cursor-pointer focus:outline-none">
            <span data-key="home">Inicio</span>
            <i class="fa-solid fa-house text-xs"></i>
        </button>
    </div>

    <!-- Workspace -->
    <div class="relative z-10 flex flex-row flex-1 h-[calc(100vh-40px)] w-full">

        <!-- Sidebar Lateral de Filtros Operativos -->
        <div
            class="w-1/4 min-w-[280px] max-w-[340px] bg-black/50 backdrop-blur-xl border-r border-white/10 p-5 flex flex-col gap-5 text-white overflow-y-auto no-scrollbar shadow-2xl">

            <!-- Buscador Dinámico -->
            <div class="flex flex-col gap-2">
                <h2 data-key="search_lbl" class="text-xs font-bold tracking-wider text-white/60 uppercase">Buscar
                    Experiencia</h2>
                <div class="relative">
                    <input type="text" id="search-input" oninput="applyFiltersAndSorting()"
                        placeholder="Escribe un servicio o lugar..."
                        class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-xs text-white placeholder-white/30 focus:outline-none focus:border-[#C5A059] transition-all">
                </div>
            </div>

            <hr class="border-white/10">

            <!-- Tipo de Actividad (Tipo) -->
            <div>
                <h2 data-key="filter_title" class="text-xs font-bold tracking-wider text-white/60 uppercase mb-3">Tipo
                    de Actividad</h2>
                <div class="flex flex-col gap-2">
                    <label class="flex items-center gap-3 cursor-pointer group text-xs">
                        <input type="radio" name="type-filter" value="all" checked
                            onchange="applyFiltersAndSorting()" class="hidden peer">
                        <div
                            class="w-4 h-4 rounded-full border border-[#C5A059] flex items-center justify-center peer-checked:bg-[#C5A059]">
                            <div
                                class="w-1.5 h-1.5 rounded-full bg-black scale-0 peer-checked:scale-100 transition-all">
                            </div>
                        </div>
                        <span data-key="type_all"
                            class="font-medium text-white group-hover:text-[#C5A059] transition-colors">Todas las
                            categorías</span>
                    </label>
                    <label
                        class="flex items-center gap-3 cursor-pointer group text-xs text-white/60 hover:text-white transition-all">
                        <input type="radio" name="type-filter" value="Spa & Bienestar"
                            onchange="applyFiltersAndSorting()" class="hidden peer">
                        <div
                            class="w-4 h-4 rounded-full border border-[#C5A059] group-hover:border-[#C5A059] flex items-center justify-center peer-checked:border-[#C5A059] peer-checked:bg-[#C5A059]">
                            <div
                                class="w-1.5 h-1.5 rounded-full bg-black scale-0 peer-checked:scale-100 transition-all">
                            </div>
                        </div>
                        <span data-key="type_wellness">Spa & Bienestar</span>
                    </label>
                    <label
                        class="flex items-center gap-3 cursor-pointer group text-xs text-white/60 hover:text-white transition-all">
                        <input type="radio" name="type-filter" value="Talleres & Mixología"
                            onchange="applyFiltersAndSorting()" class="hidden peer">
                        <div
                            class="w-4 h-4 rounded-full border border-[#C5A059] group-hover:border-[#C5A059] flex items-center justify-center peer-checked:border-[#C5A059] peer-checked:bg-[#C5A059]">
                            <div
                                class="w-1.5 h-1.5 rounded-full bg-black scale-0 peer-checked:scale-100 transition-all">
                            </div>
                        </div>
                        <span data-key="type_mixology">Talleres & Mixología</span>
                    </label>
                    <label
                        class="flex items-center gap-3 cursor-pointer group text-xs text-white/60 hover:text-white transition-all">
                        <input type="radio" name="type-filter" value="Gastronomico"
                            onchange="applyFiltersAndSorting()" class="hidden peer">
                        <div
                            class="w-4 h-4 rounded-full border border-[#C5A059] group-hover:border-[#C5A059] flex items-center justify-center peer-checked:border-[#C5A059] peer-checked:bg-[#C5A059]">
                            <div
                                class="w-1.5 h-1.5 rounded-full bg-black scale-0 peer-checked:scale-100 transition-all">
                            </div>
                        </div>
                        <span data-key="type_gastronomic">Gastronómico</span>
                    </label>
                </div>
            </div>

            <hr class="border-white/10">

            <!-- Capacidad de Personas -->
            <div class="flex flex-col gap-2">
                <h2 data-key="capacity_title" class="text-xs font-bold tracking-wider text-white/60 uppercase">Capacidad
                    (Pax)</h2>
                <select id="pax-filter" onchange="applyFiltersAndSorting()"
                    class="w-full bg-black/40 border border-white/10 rounded-lg py-2 px-3 text-xs text-white/80 focus:outline-none focus:border-[#C5A059] cursor-pointer transition-all">
                    <option value="all" data-key="pax_all" class="bg-stone-900">Cualquier capacidad</option>
                    <option value="1" data-key="pax_individual" class="bg-stone-900">Individual (1 Pax)</option>
                    <option value="2" data-key="pax_couples" class="bg-stone-900">Parejas (2 Pax)</option>
                    <option value="4" data-key="pax_group" class="bg-stone-900">Grupal (4+ Pax)</option>
                </select>
            </div>

            <hr class="border-white/10">

            <!-- Rangos y Órdenes de Precio -->
            <div class="flex flex-col gap-4">
                <h2 data-key="search_title" class="text-xs font-bold tracking-wider text-white/60 uppercase">Rango &
                    Preferencias</h2>

                <!-- Rangos Estrictos de Precios -->
                <div class="space-y-1.5">
                    <label data-key="range_lbl" class="text-[10px] uppercase tracking-wider text-white/40 block">Filtrar
                        por Rango</label>
                    <select id="price-range-filter" onchange="applyFiltersAndSorting()"
                        class="w-full bg-black/40 border border-white/10 rounded-lg py-2 px-3 text-xs text-white/80 focus:outline-none focus:border-[#C5A059] cursor-pointer transition-all">
                        <option value="all" data-key="price_all" class="bg-stone-900">Cualquier precio</option>
                        <option value="0-1500" data-key="price_up_to_15k" class="bg-stone-900">Hasta $1,500 MXN
                        </option>
                        <option value="1500-4000" data-key="price_15k_to_4k" class="bg-stone-900">$1,500 MXN - $4,000
                            MXN</option>
                        <option value="4000-plus" data-key="price_over_4k" class="bg-stone-900">Más de $4,000 MXN
                        </option>
                    </select>
                </div>

                <!-- Sentidos de Ordenamiento Ascendente y Descendente -->
                <div class="space-y-1.5">
                    <label data-key="sort_lbl"
                        class="text-[10px] uppercase tracking-wider text-white/40 block">Ordenar por Precio</label>
                    <select id="price-sort" onchange="applyFiltersAndSorting()"
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

        <!-- Contenedor Principal de Tarjetas -->
        <div id="experiencias-container"
            class="flex-1 overflow-y-auto">
            <div class="p-6 flex flex-col gap-4 max-w-4xl mx-auto w-full">
                <div class="text-white/40 text-center py-10 tracking-widest text-xs uppercase">
                    <i class="fa-solid fa-circle-notch animate-spin mr-2"></i> Cargando experiencias...
                </div>
            </div>
        </div>
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
                search_lbl: "Buscar Experiencia",
                capacity_title: "Capacidad (Pax)",
                pax_all: "Cualquier capacidad",
                pax_individual: "Individual (1 Pax)",
                pax_couples: "Parejas (2 Pax)",
                pax_group: "Grupal (4+ Pax)",
                search_title: "Rango & Preferencias",
                range_lbl: "Filtrar por Rango",
                price_all: "Cualquier precio",
                price_up_to_15k: "Hasta $1,500 MXN",
                price_15k_to_4k: "$1,500 MXN - $4,000 MXN",
                price_over_4k: "Más de $4,000 MXN",
                sort_lbl: "Ordenar por Precio",
                sort_default: "Predeterminado",
                sort_asc: "📈 Menor a Mayor precio",
                sort_desc: "📉 Mayor a Menor precio",
                loading: "Cargando experiencias...",
                tap_view: "Tocar para ver",
                duration_lbl: "Duración",
                place_lbl: "Lugar",
                capacity_lbl: "Capacidad",
                default_place: "Instalaciones del Hotel",
                no_results: "No se encontraron experiencias con los filtros seleccionados."
            },
            en: {
                back: "Back",
                home: "Home",
                top_title: "VIP Experiences Reservation",
                filter_title: "Activity Type",
                type_all: "All Categories",
                type_wellness: "Spa & Wellness",
                type_mixology: "Workshops & Mixology",
                search_lbl: "Search Experience",
                capacity_title: "Capacity (Pax)",
                pax_all: "Any capacity",
                pax_individual: "Individual (1 Pax)",
                pax_couples: "Couples (2 Pax)",
                pax_group: "Group (4+ Pax)",
                search_title: "Range & Preferences",
                range_lbl: "Filter by Range",
                price_all: "Any price",
                price_up_to_15k: "Up to $1,500 MXN",
                price_15k_to_4k: "$1,500 MXN - $4,000 MXN",
                price_over_4k: "Over $4,000 MXN",
                sort_lbl: "Sort by Price",
                sort_default: "Default",
                sort_asc: "📈 Lowest to Highest price",
                sort_desc: "📉 Highest to Lowest price",
                loading: "Loading experiences...",
                tap_view: "Tap to view",
                duration_lbl: "Duration",
                place_lbl: "Location",
                capacity_lbl: "Capacity",
                default_place: "Hotel Facilities",
                no_results: "No experiences found matching the selected filters."
            }
        };

        const urlParams = new URLSearchParams(window.location.search);
        const currentLang = urlParams.get('lang') === 'en' ? 'en' : 'es';
        let allExperiencias = [];

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

        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.placeholder = currentLang === 'en' ? "Type a service or location..." :
                "Escribe un servicio o lugar...";
        }

        async function traducirTextoAIngles(textoOriginal) {
            if (!textoOriginal) return '';
            try {
                const response = await fetch(
                    `https://api.mymemory.translated.net/get?q=${encodeURIComponent(textoOriginal)}&langpair=es|en`);
                const data = await response.json();
                return data.responseData?.translatedText || textoOriginal;
            } catch (error) {
                console.error("Error al traducir automáticamente:", error);
                return textoOriginal;
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById('experiencias-container');

            fetch('/api/hotel/catalog')
                .then(response => response.json())
                .then(async res => {
                    if (res.success && res.data && res.data.experiencias) {

                        allExperiencias = await Promise.all(res.data.experiencias.map(async (exp) => {
                            let nombre = exp.nombre;
                            let descripcion = exp.descripcion || exp.Descripcion || '';
                            let duracion = exp.duracion || exp.Duracion || '';
                            let lugar = exp.lugar || exp.Lugar || 'Instalaciones del Hotel';

                            // 🌟 EXTRAER LA PRIMERA IMAGEN DE FORMA SEGURA (Copiado de Balinesas)
                            let primeraImagen =
                                'https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=300';
                            if (exp.imagenes) {
                                try {
                                    const arrayImg = typeof exp.imagenes === 'string' ? JSON
                                        .parse(exp.imagenes) : exp.imagenes;
                                    if (Array.isArray(arrayImg) && arrayImg.length > 0) {
                                        primeraImagen = arrayImg[0];
                                    }
                                } catch (e) {
                                    if (Array.isArray(exp.imagenes) && exp.imagenes.length >
                                        0) {
                                        primeraImagen = exp.imagenes[0];
                                    }
                                }
                            }

                            if (currentLang === 'en') {
                                nombre = exp.name || exp.nombre;
                                descripcion = await traducirTextoAIngles(descripcion);
                                duracion = await traducirTextoAIngles(duracion);
                                if (exp.lugar || exp.Lugar) {
                                    lugar = await traducirTextoAIngles(lugar);
                                } else {
                                    lugar = translations['en'].default_place;
                                }
                            }

                            return {
                                slug: exp.slug,
                                tipo: exp.tipo || exp.Tipo || '',
                                precio: Number(exp.precio || exp.Precio || 0),
                                numero_personas: Number(exp.numero_personas || 2),
                                renderedNombre: nombre,
                                renderedDescripcion: descripcion,
                                renderedDuracion: duracion,
                                renderedLugar: lugar,
                                imagen: primeraImagen // 🌟 Agregamos la propiedad de imagen limpia
                            };
                        }));

                        applyFiltersAndSorting();
                    } else {
                        container.innerHTML =
                            `<div class="p-6 flex flex-col gap-4 max-w-4xl mx-auto w-full"><div class="text-white/40 text-center py-10 text-xs uppercase">${translations[currentLang].no_results}</div></div>`;
                    }
                })
                .catch(err => {
                    console.error("Error crítico:", err);
                    container.innerHTML =
                        `<div class="p-6 flex flex-col gap-4 max-w-4xl mx-auto w-full"><div class="text-red-400 text-center py-10 text-xs uppercase">${currentLang === 'en' ? 'Error loading catalog data.' : 'Error al cargar los datos.'}</div></div>`;
                });
        });

        function applyFiltersAndSorting() {
            if (!allExperiencias || allExperiencias.length === 0) return;

            const searchText = document.getElementById('search-input').value.toLowerCase().trim();
            const selectedTypeElement = document.querySelector('input[name="type-filter"]:checked');
            const selectedType = selectedTypeElement ? selectedTypeElement.value : 'all';
            const selectedPax = document.getElementById('pax-filter').value;
            const selectedPriceRange = document.getElementById('price-range-filter').value;

            let resultados = allExperiencias.filter(exp => {
                const matchType = (selectedType === 'all' || exp.tipo === selectedType);

                const matchText = !searchText ||
                    (exp.renderedNombre && exp.renderedNombre.toLowerCase().includes(searchText)) ||
                    (exp.renderedDescripcion && exp.renderedDescripcion.toLowerCase().includes(searchText)) ||
                    (exp.renderedLugar && exp.renderedLugar.toLowerCase().includes(searchText));

                let matchPax = true;
                if (selectedPax !== 'all') {
                    if (selectedPax === '4') {
                        matchPax = (exp.numero_personas >= 4);
                    } else {
                        matchPax = (exp.numero_personas == selectedPax);
                    }
                }

                let matchPriceRange = true;
                if (selectedPriceRange !== 'all') {
                    if (selectedPriceRange === '0-1500') matchPriceRange = (exp.precio <= 1500);
                    else if (selectedPriceRange === '1500-4000') matchPriceRange = (exp.precio > 1500 && exp
                        .precio <= 4000);
                    else if (selectedPriceRange === '4000-plus') matchPriceRange = (exp.precio > 4000);
                }

                return matchType && matchText && matchPax && matchPriceRange;
            });

            const sortVal = document.getElementById('price-sort').value;
            if (sortVal === 'low-high') {
                resultados.sort((a, b) => a.precio - b.precio);
            } else if (sortVal === 'high-low') {
                resultados.sort((a, b) => b.precio - a.precio);
            }

            renderExperiencias(resultados);
        }

        function renderExperiencias(items) {
            const container = document.getElementById('experiencias-container');
            if (!container) return;

            if (items.length === 0) {
                container.innerHTML =
                    `<div class="p-6 flex flex-col gap-4 max-w-4xl mx-auto w-full"><div class="text-white/40 text-center py-12 tracking-widest text-xs uppercase">${translations[currentLang].no_results}</div></div>`;
                return;
            }

            const t = translations[currentLang];

            let html = '<div class="p-6 flex flex-col gap-4 max-w-4xl mx-auto w-full">';
            items.forEach(exp => {
                html += `
                    <div onclick="selectExperiencia('${exp.slug}')" class="bg-black/60 hover:bg-black/70 backdrop-blur-sm border border-white/10 hover:border-[#A21B54]/50 rounded-xl p-4 sm:p-5 flex flex-row justify-between items-center transition-all duration-300 cursor-pointer shadow-lg transform active:scale-[0.99] group gap-4 sm:gap-6">
                        
                        <div class="flex-1 text-white">
                            <h3 translate="no" class="text-lg font-semibold tracking-wide mb-2 group-hover:text-[#D4AF37] transition-colors">${exp.renderedNombre}</h3>
                            <p class="text-xs text-white/70 font-light leading-relaxed mb-3 line-clamp-2">${exp.renderedDescripcion}</p>
                            
                            <div class="flex flex-col gap-1 text-[11px] text-white/60">
                                <div>• <span>${t.duration_lbl}</span>: <span class="font-medium text-white/80">${exp.renderedDuracion}</span></div>
                                <div>• <span>${t.place_lbl}</span>: <span class="font-medium text-white/80">${exp.renderedLugar}</span></div>
                                <div>• <span>${t.capacity_lbl}</span>: <span class="font-medium text-white/80">${exp.numero_personas} Pax</span></div>
                            </div>
                            
                            <div class="text-xl font-semibold text-[#D4AF37] mt-3 tracking-wide">$${exp.precio.toLocaleString()} <span class="text-xs text-white/50 font-light">MXN</span></div>
                        </div>

                        <!-- 🌟 CONTENEDOR DE IMAGEN ASÍNCRONO (Mismo layout de Balinesas con colores dorados de Experiencias) -->
                        <div class="w-32 h-32 sm:w-44 sm:h-36 md:w-52 md:h-40 bg-stone-900 border border-white/10 rounded-lg shrink-0 overflow-hidden relative group-hover:border-[#D4AF37]/40 transition-colors shadow-inner">
                            <img src="${exp.imagen}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90 group-hover:opacity-100" alt="${exp.renderedNombre}">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex items-end justify-center pb-2.5">
                                <span class="text-[9px] sm:text-[10px] uppercase tracking-wider text-white/90 text-center font-medium group-hover:text-[#D4AF37] transition-colors drop-shadow-lg flex items-center gap-1.5">
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

        function navigateWithAnimation(url) {
            const body = document.getElementById('experiencias-body');
            body.classList.add('page-exit');
            setTimeout(() => {
                window.location.href = url + (url.includes('?') ? '&' : '?') + 'lang=' + currentLang;
            }, 400);
        }

        function selectExperiencia(slug) {
            const body = document.getElementById('experiencias-body');
            body.classList.add('page-exit');
            setTimeout(() => {
                window.location.href = `/hotel/detalle-experiencia/${slug}?lang=${currentLang}`;
            }, 400);
        }
    </script>
</body>

</html>
