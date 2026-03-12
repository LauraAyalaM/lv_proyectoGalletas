@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Inventario Diario</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('inventario.update', $inventarioDiario->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Producto</label>
            <input type="text" class="form-control" value="{{ $inventarioDiario->producto->nombre }}" disabled>
        </div>
        <div class="mb-3">
            <label>Fecha</label>
            <input type="date" class="form-control" value="{{ $inventarioDiario->fecha }}" disabled>
        </div>
        <div class="mb-3">
            <label for="cantidad_inicial" class="form-label">Cantidad Inicial</label>
            <input type="number" name="cantidad_inicial" id="cantidad_inicial" class="form-control" value="{{ $inventarioDiario->cantidad_inicial }}" min="0" required>
        </div>
        <div class="mb-3">
            <label for="cantidad_disponible" class="form-label">Cantidad Disponible</label>
            <input type="number" name="cantidad_disponible" id="cantidad_disponible" class="form-control" value="{{ $inventarioDiario->cantidad_disponible }}" min="0" required>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('inventario.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection