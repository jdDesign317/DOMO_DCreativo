<?php
// Validación defensiva: aseguramos que $pedido exista
if (!isset($pedido) || !is_array($pedido)) {
    echo "<div class='alert alert-danger text-center mt-4'>
            Error: No se pudo cargar el pedido.
          </div>";
    return;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Pedido | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<div class="container mt-5 pt-4">

    <!-- TÍTULO + BOTÓN VOLVER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-danger">
            <i class="bi bi-pencil-square me-2"></i>Actualizar Pedido
        </h3>
        <a href="index.php?controlador=pedidos&accion=listar" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <!-- FORMULARIO ACTUALIZAR -->
            <form action="index.php?controlador=pedidos&accion=actualizar" method="POST">
                <!-- Campo oculto con el ID del pedido -->
                <input type="hidden" name="id" value="<?= $pedido['id_pedido'] ?>">

                <!-- ESTADO -->
                <div class="mb-3">
                    <label for="estado" class="form-label fw-semibold">Estado</label>
                    <select id="estado" name="estado" class="form-select" required>
                        <option value="" disabled>-- Seleccione un estado --</option>
                        <option value="Pendiente"   <?= ($pedido['estado'] === 'Pendiente') ? 'selected' : '' ?>>Pendiente</option>
                        <option value="En proceso"  <?= ($pedido['estado'] === 'En proceso') ? 'selected' : '' ?>>En proceso</option>
                        <option value="Entregado"   <?= ($pedido['estado'] === 'Entregado') ? 'selected' : '' ?>>Entregado</option>
                    </select>
                </div>

                <!-- FECHA DE ENTREGA -->
                <div class="mb-3">
                    <label for="fecha_entrega" class="form-label fw-semibold">Fecha de Entrega</label>
                    <input type="date" 
                           id="fecha_entrega"
                           name="fecha_entrega" 
                           class="form-control" 
                           value="<?= date('Y-m-d', strtotime($pedido['fecha_entrega'])) ?>" 
                           required>
                </div>

                <!-- BOTÓN GUARDAR -->
                <div class="text-end">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

<footer class="text-center text-muted small py-4">
    © <?= date("Y") ?> Domo Creativo — Actualización de pedidos.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
