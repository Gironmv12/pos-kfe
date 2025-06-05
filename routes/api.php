<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\VentaController;
use App\Http\Controllers\Api\UsuarioController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('ventas')->group(function(){

});

Route::apiResource('productos', ProductoController::class);

Route::prefix('reportes')->group(function(){
    
});