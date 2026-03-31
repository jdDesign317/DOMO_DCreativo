<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Método de Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h3 class="text-danger mb-4">Nuevo Método de Pago</h3>

    <form action="index.php?controlador=metodos_pago&accion=insertar"
          method="POST"
          class="shadow p-4 rounded bg-white">

        <div class="mb-3">
            <label class="form-label fw-semibold">Nombre del Método de Pago</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-danger px-4">
            <i class="bi bi-save me-1"></i> Guardar
        </button>

        <a href="index.php?controlador=metodos_pago&accion=listar"
           class="btn btn-secondary ms-2">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </form>
</div>

</body>
</html>
