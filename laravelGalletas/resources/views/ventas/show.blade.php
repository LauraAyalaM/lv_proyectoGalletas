@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalle Venta</h1>

    <p><strong>Cliente:</strong> {{ $venta->cliente->nombre }}</p>
    <p><strong>Fecha:</strong> {{ $venta->fecha }}</p>
    <p><strong>Tipo de Pago:</strong> {{ ucfirst($venta->tipo_pago) }}</p>
    <p><strong>Total:</strong> {{ $venta->total }}</p>

    <h5>Productos</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->detalles as $detalle)
            <tr>
                <td>{{ $detalle->producto->nombre }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>{{ $detalle->precio_unitario }}</td>
                <td>{{ $detalle->subtotal }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('ventas.index') }}" class="btn btn-primary mt-3">Volver</a>
</div>
@endsection