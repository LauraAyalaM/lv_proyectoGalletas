@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Registrar Pago a Crédito</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

<form action="{{ route('pagos_credito.store') }}" method="POST">        @csrf
        <div class="mb-3">
            <label for="venta_id" class="form-label">Venta (Crédito)</label>
            <select name="venta_id" id="venta_id" class="form-control" required>
                <option value="">Seleccione una venta</option>
                @foreach($ventasCredito as $venta)
                    <option value="{{ $venta->id }}">Venta #{{ $venta->id }} - {{ $venta->cliente->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="monto" class="form-label">Monto</label>
            <input type="number" step="0.01" name="monto" id="monto" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="datetime-local" name="fecha" id="fecha" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="metodo_pago" class="form-label">Método de Pago</label>
            <select name="metodo_pago" id="metodo_pago" class="form-control" required>
                <option value="efectivo">Efectivo</option>
                <option value="nequi">Nequi</option>
                <option value="transferencia">Transferencia</option>
                <option value="credito">Crédito</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Registrar Pago</button>
        <a href="{{ route('pagos_credito.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection