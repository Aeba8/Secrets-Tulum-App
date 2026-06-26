<!DOCTYPE html>
<html lang="{{ request('lang', 'es') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secrets Tulum - Catálogo</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap');
        .font-sans { font-family: 'Montserrat', sans-serif; }
        
        .fade-in { animation: fadeInPage 0.6s ease-out forwards; }
        .page-exit { opacity: 0; transform: scale(1.03); transition: all 0.4s ease-in-out; }

        @keyframes fadeInPage {
            from { opacity: 0; transform: scale(0.98); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body id="catalog-body" class="font-sans h-screen w-screen overflow-hidden flex flex-col justify-between bg-zinc-950 text-white fade-in select-none">

    <header class="relative z-10 w-full bg-[#A21B54] text-white flex flex-row justify-between items-center px-6 py-3 shadow-md">
        <button onclick="navigateWithAnimation('{{ route('welcome') }}')" class="text-white/80 hover:text-white flex items-center gap-2 text-xs uppercase tracking-widest transition-all active:scale-95 cursor-pointer">
            <i class="fa-solid fa-arrow-left"></i> <span>Atrás</span>
        </button>
        <div translate="no" class="text-sm tracking-widest font-medium uppercase absolute left-1/2 transform -translate-x-1/2">
            Secrets Tulum
        </div>
        <div class="text-xs tracking-widest font-light text-white/60 uppercase">
            {{ request('lang') == 'en' ? 'Exclusive Catalog' : 'Catálogo Exclusivo' }}
        </div>
    </header>

    <main class="flex-1 flex flex-col justify-center items-center px-8 max-w-6xl mx-auto w-full gap-8">
        
        <div class="text-center mb-2">
            <h1 class="text-2xl font-light tracking-widest uppercase mb-2 text-[#D4AF37]">
                {{ request('lang') == 'en' ? 'Select an Experience' : 'Seleccione una Experiencia' }}
            </h1>
            <div class="w-16 h-[1px] bg-white/20 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 w-full justify-center">
            
            {{-- 🌟 CICLO DINÁMICO SOBRE LA BASE DE DATOS 🌟 --}}
            @foreach($categorias as $cat)
                @php
                    $slugLimpio = strtolower($cat->Slug);
                    
                    // 1. Mapeo de íconos predeterminados para tus categorías base
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
                        // 🌟 ÍCONO DE RESPALDO: Si creas una categoría nueva como "Cenotes", usará este automáticamente
                        $icon = 'fa-water'; 
                        $targetRoute = '/hotel/' . $slugLimpio; 
                    }
                @endphp

                <button onclick="navigateWithAnimation('{{ $targetRoute }}')" 
                    class="group relative bg-zinc-900/50 hover:bg-zinc-900 border border-white/10 hover:border-[#A21B54]/60 rounded-2xl p-6 flex flex-col items-center justify-center gap-4 transition-all duration-300 cursor-pointer shadow-xl transform active:scale-98 min-h-[190px] w-full">
                    
                    <div class="absolute inset-0 bg-gradient-to-b from-[#A21B54]/0 to-[#A21B54]/5 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></div>

                    <div class="w-14 h-14 rounded-full bg-white/5 group-hover:bg-[#A21B54] flex items-center justify-center border border-white/10 group-hover:border-transparent transition-all duration-300 transform group-hover:scale-110">
                        <i class="fa-solid {{ $icon }} text-lg text-[#D4AF37] group-hover:text-white transition-colors"></i>
                    </div>

                    <div class="text-center relative z-10">
                        <h2 translate="no" class="text-sm font-semibold tracking-widest uppercase mb-1 group-hover:text-[#D4AF37] transition-colors">
                            @if(request('lang') == 'en')
                                {{-- Traducción rápida de respaldo para tus categorías principales --}}
                                {{ $slugLimpio === 'cenas-especiales' ? 'Romantic Dinners' : ($slugLimpio === 'balinesas' ? 'Bali Beds' : ($slugLimpio === 'experiencias' ? 'VIP Experiences' : ucfirst($cat->Slug))) }}
                            @else
                                {{ $cat->Nombre }}
                            @endif
                        </h2>
                        <span class="text-[9px] text-white/40 uppercase tracking-wider group-hover:text-white/60 transition-colors">
                            {{ request('lang') == 'en' ? 'View Options' : 'Ver Opciones' }}
                        </span>
                    </div>
                </button>
            @endforeach

        </div>

    </main>

    <footer class="w-full text-center py-4 text-[10px] text-white/20 tracking-widest uppercase relative z-10">
        &copy; {{ date('Y') }} Secrets Resorts & Spas
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