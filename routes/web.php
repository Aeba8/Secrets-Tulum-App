<?php

use App\Http\Controllers\API\ExperienciaReservaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\API\ReservaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HotelWebController;
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
Route::post('/login', [AuthController::class, 'login']);
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
});
