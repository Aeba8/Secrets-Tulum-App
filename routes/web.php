<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HotelWebController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SecretsPad
|--------------------------------------------------------------------------
*/

// 🚪 Rutas Públicas de Autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 📱 Rutas Protegidas de la aplicación (Exigen inicio de sesión y Roles válidos)
Route::middleware(['auth', 'role:Admin'])->group(function () {});

Route::middleware(['auth', 'role:Operativo'])->group(function () {
    Route::get('/welcome', function () {
        return view('ipad.welcome');
    })->name('welcome');
    Route::get('/catalogo', [HotelWebController::class, 'mostrarCatalogo'])->name('catalogo');
    Route::get('/hotel/mapa-espacios', function () {
        return view('ipad.mapa');
    })->name('mapa.espacios');
    // 🌟 Ruta para el detalle de la cama balinesa (Agregamos alias claro para usar con route())
    Route::get('/hotel/detalle-balinesa/{slug}', [HotelWebController::class, 'detalleBalinesa'])
        ->name('paquetes.detalleBalinesa');

    // 🌟 NUEVA RUTA: Detalle de Cenas Románticas / Especiales
    Route::get('/hotel/detalle-cena/{slug}', [HotelWebController::class, 'detalleCena'])
        ->name('paquetes.detalleCena');

    Route::middleware(['auth', 'role:Operativo'])->group(function () {
        // ... tus rutas actuales

        // 🌟 NUEVA RUTA: Detalle de Experiencias VIP
        Route::get('/hotel/detalle-experiencia/{slug}', [HotelWebController::class, 'detalleExperiencia'])
            ->name('paquetes.detalleExperiencia');

        Route::get('/hotel/experiencias-vip', function () {
            return view('ipad.experiencias');
        })->name('paquetes.experiencias');
    });

    // Carga las estructuras limpias de las vistas
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

// Redirección por defecto: si entran a la raíz, los mandamos al login
Route::get('/', function () {
    return redirect()->route('login');
});
