@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4" style="color:#D2691E;">Todos los Pedidos - CapyCrunch</h2>

    <!-- Filtros -->
    <form method="GET" action="{{ route('ventas.todos') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="fecha_desde" class="form-label">Fecha desde</label>
            <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
        </div>
        <div class="col-md-3">
            <label for="fecha_hasta" class="form-label">Fecha hasta</label>
            <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <div class="form-check">
                <input type="checkbox" name="solo_pendientes" value="1" class="form-check-input" id="solo_pendientes" {{ request('solo_pendientes') == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="solo_pendientes">Solo pendientes</label>
            </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a href="{{ route('ventas.todos') }}" class="btn btn-secondary w-100">Limpiar filtros</a>
        </div>
    </form>

    <!-- Tabla de pedidos -->
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Producto</th>
                    <th>Forma de Pago</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Saldo Pendiente</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resumen as $item)
                <tr>
                    <td>{{ $item->fecha }}</td>
                    <td>{{ $item->cliente }}</td>
                    <td>{{ $item->telefono }}</td>
                    <td>{{ $item->producto }}</td>
                    <td>{{ ucfirst($item->tipo_pago) }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>${{ number_format($item->total,2) }}</td>
                    <td>${{ number_format($item->saldo_pendiente,2) }}</td>
                    <td>
                        @if($item->saldo_pendiente > 0)
                            <span class="badge bg-warning">Pendiente</span>
                        @else
                            <span class="badge bg-success">Pagado</span>
                        @endif
                    </td>
                    <td>
                        @if($item->saldo_pendiente > 0)
                            <button class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#abonoModal"
                                    data-venta="{{ $item->venta_id }}"
                                    data-saldo="{{ $item->saldo_pendiente }}">
                                Registrar Pago
                            </button>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">No hay pedidos registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<!-- Modal único para registrar pagos -->
<div class="modal fade" id="abonoModal" tabindex="-1" aria-labelledby="abonoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('ventas.abono') }}" method="POST">
        @csrf
        <input type="hidden" name="venta_id" id="venta_id">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="abonoModalLabel">Registrar Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="monto_abono" class="form-label">Monto a abonar</label>
                    <input type="number" name="monto_abono" id="monto_abono" class="form-control" min="1" step="0.01" required>
                </div>
                <p>Saldo pendiente: $<span id="saldoModal">0.00</span></p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Registrar Pago</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </form>
  </div>
</div>

<script>
var abonoModal = document.getElementById('abonoModal');
abonoModal.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget;
    var ventaId = button.getAttribute('data-venta');
    var saldo = button.getAttribute('data-saldo');
    document.getElementById('venta_id').value = ventaId;
    document.getElementById('saldoModal').textContent = parseFloat(saldo).toFixed(2);
});
</script>
@endsection