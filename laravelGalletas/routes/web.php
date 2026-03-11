<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\InventarioDiarioController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\PagoCreditoController;

Route::resource('productos', ProductoController::class);
Route::resource('clientes', ClienteController::class);
Route::resource('inventario', InventarioDiarioController::class);
Route::resource('ventas', VentaController::class);
Route::resource('pagos_credito', PagoCreditoController::class);

Route::get('/', function () {
    return view('welcome');
});