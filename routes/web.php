<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/pos', function () {
    return view('empleado.venta');
})->name('pos');

// vistas hijas
Route::get('/ventas', function () {
    return view('empleado.venta');
})->name('ventas');
Route::get('/productos', function () {
    return view('admin.productos');
})->name('productos');
Route::get('/reportes', function () {
    return view('admin.reportes');
})->name('reportes');
