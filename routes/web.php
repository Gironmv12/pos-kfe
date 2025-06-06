<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/pos', function () {
    return view('pos');
})->name('pos');

//vista venta
Route::get('/ventas', function () {
    return view('empleado.venta');
})->name('ventas');
//vista productos
Route::get('/productos', function () {
    return view('admin.productos');
})->name('productos');
//vista reportes
Route::get('/reportes', function () {
    return view('admin.reportes');
})->name('reportes');
