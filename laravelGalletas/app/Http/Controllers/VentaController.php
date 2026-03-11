<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index() {
        $ventas = Venta::with('cliente','detalles.producto')->get();
        return response()->json($ventas);
    }

    public function store(Request $request) {
        $venta = Venta::create([
            'cliente_id' => $request->cliente_id,
            'fecha' => now(),
            'tipo_pago' => $request->tipo_pago,
            'total' => $request->total,
        ]);

        foreach ($request->productos as $prod) {
            DetalleVenta::create([
                'venta_id' => $venta->id,
                'producto_id' => $prod['producto_id'],
                'cantidad' => $prod['cantidad'],
                'precio_unitario' => $prod['precio_unitario'],
                'subtotal' => $prod['cantidad'] * $prod['precio_unitario'],
            ]);
        }

        return response()->json(['message' => 'Venta registrada', 'venta' => $venta]);
    }
}