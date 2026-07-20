<?php

use App\Http\Controllers\Admin\BalinesaController;
use App\Http\Controllers\Admin\CenaEspecialController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExperienciaController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\EspacioController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\API\ExperienciaReservaController;
use App\Http\Controllers\API\ReservaController;
use App\Http\Controllers\API\TerminosController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HotelWebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SecretsPad
|--------------------------------------------------------------------------
*/

// 🌟 SOLUCIÓN AL ERROR 1: Atrapa la raíz y redirige automáticamente al login
Route::get('/', function () {
    return redirect()->route('login');
});

// 🚪 Rutas Públicas de Autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 📱 Rutas Protegidas de la aplicación (Operativo / iPad)
Route::middleware(['auth', 'role:Operativo'])->group(function () {

    Route::get('/welcome', function () {
        return view('ipad.welcome');
    })->name('welcome');

    Route::get('/catalogo', [HotelWebController::class, 'mostrarCatalogo'])->name('catalogo');

    Route::get('/hotel/mapa-espacios', function () {
        return view('ipad.mapa-balinesas');
    })->name('mapa.espacios');

    // En web.php (Dentro del grupo de middleware 'auth' y 'role:Operativo')
    Route::get('/hotel/mapa-mesas', function () {
        return view('ipad.mapa-cenas'); // Apunta a tu nueva vista
    })->name('mapa.mesas');

    // 🌟 API Interna Segura: Consulta disponibilidad usando la sesión activa del navegador
    Route::get('/hotel/internal-api/espacios-disponibles', [ReservaController::class, 'checkDisponibilidad'])
        ->name('api.espacios.disponibles');

    // 🌟 NUEVA RUTA: Guarda la reservación usando la misma sesión activa de la iPad
    Route::post('/hotel/internal-api/reservar', [ReservaController::class, 'store'])
        ->name('api.espacios.reservar');

    // 🌟 NUEVA RUTA EXCLUSIVA: Para reservar experiencias VIP sin mapa
    Route::post('/hotel/internal-api/reservar-experiencia', [ExperienciaReservaController::class, 'store'])
        ->name('api.experiencias.reservar');

    // 🌟 Términos y Condiciones
    Route::get('/hotel/internal-api/terminos', [TerminosController::class, 'show'])
        ->name('api.terminos');

    // Detalles de los Paquetes
    Route::get('/hotel/detalle-balinesa/{slug}', [HotelWebController::class, 'detalleBalinesa'])
        ->name('paquetes.detalleBalinesa');

    Route::get('/hotel/detalle-cena/{slug}', [HotelWebController::class, 'detalleCena'])
        ->name('paquetes.detalleCena');

    Route::get('/hotel/detalle-experiencia/{slug}', [HotelWebController::class, 'detalleExperiencia'])
        ->name('paquetes.detalleExperiencia');

    // Vistas Estructurales de Categorías
    Route::get('/hotel/balinesas', function () {
        return view('ipad.balinesas');
    })->name('paquetes.balinesas');

    Route::get('/hotel/cenas-especiales', function () {
        return view('ipad.cenas');
    })->name('paquetes.cenas');

    Route::get('/hotel/experiencias-vip', function () {
        return view('ipad.experiencias');
    })->name('paquetes.experiencias');
});

// 🔐 Rutas del Panel de Administración (exclusivo Admin/SuperAdmin)
Route::middleware(['auth', 'role:Admin,SuperAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export/{section}', [DashboardController::class, 'export'])->name('dashboard.export');

    Route::post('/balinesas', [BalinesaController::class, 'store'])->name('balinesas.store');
    Route::put('/balinesas/{id}', [BalinesaController::class, 'update'])->name('balinesas.update');
    Route::delete('/balinesas/{id}', [BalinesaController::class, 'destroy'])->name('balinesas.destroy');
    Route::patch('/balinesas/{id}/activate', [BalinesaController::class, 'activate'])->name('balinesas.activate');

    Route::post('/cenas', [CenaEspecialController::class, 'store'])->name('cenas.store');
    Route::put('/cenas/{id}', [CenaEspecialController::class, 'update'])->name('cenas.update');
    Route::delete('/cenas/{id}', [CenaEspecialController::class, 'destroy'])->name('cenas.destroy');
    Route::patch('/cenas/{id}/activate', [CenaEspecialController::class, 'activate'])->name('cenas.activate');

    Route::post('/experiencias', [ExperienciaController::class, 'store'])->name('experiencias.store');
    Route::put('/experiencias/{id}', [ExperienciaController::class, 'update'])->name('experiencias.update');
    Route::delete('/experiencias/{id}', [ExperienciaController::class, 'destroy'])->name('experiencias.destroy');
    Route::patch('/experiencias/{id}/activate', [ExperienciaController::class, 'activate'])->name('experiencias.activate');

    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    Route::patch('/usuarios/{id}/activate', [UsuarioController::class, 'activate'])->name('usuarios.activate');

    Route::post('/espacios', [EspacioController::class, 'store'])->name('espacios.store');
    Route::put('/espacios/{id}', [EspacioController::class, 'update'])->name('espacios.update');
    Route::delete('/espacios/{id}', [EspacioController::class, 'destroy'])->name('espacios.destroy');
    Route::patch('/espacios/{id}/activate', [EspacioController::class, 'activate'])->name('espacios.activate');

    Route::post('/reordenar/{modelo}', function (Request $request, $modelo) {
        $clases = [
            'balinesas'    => App\Models\Balinesa::class,
            'cenas'        => App\Models\CenaEspecial::class,
            'experiencias' => App\Models\Experiencia::class,
            'espacios'     => App\Models\Espacio::class,
        ];
        $clase = $clases[$modelo] ?? abort(404);
        foreach ($request->input('orden', []) as $pos => $id) {
            $clase::where('Id', $id)->update(['Orden' => $pos]);
        }
        return response()->json(['ok' => true]);
    })->name('reordenar');

    // Agenda (Reservas CRUD)
    Route::post('/agenda', [AgendaController::class, 'store'])->name('agenda.store');
    Route::put('/agenda/{id}', [AgendaController::class, 'update'])->name('agenda.update');
    Route::delete('/agenda/{id}', [AgendaController::class, 'destroy'])->name('agenda.destroy');
    Route::patch('/agenda/{id}/activate', [AgendaController::class, 'activate'])->name('agenda.activate');

    // Configuración
    Route::put('/settings/terminos', [SettingController::class, 'updateTerminos'])->name('settings.terminos');
});
