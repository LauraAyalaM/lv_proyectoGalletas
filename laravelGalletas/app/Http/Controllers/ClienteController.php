<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // Listar todos los clientes
    public function index()
    {
        $clientes = Cliente::all();
        return response()->json($clientes);
    }

    // Crear un nuevo cliente
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
        ]);

        $cliente = Cliente::create($request->only('nombre', 'telefono'));

        return response()->json([
            'message' => 'Cliente creado correctamente',
            'cliente' => $cliente
        ], 201);
    }

    // Mostrar un cliente específico
    public function show($id)
    {
        $cliente = Cliente::with('ventas')->findOrFail($id);
        return response()->json($cliente);
    }

    // Actualizar un cliente
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
        ]);

        $cliente->update($request->only('nombre', 'telefono'));

        return response()->json([
            'message' => 'Cliente actualizado correctamente',
            'cliente' => $cliente
        ]);
    }

    // Eliminar un cliente
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return response()->json([
            'message' => 'Cliente eliminado correctamente'
        ]);
    }
}