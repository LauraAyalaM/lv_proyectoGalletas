<?php

namespace App\Http\Controllers;

use App\Models\InventarioDiario;
use App\Models\Producto;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InventarioDiarioController extends Controller
{
    /**
     * Mostrar inventario diario
     */
    public function index()
    {
        $inventario = InventarioDiario::with('producto')->orderBy('fecha', 'desc')->get();
        return view('inventario.index', compact('inventario'));
    }

    /**
     * Crear nuevo registro de inventario
     */
    public function create()
    {
        $productos = Producto::all();
        return view('inventario.create', compact('productos'));
    }

    /**
     * Guardar inventario diario
     */
    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'fecha' => 'required|date',
            'cantidad_inicial' => 'required|integer|min:0',
        ]);

        InventarioDiario::create([
            'producto_id' => $request->producto_id,
            'fecha' => $request->fecha,
            'cantidad_inicial' => $request->cantidad_inicial,
            'cantidad_disponible' => $request->cantidad_inicial, // Al inicio disponible = inicial
        ]);

        return redirect()->route('inventario.index')->with('success', 'Inventario diario registrado correctamente.');
    }

    /**
     * Mostrar detalles de inventario
     */
    public function show(InventarioDiario $inventarioDiario)
    {
        return view('inventario.show', compact('inventarioDiario'));
    }

    /**
     * Editar inventario diario
     */
    public function edit(InventarioDiario $inventarioDiario)
    {
        $productos = Producto::all();
        return view('inventario.edit', compact('inventarioDiario', 'productos'));
    }

    /**
     * Actualizar inventario diario
     */
    public function update(Request $request, InventarioDiario $inventarioDiario)
    {
        $request->validate([
            'cantidad_inicial' => 'required|integer|min:0',
            'cantidad_disponible' => 'required|integer|min:0',
        ]);

        $inventarioDiario->update($request->only(['cantidad_inicial', 'cantidad_disponible']));

        return redirect()->route('inventario.index')->with('success', 'Inventario actualizado correctamente.');
    }
}