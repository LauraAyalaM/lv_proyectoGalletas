@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="color:#D35400; font-weight:700;">CapyCrunch - Inventario Diario</h1>
        <span class="text-muted" style="font-size:1.1rem;">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
    </div>

    <!-- Formulario para agregar inventario (ancho completo) -->
    <div class="p-4 shadow-sm rounded mb-4" style="background-color:#FFE8D6;">
        <h5 style="color:black; margin-bottom:1rem;">Agregar Cantidad de Producto</h5>
        <form action="{{ route('inventario.store') }}" method="POST" class="row g-3 align-items-end">
            @csrf
            <div class="col-md-6">
                <label for="producto_id" class="form-label">Producto</label>
                <select name="producto_id" id="producto_id" class="form-select" required>
                    <option value="">Seleccione un producto</option>
                    @foreach($productos as $prod)
                        <option value="{{ $prod->id }}">{{ $prod->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="cantidad_inicial" class="form-label">Cantidad a Agregar</label>
                <input type="number" name="cantidad_inicial" id="cantidad_inicial" class="form-control" min="0" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn w-100" style="background-color:#D35400; color:white;">Agregar</button>
            </div>
        </form>
    </div>

    <!-- Tabla de inventario -->
    <h4 style="color:#D35400; margin-bottom:1rem;">Inventario Actual</h4>
    <table class="table table-striped table-hover align-middle shadow-sm" style="border-radius:0.5rem; overflow:hidden;">
        <thead style="background-color:#D35400; color:white;">
            <tr>
                <th>Producto</th>
                <th>Cantidad Inicial</th>
                <th>Cantidad Disponible</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventario as $inv)
                <tr>
                    <td>{{ $inv->producto->nombre }}</td>
                    <td>{{ $inv->cantidad_inicial }}</td>
                    <td>{{ $inv->cantidad_disponible }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No hay inventario registrado hoy</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
    /* Hover más visual para la tabla */
    table.table-hover tbody tr:hover {
        background-color: #FFD8A6 !important;
    }

    /* Redondeado de encabezado de tabla */
    table thead tr th:first-child {
        border-top-left-radius: 0.5rem;
    }
    table thead tr th:last-child {
        border-top-right-radius: 0.5rem;
    }

    /* Sombra suave para la tabla */
    table {
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
</style>
@endsection