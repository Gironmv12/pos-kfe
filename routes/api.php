<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\VentaController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\AuthController;

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::apiResource('usuarios', UsuarioController::class);
Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('productos', ProductoController::class);
    

    Route::prefix('ventas')->group(function(){
        // ...
    });

    Route::prefix('reportes')->group(function(){
        // ...
    });
});
