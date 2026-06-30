@php
    // Dividimos la Ficha_Tecnica original del modelo usando tu separador '|'
    $parts = explode('|', $balinesa->Ficha_Tecnica ?? ($balinesa->ficha_tecnica ?? ''));
    $horario_disponible = isset($parts[0]) && !empty(trim($parts[0])) ? trim($parts[0]) : 'Todos los días';
    $botella_incluida = isset($parts[1]) && !empty(trim($parts[1])) ? trim($parts[1]) : '1 botella de Moët & Chandon Brut 750ml';
@endphp

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
    style="background-image: url('{{ asset('storage/Balinesa.jpg') }}');">

    <div class="absolute inset-0 bg-black/60 z-0"></div>

    <!-- Top Navbar -->
    <div class="relative z-10 w-full bg-[#C5A059] text-[#2C1A11] flex items-center justify-between px-6 py-2 text-sm shadow-md">
        <button onclick="navigateWithAnimation('{{ route('catalogo') }}')"
            class="flex items-center gap-2 opacity-90 hover:opacity-100 transition-all cursor-pointer focus:outline-none font-medium">
            <i class="fa-solid fa-chevron-left text-xs"></i>
            <span data-key="back">Ir atrás</span>
        </button>

        <span data-key="top_title" class="tracking-[0.2em] font-medium uppercase text-xs md:text-sm">Reservación de Camas Balinesas</span>

        <button onclick="navigateWithAnimation('{{ route('welcome') }}')"
            class="flex items-center gap-2 opacity-90 hover:opacity-100 transition-all cursor-pointer focus:outline-none font-medium">
            <span data-key="home">Inicio</span>
            <i class="fa-solid fa-house text-xs"></i>
        </button>
    </div>

    <!-- Main Workspace -->
    <div class="relative z-10 flex flex-row flex-1 h-[calc(100vh-40px)] w-full">

        <!-- SIDEBAR DE FILTROS REDISEÑADO POR EXPERIENCIA DE BEBIDAS -->
        <div class="w-1/4 min-w-[280px] max-w-[340px] bg-black/50 backdrop-blur-xl border-r border-white/10 p-6 flex flex-col gap-6 text-white shadow-2xl">
            
            <div>
                <h2 data-key="filter_title" class="text-[11px] font-bold tracking-[0.2em] text-[#C5A059] uppercase mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-wine-bottle text-[10px]"></i> Inclusiones (Bebida)
                </h2>
                
                <div class="flex flex-col gap-2">
                    <!-- Opción: Todo -->
                    <button onclick="filterByInclusion('all')" id="inc-all"
                        class="inclusion-filter-btn flex items-center justify-between w-full p-3 rounded-lg border border-white/5 bg-white/5 text-white text-sm transition-all duration-300 cursor-pointer">
                        <span data-key="inc_all" class="font-medium">Cualquier paquete</span>
                        <div class="w-4 h-4 rounded-full border-2 border-[#C5A059] flex items-center justify-center p-0.5">
                            <div id="radio-all" class="w-full h-full rounded-full bg-[#C5A059]"></div>
                        </div>
                    </button>
                    
                    <!-- Opción: Champagne (Moët, Veuve, etc.) -->
                    <button onclick="filterByInclusion('champagne')" id="inc-champagne"
                        class="inclusion-filter-btn flex items-center justify-between w-full p-3 rounded-lg border border-white/5 bg-transparent text-white/60 text-sm hover:text-white hover:bg-white/[0.02] transition-all duration-300 cursor-pointer">
                        <span data-key="inc_champagne">Con Champagne / Moët</span>
                        <div class="w-4 h-4 rounded-full border border-white/30 flex items-center justify-center p-0.5">
                            <div id="radio-champagne" class="w-full h-full rounded-full bg-transparent"></div>
                        </div>
                    </button>
                    
                    <!-- Opción: Destilados Premium u Otras botellas -->
                    <button onclick="filterByInclusion('premium')" id="inc-premium"
                        class="inclusion-filter-btn flex items-center justify-between w-full p-3 rounded-lg border border-white/5 bg-transparent text-white/60 text-sm hover:text-white hover:bg-white/[0.02] transition-all duration-300 cursor-pointer">
                        <span data-key="inc_premium">Licores y Premium Botellas</span>
                        <div class="w-4 h-4 rounded-full border border-white/30 flex items-center justify-center p-0.5">
                            <div id="radio-premium" class="w-full h-full rounded-full bg-transparent"></div>
                        </div>
                    </button>
                </div>
            </div>

            <hr class="border-white/10">

            <!-- Preferencias Avanzadas de Ordenamiento -->
            <div class="flex flex-col gap-4">
                <h2 data-key="search_title" class="text-[11px] font-bold tracking-[0.2em] text-[#C5A059] uppercase flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-[10px]"></i> Preferencias
                </h2>
                
                <div class="space-y-2">
                    <label class="text-[10px] uppercase tracking-wider text-white/40 block">Ordenar por Precio</label>
                    <select id="price-sort" onchange="sortCatalog()" 
                        class="w-full bg-black/40 border border-white/10 rounded-lg py-2.5 px-3 text-xs text-white/80 focus:outline-none focus:border-[#C5A059] cursor-pointer transition-all">
                        <option value="default" class="bg-stone-900">Predeterminado</option>
                        <option value="low-high" class="bg-stone-900">Menor a Mayor precio</option>
                        <option value="high-low" class="bg-stone-900">Mayor a Menor precio</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- CONTENEDOR PRINCIPAL DE TARJETAS -->
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
                filter_title: "Inclusiones (Bebida)",
                inc_all: "Cualquier paquete",
                inc_champagne: "Con Champagne / Moët",
                inc_premium: "Licores y Premium Botellas",
                search_title: "Preferencias",
                loading: "Cargando catálogo...",
                tap_view: "Tocar para ver",
                no_results: "No se encontraron paquetes con esta selección."
            },
            en: {
                back: "Back",
                home: "Home",
                top_title: "Bali Beds Reservation",
                filter_title: "Inclusions (Beverage)",
                inc_all: "Any Package",
                inc_champagne: "With Champagne / Moët",
                inc_premium: "Liquors & Premium Bottles",
                search_title: "Preferences",
                loading: "Loading catalog...",
                tap_view: "Tap to view",
                no_results: "No packages found with this selection."
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

        async function traducirTextoAIngles(textoOriginal) {
            if (!textoOriginal || textoOriginal.trim() === "") return "";
            try {
                const response = await fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(textoOriginal)}&langpair=es|en`);
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
                            const descripcionOriginal = balinesa.ficha_tecnica || balinesa.Descripcion || '';
                            const partesFicha = descripcionOriginal.split('|');
                            
                            let horarioDisponible = partesFicha[0] && partesFicha[0].trim() !== '' 
                                ? partesFicha[0].trim() 
                                : (currentLang === 'en' ? 'Every day' : 'Todos los días');
                            
                            let botellaIncluida = partesFicha[1] && partesFicha[1].trim() !== '' 
                                ? partesFicha[1].trim() 
                                : (currentLang === 'en' ? 'Premium bottle included' : 'Botella premium incluida');

                            // Guardamos una copia del texto original en español para realizar la lógica de filtrado limpia
                            const botellaOriginalEspañol = partesFicha[1] ? partesFicha[1].toLowerCase() : '';

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
                                botellaRaw: botellaOriginalEspañol, // Atributo clave para el filtro interno
                                horarioDisponible: horarioDisponible,
                                capacidad_maxima: balinesa.capacidad_maxima || balinesa.Capacidad_Maxima || 4,
                                precio: Number(balinesa.precio || balinesa.Precio)
                            };
                        }));

                        renderCatalog(allBalinesas);
                    }
                })
                .catch(err => {
                    console.error("Error al obtener catálogo:", err);
                    document.getElementById('balinesas-container').innerHTML = `<div class="text-red-400 text-center py-10 text-xs uppercase">${currentLang === 'en' ? 'Error loading data.' : 'Error al cargar los datos.'}</div>`;
                });
        });

        // 🌟 NUEVA FUNCIÓN: FILTRADO POR TIPO DE INCLUSIÓN
        function filterByInclusion(type) {
            currentFilterInclusion = type;

            // Reiniciar botones e indicadores visuales
            document.querySelectorAll('.inclusion-filter-btn').forEach(btn => {
                btn.classList.remove('bg-white/5', 'text-white');
                btn.classList.add('bg-transparent', 'text-white/60');
            });
            document.querySelectorAll('.inclusion-filter-btn div div').forEach(radio => {
                radio.classList.remove('bg-[#C5A059]');
                radio.classList.add('bg-transparent');
            });

            // Encender el botón activo
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
            let filtered = [...allBalinesas];

            // Filtrado según coincidencia de texto en la ficha técnica procesada
            if (currentFilterInclusion === 'champagne') {
                filtered = filtered.filter(b => b.botellaRaw.includes('moët') || b.botellaRaw.includes('chandon') || b.botellaRaw.includes('champagne') || b.botellaRaw.includes('brut'));
            } else if (currentFilterInclusion === 'premium') {
                // Muestra los que NO son champagne, o licores destilados alternos
                filtered = filtered.filter(b => !b.botellaRaw.includes('moët') && !b.botellaRaw.includes('chandon') && !b.botellaRaw.includes('champagne'));
            }

            // Ordenamiento por precio
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
            container.innerHTML = '';

            if (items.length === 0) {
                container.innerHTML = `<div class="text-white/40 text-center py-12 tracking-widest text-xs uppercase">${translations[currentLang].no_results}</div>`;
                return;
            }

            items.forEach(balinesa => {
                const card = `
                    <div onclick="selectBalinesa('${balinesa.slug}')" class="bg-black/40 hover:bg-black/60 backdrop-blur-md border border-white/10 hover:border-[#C5A059]/40 rounded-xl p-5 flex flex-row justify-between items-center transition-all duration-300 cursor-pointer shadow-lg transform active:scale-[0.995] group">
                        <div class="flex-1 pr-6 text-white">
                            <h3 translate="no" class="text-lg font-medium tracking-wide mb-1.5 group-hover:text-[#C5A059] transition-colors">${balinesa.nombre}</h3>
                            <p class="text-xs text-white/70 font-light leading-relaxed mb-3">${balinesa.botellaIncluida}</p>
                            
                            <div class="flex flex-col gap-1 text-[11px] text-white/50">
                                <div>• <span>${currentLang === 'en' ? 'Availability' : 'Disponibilidad'}</span>: <span class="text-emerald-400/90 font-medium">${balinesa.horarioDisponible}</span></div>
                                <div>• <span>${currentLang === 'en' ? 'Max Capacity' : 'Capacidad Máxima'}</span>: <span>${balinesa.capacidad_maxima} Pax</span></div>
                            </div>
                            
                            <div class="text-xl font-light text-[#C5A059] mt-3 tracking-wide">$${balinesa.precio.toLocaleString()} <span class="text-xs text-white/40 font-light">MXN</span></div>
                        </div>
                        <div class="w-24 h-24 bg-white/[0.02] border border-white/10 rounded-lg flex flex-col justify-center items-center gap-2 p-2 shrink-0 group-hover:border-[#C5A059]/30 transition-colors">
                            <i class="fa-solid fa-umbrella-beach text-2xl text-[#C5A059]/80 group-hover:scale-110 transition-transform duration-300"></i>
                            <span class="text-[9px] uppercase tracking-wider text-white/40 text-center font-medium group-hover:text-white transition-colors">${translations[currentLang].tap_view}</span>
                        </div>
                    </div>
                `;
                container.innerHTML += card;
            });
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
</body>

</html>