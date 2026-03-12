<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\InventarioDiarioController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\PagoCreditoController;

/*
|--------------------------------------------------------------------------
| Página principal
|--------------------------------------------------------------------------
*/
Route::get('/ventas/todos', [VentaController::class, 'todosPedidos'])->name('ventas.todos');
Route::post('/ventas/abono', [VentaController::class, 'registrarAbono'])->name('ventas.abono');
Route::get('/', function () {
    return redirect('/ventas/resumen');
});

/*
|--------------------------------------------------------------------------
| Rutas principales del sistema (CRUD)
|--------------------------------------------------------------------------
*/

Route::resource('productos', ProductoController::class);
Route::resource('clientes', ClienteController::class);
Route::resource('inventario', InventarioDiarioController::class)->parameters([
    'inventario' => 'inventarioDiario'
]);Route::resource('ventas', VentaController::class)->except(['show']); // ⚡ Excluimos show
Route::resource('pagos_credito', PagoCreditoController::class);

/*
|--------------------------------------------------------------------------
| Rutas personalizadas
|--------------------------------------------------------------------------
*/

Route::get('/ventas/resumen', [VentaController::class, 'resumen'])->name('ventas.resumen');