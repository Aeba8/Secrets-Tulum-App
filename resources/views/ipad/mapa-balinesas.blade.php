@php
    // Capturamos los datos que vienen desde la URL con fallbacks seguros para evitar fallas
    $packageSlug = request('package', '');
    $packageId = request('package_id') ?? (request('id') ?? 1);
    $lang = request('lang', 'es');
@endphp

<!DOCTYPE html>
<html lang="{{ $lang }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Secrets Pad - Mapa de Balinesas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght=300;400;500;600&display=swap');

        .font-sans {
            font-family: 'Montserrat', sans-serif;
        }

        .secrets-bg {
            /* Capa negra superpuesta combinada con la imagen de fondo */
            background-image: linear-gradient(rgba(10, 8, 9, 0.75), rgba(10, 8, 9, 0.85)), 
                              url('{{ asset('storage/Secrets Tulum.jpg') }}'); /* <-- Pon aquí la ruta de tu imagen */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            
            /* El efecto de difuminado/desenfoque sutil */
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        /* Animaciones Premium */
        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-exit {
            animation: pageExit 0.4s ease-in forwards;
        }

        @keyframes pageExit {
            from {
                opacity: 1;
                transform: scale(1);
            }

            to {
                opacity: 0;
                transform: scale(0.98);
            }
        }
    </style>
</head>

<body class="bg-[#0A0809] text-white font-sans antialiased min-h-screen overflow-x-hidden secrets-bg fade-in">

    <header
        class="w-full border-b border-white/5 bg-black/40 backdrop-blur-md sticky top-0 z-50 px-8 py-4 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <button onclick="regresarAlDetalle()"
                class="w-10 h-10 border border-white/10 rounded-full flex items-center justify-center bg-white/5 hover:bg-[#C5A059]/20 hover:border-[#C5A059] transition-all duration-300 group">
                <i class="fa-solid fa-arrow-left text-xs text-stone-400 group-hover:text-white transition-colors"></i>
            </button>
            <div>
                <span class="text-[10px] font-bold tracking-widest text-[#C5A059] uppercase" id="lbl-category">Camas
                    Balinesas</span>
                <h1 class="text-base font-semibold tracking-wide text-white/90 uppercase" id="lbl-title">Selección de
                    Ubicación</h1>
            </div>
        </div>

        <div class="flex items-center gap-3 bg-neutral-900/80 border border-white/10 px-4 py-2 rounded-xl">
            <i class="fa-solid fa-calendar-days text-xs text-[#C5A059]"></i>
            <input type="date" id="fecha_reserva" onchange="cargarDisponibilidad()"
                value="{{ request('fecha', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}"
                class="bg-transparent text-xs font-semibold tracking-wide text-white focus:outline-none cursor-pointer [color-scheme:dark]">
        </div>
    </header>

    <main class="w-full px-8 py-6 grid grid-cols-7 gap-6 max-w-[1400px] mx-auto min-h-[calc(screen-120px)]">

        <div
            class="col-span-5 bg-black/20 border border-white/5 rounded-2xl p-6 backdrop-blur-sm shadow-2xl flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-white/5 pb-4 mb-4">
                    <h2 class="text-xs font-bold tracking-widest text-stone-400 uppercase">Distribución Física</h2>

                    <div
                        class="flex items-center gap-4 text-[10px] font-medium tracking-wider uppercase text-stone-400">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-neutral-800 border border-white/10"></span> Disponible
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-950 border border-red-500/30"></span> Vendido
                        </div>
                    </div>
                </div>

                <div id="map-grid"
                    class="grid grid-cols-5 gap-4 h-full content-start max-h-[580px] overflow-y-auto pr-2">
                </div>
            </div>
        </div>

        <div class="col-span-2 flex flex-col gap-4">

            <div
                class="bg-gradient-to-b from-neutral-900/90 to-neutral-950/90 border border-white/5 rounded-2xl p-5 shadow-xl">
                <h3 class="text-[10px] font-bold tracking-widest text-[#C5A059] uppercase mb-4">Espacio Seleccionado
                </h3>
                <div class="flex items-center gap-4 bg-black/30 border border-white/5 p-4 rounded-xl">
                    <div
                        class="w-12 h-12 bg-[#C5A059]/10 border border-[#C5A059]/20 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-umbrella-beach text-lg text-[#C5A059]"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-medium text-stone-500 uppercase tracking-wider block">ID /
                            Nombre</span>
                        <p class="text-white text-sm font-semibold tracking-wide mt-0.5" id="selected-space-name">-</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-b from-neutral-900/90 to-neutral-950/90 border border-white/5 rounded-2xl p-5 shadow-xl flex-1 flex flex-col justify-between">
                <div class="space-y-4">
                    <h3 class="text-[10px] font-bold tracking-widest text-[#C5A059] uppercase mb-2">Detalles de
                        Operación</h3>

                    <div>
                        <label
                            class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest block mb-2">Número
                            de Habitación <span class="text-red-800">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-door-open absolute left-4 top-3.5 text-xs text-stone-500"></i>
                            <input type="text" id="input-habitacion" placeholder="Ej: 1102 (4 dígitos)"
                                maxlength="4" pattern="\d{4}" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full bg-black/40 border border-white/10 rounded-xl pl-10 pr-4 py-3 text-xs font-semibold tracking-wide text-white focus:outline-none focus:border-[#C5A059] transition-all">
                        </div>
                    </div>

                    <div>
                        <label
                            class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest block mb-2">Número
                            de Colaborador <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-id-card absolute left-4 top-3.5 text-xs text-stone-500"></i>
                            <input type="text" id="input-colaborador" placeholder="Ej: 104509 (6 dígitos)"
                                maxlength="6" pattern="\d{6}" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full bg-black/40 border border-white/10 rounded-xl pl-10 pr-4 py-3 text-xs font-semibold tracking-wide text-white focus:outline-none focus:border-[#C5A059] transition-all">
                        </div>
                    </div>

                    <div>
                        <label
                            class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest block mb-2">Observaciones
                            <span class="text-stone-600 text-[9px]">(Opcional)</span></label>
                        <div class="relative">
                            <textarea id="input-observaciones" placeholder="Ej: Pax alérgico, festejo de aniversario, etc..." rows="3"
                                class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-xs font-semibold tracking-wide text-white focus:outline-none focus:border-[#C5A059] transition-all resize-none"></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-white/5">
                    <button id="btn-submit-booking" onclick="ejecutarReservacion()" disabled
                        class="w-full bg-neutral-800 border border-white/5 text-stone-500 font-semibold text-xs uppercase tracking-widest py-4 rounded-xl flex items-center justify-center gap-2 transition-all duration-300 cursor-not-allowed opacity-50">
                        <i class="fa-solid fa-lock text-[10px] opacity-40" id="btn-icon"></i>
                        <span id="btn-text">Confirmar Espacio</span>
                    </button>
                </div>
            </div>

        </div>
    </main>

    <!-- 🌟 CONTENEDOR DE ALERTAS FLOTANTES (TOASTS) -->
    <div id="toast-container" class="fixed top-24 right-8 z-[100] flex flex-col gap-3 pointer-events-none"></div>

    <!-- 🌟 MODAL DE CONFIRMACIÓN PREMIUM -->
    <div id="confirm-modal"
        class="fixed inset-0 z-[90] hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-md transition-opacity duration-300 opacity-0">
        <div
            class="bg-gradient-to-b from-neutral-900 to-neutral-950 border border-white/10 rounded-2xl p-6 max-w-sm w-full shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="flex items-center gap-3 mb-4">
                <div
                    class="w-10 h-10 bg-[#C5A059]/10 border border-[#C5A059]/20 rounded-xl flex items-center justify-center text-[#C5A059]">
                    <i class="fa-solid fa-circle-question text-lg"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wide text-white">¿Confirmar Reserva?</h4>
                    <p class="text-[11px] text-stone-400 mt-0.5">La cama balinesa seleccionada quedará bloqueada de
                        inmediato.</p>
                </div>
            </div>

            <!-- Detalles a confirmar dentro del modal -->
            <div
                class="bg-black/30 border border-white/5 p-3 rounded-xl mb-6 space-y-1.5 text-[11px] font-medium tracking-wide uppercase text-stone-300">
                <div class="flex justify-between">
                    <span class="text-stone-500">Espacio:</span>
                    <span id="modal-space-name" class="text-white font-semibold">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-500">Habitación:</span>
                    <span id="modal-room-number" class="text-white font-semibold">-</span>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex gap-3">
                <button onclick="cerrarConfirmacionModal()"
                    class="flex-1 bg-neutral-800 hover:bg-neutral-700 text-stone-300 font-bold text-[10px] uppercase tracking-widest py-3 rounded-xl transition-all">
                    Cancelar
                </button>
                <button onclick="enviarDatosAlServidor()"
                    class="flex-1 bg-[#C5A059] hover:bg-[#b08e4f] text-black font-bold text-[10px] uppercase tracking-widest py-3 rounded-xl shadow-[0_4px_15px_rgba(197,160,89,0.2)] transition-all">
                    Sí, Confirmar
                </button>
            </div>
        </div>
    </div>

    <script>
        const packageSlug = "{{ $packageSlug }}";
        const packageId = parseInt("{{ $packageId }}") || 1;
        const currentLang = "{{ $lang }}";
        const serviciableType = "App\\Models\\Balinesa";

        const translations = {
            es: {
                no_spaces: "No hay espacios configurados en esta zona.",
                error: "Error fatal al conectar con el servidor.",
                select_space: "Selecciona una cama balinesa para proceder",
                ready_btn: "Confirmar Reserva",
                booking_btn: "Confirmar Espacio",
                processing: "Procesando...",
                success_alert: "¡Reservación creada con éxito de manera segura!",
                occupied_alert: "Este espacio ya se encuentra ocupado por otra habitación.",
                validation_habitacion: "El número de habitación debe contener exactamente 4 números.",
                validation_colaborador: "El número de colaborador debe contener exactamente 6 números."
            },
            en: {
                no_spaces: "No spaces configured in this zone.",
                error: "Fatal error connecting to the server.",
                select_space: "Select a balinese bed to proceed",
                ready_btn: "Confirm Booking",
                booking_btn: "Confirm Space",
                processing: "Processing...",
                success_alert: "Reservation created successfully and safely!",
                occupied_alert: "This space is already occupied by another room.",
                validation_habitacion: "The room number must contain exactly 4 digits.",
                validation_colaborador: "The collaborator number must contain exactly 6 digits."
            }
        };

        const t = translations[currentLang] || translations.es;

        let selectedSpaceId = null;
        let selectedSpaceName = "";
        let enviando = false;

        document.addEventListener("DOMContentLoaded", () => {
            // Obtener la fecha local de hoy en formato YYYY-MM-DD sin desfases de zona horaria
            const hoy = new Date();
            const offset = hoy.getTimezoneOffset();
            const hoyLocal = new Date(hoy.getTime() - (offset * 60 * 1000)).toISOString().split('T')[0];

            const inputFecha = document.getElementById('fecha_reserva');
            if (inputFecha) {
                // Forzar el mínimo permitido de manera dinámica
                inputFecha.min = hoyLocal;

                // Si la fecha que trae la URL es del pasado, la corregimos automáticamente a hoy
                if (inputFecha.value < hoyLocal) {
                    inputFecha.value = hoyLocal;
                }
            }

            cargarDisponibilidad();
        });

        function cargarDisponibilidad() {
            let fechaInput = document.getElementById('fecha_reserva')?.value;

            if (!fechaInput) {
                const hoy = new Date();
                const yyyy = hoy.getFullYear();
                const mm = String(hoy.getMonth() + 1).padStart(2, '0');
                const dd = String(hoy.getDate()).padStart(2, '0');
                fechaInput = `${yyyy}-${mm}-${dd}`;
            }

            const fechaSeleccionada = fechaInput;
            const container = document.getElementById('map-grid');

            selectedSpaceId = null;
            selectedSpaceName = "";
            actualizarUIBotón();

            container.innerHTML = `
                <div class="col-span-5 text-center py-12">
                    <i class="fa-solid fa-circle-notch text-[#C5A059] text-xl animate-spin"></i>
                </div>
            `;

            const origin = window.location.origin;
            const url =
                `${origin}/hotel/internal-api/espacios-disponibles?type=${encodeURIComponent(serviciableType)}&id=${packageId}&fecha=${fechaSeleccionada}`;

            fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                    return res.json();
                })
                .then(response => {
                    container.innerHTML = '';

                    if (!response.success || !response.secciones || Object.keys(response.secciones).length === 0) {
                        container.innerHTML =
                            `<div class="col-span-5 text-center text-white/40 text-xs uppercase tracking-widest font-medium py-12">${t.no_spaces}</div>`;
                        return;
                    }

                    for (const [nombreSeccion, espacios] of Object.entries(response.secciones)) {
                        const seccionHeader = document.createElement('div');
                        seccionHeader.className = "col-span-5 mt-6 mb-3 border-b border-white/5 pb-2";
                        seccionHeader.innerHTML = `
                        <h3 class="text-xs font-bold tracking-widest uppercase text-[#C5A059] flex items-center gap-2">
                            <i class="fa-solid fa-map-pin text-[10px]"></i> ${nombreSeccion}
                        </h3>
                    `;
                        container.appendChild(seccionHeader);

                        espacios.forEach(espacio => {
                            const isOcupado = !espacio.disponible;
                            const id = espacio.id_espacio;
                            const nombre = espacio.nombre;

                            let cardStyle =
                                "bg-neutral-900 border-white/5 text-stone-400 hover:border-[#C5A059]/30 hover:text-white cursor-pointer";
                            let iconColor = "text-stone-600";

                            if (isOcupado) {
                                cardStyle =
                                    "bg-red-950/20 border-red-500/20 text-red-400/40 cursor-not-allowed opacity-60";
                                iconColor = "text-red-500/30 shadow-[0_0_10px_rgba(239,68,68,0.2)]";
                            }

                            const card = document.createElement('div');
                            card.id = `space-${id}`;
                            card.className =
                                `w-full p-4 border rounded-xl flex flex-col items-center justify-center gap-2 transition-all duration-300 group ${cardStyle}`;

                            if (!isOcupado) {
                                card.onclick = () => seleccionarEspacio(id, nombre);
                            }

                            card.innerHTML = `
                            <i class="fa-solid fa-umbrella-beach text-xl ${iconColor} group-hover:scale-110 transition-transform duration-300"></i>
                            <span class="text-[10px] font-semibold tracking-wider uppercase text-center">${nombre}</span>
                        `;
                            container.appendChild(card);
                        });
                    }
                })
                .catch(err => {
                    console.error("Fatal Error fetching data:", err);
                    container.innerHTML =
                        `<div class="col-span-5 text-center text-red-500 text-xs font-semibold uppercase py-8">${t.error}</div>`;
                });
        }

        function seleccionarEspacio(id, nombre) {
            if (selectedSpaceId) {
                const prevCard = document.getElementById(`space-${selectedSpaceId}`);
                if (prevCard) {
                    prevCard.classList.remove('border-[#C5A059]', 'bg-[#C5A059]/5');
                }
            }

            selectedSpaceId = id;
            selectedSpaceName = nombre;

            const currentCard = document.getElementById(`space-${id}`);
            if (currentCard) {
                currentCard.classList.add('border-[#C5A059]', 'bg-[#C5A059]/5');
            }

            actualizarUIBotón();
        }

        function actualizarUIBotón() {
            const btnSubmit = document.getElementById('btn-submit-booking');
            const btnText = document.getElementById('btn-text');
            const btnIcon = document.getElementById('btn-icon');
            const spaceNamePlaceholder = document.getElementById('selected-space-name');

            if (!btnSubmit || !btnText || !btnIcon || !spaceNamePlaceholder) return;

            if (selectedSpaceId) {
                spaceNamePlaceholder.innerText = selectedSpaceName;
                btnSubmit.disabled = false;
                btnSubmit.className =
                    "w-full bg-[#C5A059] text-black font-bold text-xs uppercase tracking-widest py-4 rounded-xl flex items-center justify-center gap-2 hover:bg-[#b08e4f] shadow-[0_4px_20px_rgba(197,160,89,0.3)] transition-all duration-300 cursor-pointer";
                btnIcon.className = "fa-solid fa-unlock text-[10px] opacity-80";
                btnText.innerText = t.ready_btn;
            } else {
                spaceNamePlaceholder.innerText = "-";
                btnSubmit.disabled = true;
                btnSubmit.className =
                    "w-full bg-neutral-800 border border-white/5 text-stone-500 font-semibold text-xs uppercase tracking-widest py-4 rounded-xl flex items-center justify-center gap-2 transition-all duration-300 cursor-not-allowed opacity-50";
                btnIcon.className = "fa-solid fa-lock text-[10px] opacity-40";
                btnText.innerText = t.booking_btn;
            }
        }

        // 🌟 FUNCIÓN 1: Crea y muestra notificaciones flotantes premium
        function showToast(mensaje, tipo = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `flex items-center gap-3 px-4 py-3 border rounded-xl shadow-2xl pointer-events-auto transform translate-y-2 opacity-0 transition-all duration-300 max-w-xs ${
        tipo === 'success' 
            ? 'bg-neutral-900/90 border-emerald-500/30 text-emerald-400 shadow-emerald-950/20' 
            : 'bg-neutral-900/90 border-red-500/30 text-red-400 shadow-red-950/20'
    }`;

            const icon = tipo === 'success' ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-exclamation';

            toast.innerHTML = `
        <i class="${icon} text-sm"></i>
        <p class="text-[11px] font-semibold tracking-wide uppercase">${mensaje}</p>
    `;

            container.appendChild(toast);

            // Animación de entrada
            setTimeout(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
            }, 50);

            // Salida automática a los 3.5 segundos
            setTimeout(() => {
                toast.classList.add('translate-y-[-10px]', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }

        // 🌟 FUNCIÓN 2: Valida los campos y abre el Modal de Confirmación
        function ejecutarReservacion() {
            const habitacion = document.getElementById('input-habitacion')?.value.trim();
            const colaborador = document.getElementById('input-colaborador')?.value.trim();

            if (!selectedSpaceId) return;

            // Validaciones expresas de dígitos en el frontend
            const regexHabitacion = /^\d{4}$/;
            const regexColaborador = /^\d{6}$/;

            if (!regexHabitacion.test(habitacion)) {
                showToast(t.validation_habitacion, 'error');
                return;
            }

            if (!regexColaborador.test(colaborador)) {
                showToast(t.validation_colaborador, 'error');
                return;
            }

            // Inyectar datos dinámicos al modal estético
            document.getElementById('modal-space-name').innerText = selectedSpaceName;
            document.getElementById('modal-room-number').innerText = habitacion;

            // Mostrar el modal con transiciones fluidas
            const modal = document.getElementById('confirm-modal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('div').classList.remove('scale-95');
            }, 10);
        }

        // 🌟 FUNCIÓN 3: Oculta el modal de confirmación
        function cerrarConfirmacionModal() {
            const modal = document.getElementById('confirm-modal');
            if (!modal) return;

            modal.classList.add('opacity-0');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        // 🌟 FUNCIÓN 4: Envía los datos finales al servidor tras dar "Sí, Confirmar"
        function enviarDatosAlServidor() {
            if (enviando) return;
            enviando = true;
            const habitacion = document.getElementById('input-habitacion')?.value.trim();
            const colaborador = document.getElementById('input-colaborador')?.value.trim();
            const observaciones = document.getElementById('input-observaciones')?.value.trim();
            const fechaInput = document.getElementById('fecha_reserva')?.value;

            cerrarConfirmacionModal();

            const btnSubmit = document.getElementById('btn-submit-booking');
            const btnText = document.getElementById('btn-text');
            if (btnSubmit && btnText) {
                btnSubmit.disabled = true;
                btnText.innerText = t.processing;
            }

            const payload = {
                serviciable_type: serviciableType,
                serviciable_id: packageId,
                id_espacio: parseInt(selectedSpaceId),
                fecha: fechaInput,
                habitacion: habitacion,
                numero_colaborador_vendedor: colaborador,
                observaciones: observaciones || null
            };

            const origin = window.location.origin;
            const urlDestino = `${origin}/hotel/internal-api/reservar`;

            fetch(urlDestino, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errData => {
                            throw errData;
                        });
                    }
                    return response.json();
                })
                .then(res => {
                    enviando = false;
                    if (res.success) {
                        showToast(t.success_alert, 'success');
                        document.body.classList.add('page-exit');
                        setTimeout(() => {
                            window.location.href = `{{ route('paquetes.balinesas') }}?lang=${currentLang}`;
                        }, 1200);
                    } else {
                        showToast(res.message || t.occupied_alert, 'error');
                        if (btnSubmit && btnText) {
                            btnSubmit.disabled = false;
                            btnText.innerText = t.booking_btn;
                        }
                        cargarDisponibilidad();
                    }
                })
                .catch(err => {
                    enviando = false;
                    console.error("Error:", err);
                    const errorMsg = (err && err.message) ? err.message : "Fallo de conexión al guardar.";
                    showToast(errorMsg, 'error');

                    if (btnSubmit && btnText) {
                        btnSubmit.disabled = false;
                        btnText.innerText = t.booking_btn;
                    }
                });
        }

        function regresarAlDetalle() {
            document.body.classList.add('page-exit');
            setTimeout(() => {
                window.location.href = `/hotel/detalle-balinesa/${packageSlug}?lang=${currentLang}`;
            }, 400);
        }
    </script>



</body>

</html>
