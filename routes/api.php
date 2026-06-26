<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CatalogController;
use App\Http\Controllers\API\ReservaController;

// Consulta de usuario autenticado
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Catálogo público de la iPad
Route::get('/hotel/catalog', [CatalogController::class, 'index']);

// 🔒 GRUPO PROTEGIDO: Las rutas van DENTRO de las llaves del grupo
Route::middleware('auth:sanctum')->group(function () {
    
    // Endpoint para el mapa de camas/mesas disponibles
    Route::get('/hotel/espacios-disponibles', [ReservaController::class, 'checkDisponibilidad']);

    // Endpoint para procesar la reservación final con candado anti-overbooking
    Route::post('/hotel/reservar', [ReservaController::class, 'store']);
    
});