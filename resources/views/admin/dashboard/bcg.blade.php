        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-6 bg-gold-500 rounded-full"></div>
            <h2 class="font-serif text-gold-500 text-lg font-semibold tracking-wide">Análisis Estratégico de Catálogo — Matriz BCG</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
            @foreach ($bcgQuadrants as $key => $q)
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-5 text-center">
                <div class="w-10 h-10 rounded-xl mx-auto mb-2 flex items-center justify-center" style="background-color: {{ $q['color'] }}12; color: {{ $q['color'] }};">
                    <i class="fa-solid {{ $q['icon'] }}"></i>
                </div>
                <div class="text-gray-900 dark:text-gray-100 font-semibold text-sm">{{ $q['label'] }}</div>
                <div class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">{{ $q['desc'] }}</div>
                @php $count = count(array_filter($bcgMatrix, fn($b) => $b['quadrant'] === $key)); @endphp
                <div class="text-2xl font-bold mt-1 font-mono" style="color: {{ $q['color'] }};">{{ $count }}</div>
            </div>
            @endforeach
        </div>

        <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Matriz de Crecimiento vs. Participación</h3>
                <span class="text-xs text-gray-500 dark:text-gray-400">Tamaño del círculo = Revenue total</span>
            </div>
            <div class="relative h-[420px] w-full">
                <canvas id="bcgChart"></canvas>
            </div>
            <div class="flex items-center justify-center gap-6 mt-4 text-xs">
                @foreach ($bcgQuadrants as $q)
                <div class="flex items-center gap-1.5">
                    <span class="inline-block w-3 h-3 rounded-full" style="background-color: {{ $q['color'] }};"></span>
                    <span class="text-gray-500 dark:text-gray-400">{{ $q['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
