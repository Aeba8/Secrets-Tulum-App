<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelWebController;

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
    Route::get('/welcome', function () { return view('ipad.welcome'); })->name('welcome');
    Route::get('/catalogo', [HotelWebController::class, 'mostrarCatalogo'])->name('catalogo');
    Route::get('/hotel/mapa-espacios', function () { return view('ipad.mapa'); })->name('mapa.espacios');

    // Carga las estructuras limpias de las vistas
    Route::get('/hotel/balinesas', function () { return view('ipad.balinesas'); })->name('paquetes.balinesas');
    Route::get('/hotel/cenas-especiales', function () { return view('ipad.cenas'); })->name('paquetes.cenas');
    Route::get('/hotel/experiencias-vip', function () { return view('ipad.experiencias'); })->name('paquetes.experiencias');
});

// Redirección por defecto: si entran a la raíz, los mandamos al login
Route::get('/', function () {
    return redirect()->route('login');
});
