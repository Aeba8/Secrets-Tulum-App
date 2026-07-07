        <div id="crud-cenas" class="crud-panel">
            <div class="bg-white dark:bg-charcoal-600 border border-sand-200 dark:border-charcoal-500 rounded-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-sand-200 dark:border-charcoal-500">
                    <h3 class="text-gray-900 dark:text-gray-100 font-semibold text-sm">Locaciones de Cenas Especiales</h3>
                    <button class="text-xs px-3 py-1.5 rounded-lg bg-gold-500 text-white font-medium hover:bg-gold-600 transition-colors">
                        <i class="fa-solid fa-plus mr-1"></i> Nuevo
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-sand-200 dark:border-charcoal-500 bg-sand-50 dark:bg-charcoal-500">
                                <th class="text-left px-5 py-3 font-medium">Lugar</th>
                                <th class="text-left px-5 py-3 font-medium">Descripción</th>
                                <th class="text-right px-5 py-3 font-medium">Precio</th>
                                <th class="text-center px-5 py-3 font-medium">Capacidad</th>
                                <th class="text-center px-5 py-3 font-medium">Imágenes</th>
                                <th class="text-center px-5 py-3 font-medium">Estado</th>
                                <th class="text-center px-5 py-3 font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cenasEspeciales as $ce)
                            <tr class="border-b border-sand-200 dark:border-charcoal-500 last:border-0 hover:bg-sand-50 dark:hover:bg-charcoal-500 transition-colors">
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 font-medium">{{ $ce['lugar'] }}</td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 max-w-[200px] truncate">{{ $ce['descripcion'] }}</td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-right font-mono">${{ number_format($ce['precio']) }}</td>
                                <td class="px-5 py-3.5 text-gray-900 dark:text-gray-100 text-center font-mono">{{ $ce['capacidad'] }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    @if(!empty($ce['imagenes']))
                                        <div class="flex items-center justify-center gap-1">
                                            @foreach(array_slice($ce['imagenes'], 0, 3) as $img)
                                                <img src="{{ $img }}" class="w-8 h-8 rounded object-cover border border-sand-200 dark:border-charcoal-500" alt="">
                                            @endforeach
                                            @if(count($ce['imagenes']) > 3)
                                                <span class="text-xs text-gray-400 ml-1">+{{ count($ce['imagenes']) - 3 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="text-xs px-2 py-0.5 rounded-md border {{ $ce['status'] === 'Activo' ? 'bg-sapphire-50 dark:bg-sapphire-900/20 text-sapphire-600 dark:text-sapphire-400 border-sapphire-200 dark:border-sapphire-800' : 'bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700' }}">{{ $ce['status'] }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-2 text-gray-400 dark:text-gray-500">
                                        <button class="hover:text-gold-500 transition-colors"><i class="fa-solid fa-pen text-xs"></i></button>
                                        <button class="hover:text-red-500 transition-colors"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
