<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\VentaController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\AuthController;

Route::post('login', [AuthController::class, 'login'])->name('api.login');
Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum')
    ->name('api.logout');
Route::prefix('usuarios')->group(function () {
    Route::get('/', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::post('/', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::put('{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
});
Route::middleware('auth:sanctum')->group(function() {
    Route::prefix('productos')->group(function () {
        Route::get('/', [ProductoController::class, 'index'])->name('productos.index');
        Route::post('/', [ProductoController::class, 'store'])->name('productos.store');
        Route::put('{producto}', [ProductoController::class, 'update'])->name('productos.update');
        Route::delete('{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    });
    
    route::post('venta',[VentaController::class, 'store'])
        ->name('venta.store');

    Route::prefix('reportes')->group(function(){
        // ...
    });
});
