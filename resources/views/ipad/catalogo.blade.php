<!DOCTYPE html>
<html lang="{{ request('lang', 'es') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secrets Tulum - Catálogo</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Montserrat:wght@300;400;500;600&display=swap');
        
        .font-sans { font-family: 'Montserrat', sans-serif; }
        .font-serif { font-family: 'Cinzel', serif; }
        
        .fade-in { animation: fadeInPage 1s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
        .page-exit { opacity: 0; transform: scale(1.01); transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1); }

        @keyframes fadeInPage {
            from { opacity: 0; transform: scale(0.98); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Efecto premium de luz reflejada */
        .premium-glow {
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.5), 
                        0 0 30px 0 rgba(197, 160, 89, 0.04);
        }
        .group:hover .premium-glow {
            box-shadow: 0 20px 40px -5px rgba(0, 0, 0, 0.6), 
                        0 0 40px 5px rgba(197, 160, 89, 0.15);
        }
    </style>
</head>
<body id="catalog-body" class="font-sans h-screen w-screen overflow-hidden flex flex-col justify-between bg-zinc-950 text-white fade-in select-none relative" 
    style="background-image: linear-gradient(to bottom, rgba(15, 15, 18, 0.65), rgba(8, 8, 10, 0.75)), url('{{ asset('storage/Fondo 2.png') }}'); background-size: cover; background-position: center;">

    <!-- 🌌 Capa de brillo ambiental de fondo para quitar lo "apagado" -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_40%,_rgba(197,160,89,0.08)_0%,_rgba(0,0,0,0)_70%)] z-0"></div>

    <!-- 🏷️ Header Líquido y Premium -->
    <header class="relative z-10 w-full flex flex-row justify-between items-center px-8 py-5 border-b border-white/[0.08] backdrop-blur-md bg-zinc-950/20 shadow-sm">
        <button onclick="navigateWithAnimation('{{ route('welcome') }}')" class="text-white/70 hover:text-[#C5A059] flex items-center gap-2.5 text-[10px] uppercase tracking-[0.25em] font-medium transition-all active:scale-95 cursor-pointer">
            <i class="fa-solid fa-arrow-left text-[9px] text-[#C5A059]"></i> <span>{{ request('lang') == 'en' ? 'Back' : 'Atrás' }}</span>
        </button>
        
        <div translate="no" class="font-serif text-base sm:text-lg tracking-[0.35em] font-medium uppercase text-white absolute left-1/2 transform -translate-x-1/2 drop-shadow-[0_2px_10px_rgba(0,0,0,0.5)]">
            Secrets Tulum
        </div>
        
        <div class="text-[9px] tracking-[0.2em] font-semibold text-zinc-950 bg-gradient-to-r from-[#E5C483] to-[#C5A059] px-4 py-1.5 rounded-md uppercase shadow-md shadow-[#C5A059]/10">
            {{ request('lang') == 'en' ? 'Exclusive Catalog' : 'Catálogo Exclusivo' }}
        </div>
    </header>

    <!-- 🍽️ Contenido Principal -->
    <main class="flex-1 flex flex-col justify-center items-center px-8 max-w-6xl mx-auto w-full gap-12 relative z-10">
        
        <!-- Título de Sección Iluminado -->
        <div class="text-center">
            <h1 class="font-serif text-2xl sm:text-3xl font-medium tracking-[0.3em] uppercase mb-4 bg-gradient-to-b from-white via-white to-[#E5C483] bg-clip-text text-transparent drop-shadow-sm">
                {{ request('lang') == 'en' ? 'Select an Experience' : 'Seleccione una Experiencia' }}
            </h1>
            <div class="w-20 h-[2px] bg-gradient-to-r from-transparent via-[#C5A059] to-transparent mx-auto shadow-[0_0_8px_#C5A059]"></div>
        </div>

        <!-- Grid de Tarjetas Iluminadas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 w-full justify-center max-w-4xl">
            
            @foreach($categorias as $cat)
                @php
                    $slugLimpio = strtolower($cat->Slug);
                    
                    if ($slugLimpio === 'balinesas') {
                        $icon = 'fa-umbrella-beach';
                        $targetRoute = route('paquetes.balinesas');
                    } elseif ($slugLimpio === 'cenas-especiales') {
                        $icon = 'fa-wine-glass';
                        $targetRoute = route('paquetes.cenas');
                    } elseif ($slugLimpio === 'experiencias') {
                        $icon = 'fa-spa';
                        $targetRoute = route('paquetes.experiencias');
                    } else {
                        $icon = 'fa-water'; 
                        $targetRoute = '/hotel/' . $slugLimpio; 
                    }
                @endphp

                <!-- Tarjeta de Alto Impacto / Cristal de Lujo -->
                <button onclick="navigateWithAnimation('{{ $targetRoute }}')" 
                    class="group relative premium-glow bg-zinc-900/50 hover:bg-zinc-900/80 backdrop-blur-xl border border-white/[0.12] hover:border-[#E5C483]/60 rounded-2xl p-8 flex flex-col items-center justify-center gap-6 transition-all duration-500 cursor-pointer transform active:scale-[0.98] min-h-[230px] w-full overflow-hidden">
                    
                    <!-- Resplandor superior sutil permanente que se enciende en Hover -->
                    <div class="absolute -top-10 left-1/2 -translate-x-1/2 w-32 h-20 bg-[#C5A059]/10 group-hover:bg-[#C5A059]/30 rounded-full blur-xl transition-all duration-500"></div>

                    <!-- Contenedor del Ícono Activo -->
                    <div class="w-16 h-16 rounded-full bg-zinc-950/60 border border-white/[0.15] group-hover:bg-gradient-to-b group-hover:from-[#E5C483] group-hover:to-[#C5A059] group-hover:border-transparent flex items-center justify-center transition-all duration-500 transform group-hover:scale-105 group-hover:shadow-[0_0_30px_rgba(229,196,131,0.3)]">
                        <i class="fa-solid {{ $icon }} text-xl text-[#E5C483] group-hover:text-zinc-950 transition-colors duration-500"></i>
                    </div>

                    <!-- Textos Enérgicos -->
                    <div class="text-center relative z-10 flex flex-col items-center gap-2.5">
                        <h2 translate="no" class="text-xs font-semibold tracking-[0.2em] uppercase text-white group-hover:text-[#E5C483] transition-colors duration-300 drop-shadow-[0_2px_4px_rgba(0,0,0,0.4)]">
                            @if(request('lang') == 'en')
                                {{ $slugLimpio === 'cenas-especiales' ? 'Romantic Dinners' : ($slugLimpio === 'balinesas' ? 'Bali Beds' : ($slugLimpio === 'experiencias' ? 'VIP Experiences' : ucfirst($cat->Slug))) }}
                            @else
                                {{ $cat->Nombre }}
                            @endif
                        </h2>
                        
                        <!-- Línea indicadora dorada -->
                        <div class="w-6 h-[1.5px] bg-[#C5A059]/30 group-hover:bg-[#E5C483] group-hover:w-12 group-hover:shadow-[0_0_8px_#E5C483] transition-all duration-500 ease-out"></div>
                        
                        <!-- Acción (Ver Opciones) con mayor opacidad -->
                        <span class="text-[9px] text-white/40 uppercase tracking-[0.2em] flex items-center gap-1.5 group-hover:text-white/90 transition-colors duration-300">
                            <span>{{ request('lang') == 'en' ? 'View Options' : 'Ver Opciones' }}</span>
                            <i class="fa-solid fa-chevron-right text-[7px] text-[#C5A059] transform translate-x-0 group-hover:translate-x-1 transition-transform"></i>
                        </span>
                    </div>
                </button>
            @endforeach

        </div>
    </main>

    <!-- ⏳ Footer Fino -->
    <footer class="w-full text-center py-5 text-[8.5px] text-white/30 tracking-[0.25em] uppercase relative z-10 border-t border-white/[0.06] bg-zinc-950/40 backdrop-blur-md">
        &copy; {{ date('Y') }} SecretsPad &bull; WEB
    </footer>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const currentLang = urlParams.get('lang') || 'es';

        function navigateWithAnimation(targetUrl) {
            const body = document.getElementById('catalog-body');
            body.classList.add('page-exit');
            
            setTimeout(() => {
                window.location.href = targetUrl + "?lang=" + currentLang;
            }, 400);
        }
    </script>
</body>
</html>