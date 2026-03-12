<!DOCTYPE html>

<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Sistema Inventario Galletas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">

<a class="navbar-brand" href="/">🍪 CapyCrunch</a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarNav">

<ul class="navbar-nav">

<li class="nav-item">
<a class="nav-link" href="/inventario">Inventario del día</a>
</li>

<li class="nav-item">
<a class="nav-link" href="/ventas/create">Registrar Venta</a>
</li>

<li class="nav-item">
<a class="nav-link" href="/ventas/resumen">Ventas del día</a>
</li>

<li class="nav-item">
<a class="nav-link" href="/ventas/todos">Todas las Ventas</a>
</li>
</ul>

</div>
</div>
</nav>

<div class="container mt-4">

@if(session('success'))

<div class="alert alert-success">
{{ session('success') }}
</div>
@endif

@yield('content')

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
