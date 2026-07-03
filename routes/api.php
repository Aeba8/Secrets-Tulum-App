<?php

use App\Http\Controllers\API\CatalogController;
use App\Http\Controllers\API\ReservaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - SecretsPad
|--------------------------------------------------------------------------
*/

// Consulta de usuario autenticado vía API (Sanctum)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Catálogo público de la iPad
Route::get('/hotel/catalog', [CatalogController::class, 'index']);

// 🔒 GRUPO PROTEGIDO CON SANCTUM (Para peticiones móviles nativas estrictas)
Route::middleware('auth:sanctum')->group(function () {
    
    

});