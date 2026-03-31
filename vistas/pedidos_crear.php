<?php
if (!defined("BASE_URL")) {
    define("BASE_URL", "/plataforma_domo/");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Pedido | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<div class="container mt-5 pt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-danger">
            <i class="bi bi-plus-circle me-2"></i>Nuevo Pedido
        </h3>
        <a href="<?= BASE_URL ?>index.php?controlador=pedidos&accion=listar" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="<?= BASE_URL ?>index.php?controlador=pedidos&accion=insertar" method="POST">

                <div class="mb-3">
                    <label class="form-label">ID Presupuesto</label>
                    <input type="number" name="id_presupuesto" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select" required>
                        <option value="Pendiente" selected>Pendiente</option>
                        <option value="En proceso">En proceso</option>
                        <option value="Entregado">Entregado</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha Entrega</label>
                    <input type="date" name="fecha_entrega" class="form-control" required>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-save me-1"></i> Guardar Pedido
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
