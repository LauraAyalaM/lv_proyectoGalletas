@extends('layouts.app')

@section('content')
<div class="container py-3">

    <h1 class="mb-4" style="color:#D2691E;">Registrar Venta - CapyCrunch</h1>

    @if($errors->any())
        <div class="alert alert-danger rounded-0 py-2 px-3">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('ventas.store') }}" method="POST" class="mb-5">
        @csrf

        <!-- Cliente -->
        <div class="row mb-3 align-items-end">
            <div class="col-md-6">
                <label for="cliente_id" class="form-label">Cliente</label>
                <select name="cliente_id" id="cliente_id" class="form-select" required>
                    <option value="">Seleccione un cliente</option>
                    <option value="nuevo">Agregar cliente nuevo</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6" id="nuevoClienteDiv" style="display:none;">
                <label class="form-label">Datos Cliente Nuevo</label>
                <input type="text" name="cliente_nombre" placeholder="Nombre" class="form-control mb-2">
                <input type="text" name="cliente_telefono" placeholder="Teléfono" class="form-control">
            </div>
        </div>

        <!-- Tipo de pago -->
<div class="row mb-4">

    <div class="col-md-6">
        <label for="tipo_pago" class="form-label">Tipo de Pago</label>
        <select name="tipo_pago" id="tipo_pago" class="form-select" required>
            <option value="efectivo">Efectivo</option>
            <option value="nequi">Nequi</option>
            <option value="transferencia">Transferencia</option>
            <option value="credito">Crédito</option>

        </select>
    </div>
</div>

        <!-- Productos -->
        <h5 class="mb-3">Productos</h5>
        <table class="table table-borderless align-middle" id="productosTable">
            <thead>
                <tr>
                    <th>Producto (disp.)</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                    <th><button type="button" class="btn btn-sm btn-outline-success" id="addRow">+</button></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="productos[0][producto_id]" class="form-select producto-select" required>
                            <option value="">Seleccione producto</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto['id'] }}"
                                    data-precio="{{ $producto['precio'] }}"
                                    data-disponible="{{ $producto['cantidad_disponible'] }}">
                                    {{ $producto['nombre'] }} (disp: {{ $producto['cantidad_disponible'] }})
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="productos[0][cantidad]" class="form-control cantidad" min="1" value="1" required></td>
                    <td><input type="number" name="productos[0][precio_unitario]" class="form-control precio" step="0.01" required></td>
                    <td><input type="number" name="productos[0][subtotal]" class="form-control subtotal" step="0.01" readonly></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn" style="background-color:#D2691E; color:white;">Registrar Venta</button>
            <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let rowCount = 1;

    const clienteSelect = document.getElementById('cliente_id');
    const nuevoClienteDiv = document.getElementById('nuevoClienteDiv');

    clienteSelect.addEventListener('change', function() {
        if(this.value === 'nuevo') {
            nuevoClienteDiv.style.display = 'block';
            nuevoClienteDiv.querySelectorAll('input').forEach(input => input.required = true);
        } else {
            nuevoClienteDiv.style.display = 'none';
            nuevoClienteDiv.querySelectorAll('input').forEach(input => input.required = false);
        }
    });

    function recalcularSubtotal(tr) {
        const cantidad = parseFloat(tr.querySelector('.cantidad').value) || 0;
        const precio = parseFloat(tr.querySelector('.precio').value) || 0;
        tr.querySelector('.subtotal').value = (cantidad * precio).toFixed(2);
    }

    function actualizarProducto(e) {
        if(e.target.classList.contains('producto-select')) {
            const tr = e.target.closest('tr');
            const selected = e.target.selectedOptions[0];
            const precioInput = tr.querySelector('.precio');
            const cantidadInput = tr.querySelector('.cantidad');

            precioInput.value = selected.dataset.precio;
            cantidadInput.max = selected.dataset.disponible;
            if(cantidadInput.value > cantidadInput.max) cantidadInput.value = cantidadInput.max;

            recalcularSubtotal(tr);
        }
    }

    // Agregar fila
    document.getElementById('addRow').addEventListener('click', function() {
        const tbody = document.querySelector('#productosTable tbody');
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>
            <select name="productos[${rowCount}][producto_id]" class="form-select producto-select" required>
                <option value="">Seleccione producto</option>
                @foreach($productos as $producto)
                    <option value="{{ $producto['id'] }}"
                        data-precio="{{ $producto['precio'] }}"
                        data-disponible="{{ $producto['cantidad_disponible'] }}">
                        {{ $producto['nombre'] }} (disp: {{ $producto['cantidad_disponible'] }})
                    </option>
                @endforeach
            </select>
        </td>
        <td><input type="number" name="productos[${rowCount}][cantidad]" class="form-control cantidad" min="1" value="1" required></td>
        <td><input type="number" name="productos[${rowCount}][precio_unitario]" class="form-control precio" step="0.01" required></td>
        <td><input type="number" name="productos[${rowCount}][subtotal]" class="form-control subtotal" step="0.01" readonly></td>
        <td><button type="button" class="btn btn-sm btn-outline-danger removeRow">-</button></td>`;
        tbody.appendChild(tr);
        rowCount++;
    });

    // Quitar fila
    document.querySelector('#productosTable').addEventListener('click', function(e) {
        if(e.target.classList.contains('removeRow')) {
            e.target.closest('tr').remove();
        }
    });

    // Escuchar cambios
    document.querySelector('#productosTable').addEventListener('input', function(e) {
        if(e.target.classList.contains('cantidad') || e.target.classList.contains('precio')) {
            recalcularSubtotal(e.target.closest('tr'));
        }
    });

    document.querySelector('#productosTable').addEventListener('change', actualizarProducto);
});
</script>
@endsection