@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Agregar Inventario Diario</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('inventario.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="producto_id" class="form-label">Producto</label>
            <select name="producto_id" id="producto_id" class="form-control" required>
                <option value="">Seleccione un producto</option>
                @foreach($productos as $prod)
                    <option value="{{ $prod->id }}">{{ $prod->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="cantidad_inicial" class="form-label">Cantidad Inicial</label>
            <input type="number" name="cantidad_inicial" id="cantidad_inicial" class="form-control" min="0" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('inventario.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection