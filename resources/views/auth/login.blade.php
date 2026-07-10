<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secrets Tulum - Login Colaboradores</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <<style>
        @import
        url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Montserrat:wght@300;400;500&display=swap');

        .font-luxury {
        font-family: 'Cinzel', serif;
        }

        .font-sans {
        font-family: 'Montserrat', sans-serif;
        }



        /* ---- AQUÍ SE QUITA EL OJO NATIVO ---- */
        * Elimina la llave/ojo de Chrome y Safari cuando la cuenta está guardada */
        input::-webkit-credentials-filter-button,
        input::-webkit-password-toggle-button,
        input::-webkit-strong-password-auto-fill-button,
        input::-webkit-contacts-auto-fill-button {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        -webkit-appearance: none !important;
        pointer-events: none !important;
        width: 0 !important;
        height: 0 !important;
        }

        /* ---- 🌟 REGLA MAESTRA PARA CUENTAS GUARDADAS (AUTOFILL) ---- */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
        /* Mantiene el texto blanco de tu diseño */
        -webkit-text-fill-color: #ffffff !important;

        /* Forza un fondo oscuro elegante en lugar del blanco/azul feo del navegador */
        -webkit-box-shadow: 0 0 0px 1000px #161616 inset !important;
        box-shadow: 0 0 0px 1000px #161616 inset !important;

        /* Evita parpadeos visuales */
        transition: background-color 5000s ease-in-out 0s;
        }

        /* Para Edge y navegadores de Microsoft */
        input::-ms-reveal,
        input::-ms-clear {
        display: none !important;
        }

        </style>
</head>

<body
    class="font-sans h-screen w-screen overflow-hidden flex items-center justify-center bg-cover bg-center bg-no-repeat"
    style="background-image: url('{{ asset('storage/Secrets Tulum.jpg') }}'); /* Reemplaza por tu render de Secrets */">

    <div class="absolute inset-0 bg-black/40 backdrop-blur-xs"></div>

    <div
        class="relative z-10 w-full max-w-md mx-4 p-8 rounded-3xl bg-black/20 border border-white/10 backdrop-blur-md shadow-2xl text-center">

        <h1 class="font-luxury text-3xl tracking-widest text-[#D4AF37] font-semibold">SECRETS</h1>
        <h2 class="font-luxury text-xl tracking-widest text-[#D4AF37] opacity-90 -mt-1 mb-6">TULUM</h2>

        <p class="text-white/80 text-xs tracking-wider uppercase font-medium mb-8">Login Colaboradores</p>

        <form action="{{ url('/login') }}" method="POST" class="space-y-5 text-left">
            @csrf

            <div>
                <label class="block text-white/90 text-xs font-light tracking-wide mb-1">Nombre o Email</label>
                <input type="text" name="login_input" required value="{{ old('login_input') }}"
                    placeholder="ejemplo@secrets.com"
                    class="w-full px-4 py-3 rounded-xl bg-black/40 border border-white/20 text-white placeholder-white/30 text-sm focus:outline-none focus:border-[#D4AF37] transition-all">
                @error('login_input')
                    <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-white/90 text-xs font-light tracking-wide mb-1">Número de colaborador</label>
                <div class="relative flex items-center">
                    <input type="password" id="numero_colaborador" name="numero_colaborador" required minlength="6"
                        required maxlength="6" placeholder="******"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
                        class="w-full pl-4 pr-12 py-3 rounded-xl bg-black/40 border border-white/20 text-white placeholder-white/30 text-sm focus:outline-none focus:border-[#D4AF37] transition-all tracking-widest">

                    <button type="button" onclick="togglePassword()"
                        class="absolute right-4 z-30 text-white/70 hover:text-white focus:outline-none cursor-pointer">
                        <i id="eye_icon" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="w-full mt-4 py-3 px-4 rounded-full bg-gradient-to-r from-[#B8860B] via-[#D4AF37] to-[#AA7C11] text-neutral-900 font-semibold tracking-widest text-xs uppercase shadow-lg shadow-yellow-900/20 hover:brightness-110 active:scale-[0.99] transition-all flex items-center justify-center gap-2 cursor-pointer">
                <span>Iniciar Sesión</span>
                <i class="fa-solid fa-arrow-right-to-bracket text-sm"></i>
            </button>
        </form>
    </div>


</body>

<script>
    // 1. Limpia los inputs cada vez que se carga la página o se regresa a ella
    window.addEventListener('pageshow', function(event) {
        // Borra los campos de texto
        document.querySelectorAll('input').forEach(input => {
            if (input.name !== '_token') {
                input.value = '';
            }
        });
    });

    // 2. Controla el botón para mostrar/ocultar la contraseña
    function togglePassword() {
        const passwordInput = document.getElementById('numero_colaborador');
        const eyeIcon = document.getElementById('eye_icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>

</html>
