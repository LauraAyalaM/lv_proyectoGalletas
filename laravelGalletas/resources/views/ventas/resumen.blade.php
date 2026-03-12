@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4" style="color:#D2691E;">Resumen de Ventas del Día - CapyCrunch</h2>

<!-- Información general dentro de una card colorida -->
    @if($resumen->count() > 0)
        @php
            $totalDia = $resumen->sum('total');
            $cantidadDia = $resumen->sum('cantidad');
            $saldoPendienteDia = $resumen->sum('saldo_pendiente');
        @endphp

        <div class="card mb-4 shadow-sm">
            <div class="card-header" style="background-color:#D2691E; color:white; font-weight:600;">
                Resumen General del Día
            </div>
            <div class="card-body d-flex justify-content-around flex-wrap text-center">
                <div class="p-3 m-2 rounded" style="background-color:#FFE8D6; min-width:180px;">
                    <h6 class="text-muted">Fecha</h6>
                    <span class="fw-bold">{{ \Carbon\Carbon::today()->format('d/m/Y') }}</span>
                </div>
                <div class="p-3 m-2 rounded" style="background-color:#D1E7DD; min-width:180px;">
                    <h6 class="text-muted">Total Vendido</h6>
                    <span class="fw-bold text-success">${{ number_format($totalDia, 2) }}</span>
                </div>
                <div class="p-3 m-2 rounded" style="background-color:#FFF3CD; min-width:180px;">
                    <h6 class="text-muted">Cantidad de Productos</h6>
                    <span class="fw-bold">{{ $cantidadDia }}</span>
                </div>
                <div class="p-3 m-2 rounded" style="background-color:#F8D7DA; min-width:180px;">
                    <h6 class="text-muted">Saldo Pendiente</h6>
                    <span class="fw-bold text-danger">${{ number_format($saldoPendienteDia, 2) }}</span>
                </div>
            </div>
        </div>
    @endif
    <!-- Tabla de resumen -->
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover table-striped align-middle">
    <thead>
        <tr>
            <th>Cliente</th>
            <th>Teléfono</th>
            <th>Producto</th>
            <th>Forma de Pago</th>
            <th>Cantidad Vendida</th>
            <th>Total</th>
            <th>Saldo Pendiente</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @foreach($resumen as $item)
        <tr>
            <td>{{ $item->cliente }}</td>
            <td>{{ $item->telefono }}</td>
            <td>{{ $item->producto }}</td>
            <td>{{ $item->tipo_pago }}</td>
            <td>{{ $item->cantidad }}</td>
            <td>${{ number_format($item->total,2) }}</td>
            <td>${{ number_format($item->saldo_pendiente ?? 0, 2) }}</td>
            <td>
                @if($item->saldo_pendiente > 0)
                    <span class="badge text-bg-warning">Pendiente</span>
                @else
                    <span class="badge text-bg-success">Pagado</span>
                @endif
            </td>
            <td>
                @if($item->saldo_pendiente > 0)
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#abonoModal" data-venta="{{ $item->venta_id }}" data-saldo="{{ $item->saldo_pendiente }}">Registrar Pago</button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Modal para registrar abono -->
<div class="modal fade" id="abonoModal" tabindex="-1" aria-labelledby="abonoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('ventas.abono') }}" method="POST" id="abonoForm">
        @csrf
        <input type="hidden" name="venta_id" id="venta_id">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Abono</h5>
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
                <button type="submit" class="btn btn-primary">Registrar Abono</button>
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
    </div>

</div>
@endsection