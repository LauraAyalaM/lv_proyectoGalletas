@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalle del Producto</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $producto->nombre }}</h5>
            <p class="card-text"><strong>Precio:</strong> ${{ number_format($producto->precio, 0, ',', '.') }}</p>
        </div>
    </div>
    <a href="{{ route('productos.index') }}" class="btn btn-primary mt-3">Volver</a>
</div>
@endsection