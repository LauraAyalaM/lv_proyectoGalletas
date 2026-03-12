@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalle Inventario Diario</h1>
    <div class="card">
        <div class="card-body">
            <p><strong>Producto:</strong> {{ $inventarioDiario->producto->nombre }}</p>
            <p><strong>Fecha:</strong> {{ $inventarioDiario->fecha }}</p>
            <p><strong>Cantidad Inicial:</strong> {{ $inventarioDiario->cantidad_inicial }}</p>
            <p><strong>Cantidad Disponible:</strong> {{ $inventarioDiario->cantidad_disponible }}</p>
        </div>
    </div>
    <a href="{{ route('inventario.index') }}" class="btn btn-primary mt-3">Volver</a>
</div>
@endsection