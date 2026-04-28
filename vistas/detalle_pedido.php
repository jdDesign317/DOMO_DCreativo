<?php
if (!defined("BASE_URL")) {
    define("BASE_URL", "/plataforma_domo/");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Pedido | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<div class="container mt-5 pt-4">

    <!-- TÍTULO -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-danger">
            <i class="bi bi-receipt me-2"></i>Detalle del Pedido #<?= $id_pedido ?>
        </h3>

        <a href="<?= BASE_URL ?>index.php?controlador=pedidos&accion=listar" 
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <!-- TABLA -->
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (!empty($detalles)): ?>
                            <?php 
                                $total = 0;
                                foreach ($detalles as $d): 
                                    $subtotal = $d["cantidad"] * $d["precio_unitario"];
                                    $total += $subtotal;
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($d["nombre_producto"]) ?></td>
                                    <td><?= $d["cantidad"] ?></td>
                                    <td>$<?= number_format($d["precio_unitario"], 2, ",", ".") ?></td>
                                    <td>$<?= number_format($subtotal, 2, ",", ".") ?></td>
                                </tr>
                            <?php endforeach; ?>

                            <!-- TOTAL -->
                            <tr class="fw-bold">
                                <td colspan="3" class="text-end">Total:</td>
                                <td class="text-danger">
                                    $<?= number_format($total, 2, ",", ".") ?>
                                </td>
                            </tr>

                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    <i class="bi bi-info-circle me-1"></i> Este pedido no tiene productos.
                                </td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>