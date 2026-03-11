<?php

namespace App\Http\Controllers;

use App\Models\PagoCredito;
use App\Models\Venta;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PagoCreditoController extends Controller
{
    /**
     * Listar pagos a crédito
     */
    public function index()
    {
        $pagos = PagoCredito::with('venta.cliente')->orderBy('fecha', 'desc')->get();
        return view('pagos.index', compact('pagos'));
    }

    /**
     * Formulario para registrar nuevo pago
     */
    public function create()
    {
        // Solo listar ventas con tipo_pago = credito
        $ventasCredito = Venta::where('tipo_pago', 'credito')->get();
        return view('pagos.create', compact('ventasCredito'));
    }

    /**
     * Guardar pago a crédito
     */
    public function store(Request $request)
    {
        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            'metodo_pago' => 'required|in:efectivo,nequi,transferencia,credito',
        ]);

        PagoCredito::create([
            'venta_id' => $request->venta_id,
            'monto' => $request->monto,
            'fecha' => $request->fecha,
            'metodo_pago' => $request->metodo_pago,
        ]);

        return redirect()->route('pagos.index')->with('success', 'Pago a crédito registrado correctamente.');
    }

    /**
     * Mostrar un pago
     */
    public function show(PagoCredito $pagoCredito)
    {
        return view('pagos.show', compact('pagoCredito'));
    }

    /**
     * Editar pago
     */
    public function edit(PagoCredito $pagoCredito)
    {
        $ventasCredito = Venta::where('tipo_pago', 'credito')->get();
        return view('pagos.edit', compact('pagoCredito', 'ventasCredito'));
    }

    /**
     * Actualizar pago
     */
    public function update(Request $request, PagoCredito $pagoCredito)
    {
        $request->validate([
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            'metodo_pago' => 'required|in:efectivo,nequi,transferencia,credito',
        ]);

        $pagoCredito->update($request->only(['monto', 'fecha', 'metodo_pago']));

        return redirect()->route('pagos.index')->with('success', 'Pago a crédito actualizado correctamente.');
    }
}