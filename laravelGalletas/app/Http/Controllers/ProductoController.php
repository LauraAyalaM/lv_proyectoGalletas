<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Mostrar todos los productos
     */
    public function index()
    {
        $productos = Producto::orderBy('nombre')->get();
        return view('productos.index', compact('productos'));
    }

    /**
     * Formulario para crear un producto
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Guardar un nuevo producto
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
        ]);

        Producto::create($request->only(['nombre', 'precio']));

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    /**
     * Mostrar un producto
     */
    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    /**
     * Formulario para editar un producto
     */
    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    /**
     * Actualizar producto
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
        ]);

        $producto->update($request->only(['nombre', 'precio']));

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Eliminar producto
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente.');
    }
}