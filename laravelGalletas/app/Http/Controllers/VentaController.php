<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\InventarioDiario;
use App\Models\PagoCredito;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VentaController extends Controller
{
    /**
     * Mostrar formulario para registrar venta
     */
    public function create()
    {
        $clientes = Cliente::all();
        $hoy = Carbon::today()->toDateString();

        // Obtener productos con inventario disponible del día
        $productos = InventarioDiario::with('producto')
            ->whereDate('fecha', $hoy)
            ->where('cantidad_disponible', '>', 0)
            ->get()
            ->groupBy('producto_id') // Agrupar por producto
            ->map(function($items) {
                $producto = $items->first()->producto;
                $cantidadDisponible = $items->sum('cantidad_disponible');
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'cantidad_disponible' => $cantidadDisponible,
                    'precio' => $producto->precio
                ];
            })
            ->values(); // Reindexa la colección para el Blade

        return view('ventas.create', compact('clientes', 'productos'));
    }

    /**
     * Guardar venta
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo_pago' => 'required',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        $total = 0;

        // Crear cliente nuevo si se seleccionó "nuevo"
        if($request->cliente_id === 'nuevo'){
            $request->validate([
                'cliente_nombre' => 'required|string|max:255',
                'cliente_telefono' => 'required|string|max:50',
            ]);

            $cliente = Cliente::create([
                'nombre' => $request->cliente_nombre,
                'telefono' => $request->cliente_telefono,
            ]);

            $cliente_id = $cliente->id;
        } else {
            $cliente_id = $request->cliente_id;
        }

        // Crear la venta
        $venta = Venta::create([
            'cliente_id' => $cliente_id,
            'fecha' => now(),
            'tipo_pago' => $request->tipo_pago,
            'total' => 0
        ]);

        // Guardar cada detalle de venta y ajustar inventario
        foreach($request->productos as $item) {
            $producto_id = $item['producto_id'];
            $cantidad = $item['cantidad'];

            if($cantidad > 0){
                $producto = Producto::find($producto_id);
                $subtotal = $producto->precio * $cantidad;

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto_id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $producto->precio,
                    'subtotal' => $subtotal
                ]);

                $total += $subtotal;

                // Reducir inventario disponible del día
                $inventarios = InventarioDiario::where('producto_id', $producto_id)
                    ->whereDate('fecha', Carbon::today())
                    ->where('cantidad_disponible', '>', 0)
                    ->orderBy('id')
                    ->get();

                $restante = $cantidad;

                foreach($inventarios as $inv){
                    if($restante <= 0) break;

                    if($inv->cantidad_disponible >= $restante){
                        $inv->cantidad_disponible -= $restante;
                        $inv->save();
                        $restante = 0;
                    } else {
                        $restante -= $inv->cantidad_disponible;
                        $inv->cantidad_disponible = 0;
                        $inv->save();
                    }
                }
            }
        }

        // Actualizar total
        $venta->update(['total' => $total]);

        return redirect()->route('ventas.resumen')->with('success','Venta registrada correctamente');
    }

    /**
     * Registrar abono a crédito
     */
    public function registrarAbono(Request $request)
    {
        $request->validate([
            'venta_id' => 'required|exists:ventas,id',
            'monto_abono' => 'required|numeric|min:1'
        ]);

        PagoCredito::create([
            'venta_id' => $request->venta_id,
            'monto' => $request->monto_abono,
            'fecha' => now(),
            'metodo_pago' => 'efectivo'
        ]);

        return redirect()->back()->with('success', 'Abono registrado correctamente');
    }

    /**
     * Resumen diario de ventas (solo hoy)
     */
    public function resumen()
    {
        $hoy = Carbon::today()->toDateString();

        // Solo ventas del día
        $ventas = Venta::with('cliente', 'detalles.producto', 'pagosCredito')
            ->whereDate('fecha', $hoy)
            ->get();

        // Preparar resumen
        $resumen = $ventas->map(function($venta) {
            $totalPagos = $venta->pagosCredito->sum('monto');
            $saldoPendiente = $venta->tipo_pago === 'credito' ? $venta->total - $totalPagos : 0;

            return (object)[
                'venta_id' => $venta->id,
                'cliente' => $venta->cliente->nombre ?? 'Sin cliente',
                'telefono' => $venta->cliente->telefono ?? '-',
                'producto' => $venta->detalles->pluck('producto.nombre')->join(', '),
                'tipo_pago' => $venta->tipo_pago,
                'cantidad' => $venta->detalles->sum('cantidad'),
                'total' => $venta->total,
                'saldo_pendiente' => $saldoPendiente,
            ];
        });

        // Calcular totales del día
        $totalVendido = $resumen->sum('total');
        $cantidadTotal = $resumen->sum('cantidad');

        return view('ventas.resumen', compact('resumen', 'totalVendido', 'cantidadTotal'));
    }
    /**
 * Mostrar todos los pedidos con filtros
 */
public function todosPedidos(Request $request)
{
    // Base query
    $query = Venta::with('cliente', 'detalles.producto', 'pagosCredito');

    // Filtrar por fecha si se pasa
    if ($request->filled('fecha_desde')) {
        $query->whereDate('fecha', '>=', $request->fecha_desde);
    }
    if ($request->filled('fecha_hasta')) {
        $query->whereDate('fecha', '<=', $request->fecha_hasta);
    }

    // Traer todas las ventas filtradas por fecha
    $ventas = $query->orderBy('fecha', 'desc')->get();

    // Filtrar solo pendientes si se solicitó
    if ($request->filled('solo_pendientes') && $request->solo_pendientes == 1) {
        $ventas = $ventas->filter(function($venta) {
            $totalPagos = $venta->pagosCredito->sum('monto');
            return $venta->tipo_pago === 'credito' && $venta->total > $totalPagos;
        });
    }

    // Preparar resumen para la vista
    $resumen = $ventas->map(function($venta) {
        $totalPagos = $venta->pagosCredito->sum('monto');
        $saldoPendiente = $venta->tipo_pago === 'credito' ? $venta->total - $totalPagos : 0;

        return (object)[
            'venta_id' => $venta->id,
            'cliente' => $venta->cliente->nombre ?? 'Sin cliente',
            'telefono' => $venta->cliente->telefono ?? '-',
            'producto' => $venta->detalles->pluck('producto.nombre')->join(', '),
            'tipo_pago' => $venta->tipo_pago,
            'cantidad' => $venta->detalles->sum('cantidad'),
            'total' => $venta->total,
            'saldo_pendiente' => $saldoPendiente,
            'fecha' => $venta->fecha->format('d/m/Y H:i')
        ];
    });

    return view('ventas.todos', compact('resumen'));
}
}