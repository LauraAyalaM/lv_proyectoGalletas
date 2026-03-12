@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Pagos a Crédito</h1>
    <a href="{{ route('pagos.create') }}" class="btn btn-primary mb-3">Registrar Pago</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Venta</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Método de Pago</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pagos as $pago)
                <tr>
                    <td>{{ $pago->venta->cliente->nombre }}</td>
                    <td>{{ $pago->venta->id }}</td>
                    <td>{{ $pago->monto }}</td>
                    <td>{{ $pago->fecha }}</td>
                    <td>{{ ucfirst($pago->metodo_pago) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection