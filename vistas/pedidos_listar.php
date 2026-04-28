<?php
if (!defined("BASE_URL")) {
    define("BASE_URL", "/plataforma_domocretaivo1/");
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Estilos globales -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/pedidos.css">
</head>
<body class="light-theme">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top navbar-light shadow-sm glass-navbar">
    <div class="container">
        <a class="navbar-brand text-danger fw-bold" href="<?= BASE_URL ?>index.php">Domo Creativo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="menu">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>vistas/usuarios.php">Usuarios</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>vistas/perfiles.php">Perfiles</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>vistas/productos.php">Productos</a></li>
                <li class="nav-item"><a class="nav-link active fw-semibold" href="<?= BASE_URL ?>index.php?controlador=pedidos&accion=listar">Pedidos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>vistas/presupuestos.php">Presupuestos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>vistas/pagos.php">Pagos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>vistas/carrito.php">Carrito</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>vistas/facturas.php">Facturas</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>vistas/mensajes.php">Mensajes</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="<?= BASE_URL ?>logout.php">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- CONTENIDO -->
<div class="container page-container mt-5 pt-4">
    <div class="card soft-card shadow-sm">
        <div class="card-header bg-white border-0 text-danger fw-semibold d-flex justify-content-between align-items-center flex-wrap">
            <span><i class="bi bi-receipt-cutoff me-2"></i> Gestión de Pedidos</span>
            <a href="<?= BASE_URL ?>index.php?controlador=pedidos&accion=crear" class="btn btn-sm btn-outline-danger text-nowrap">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Pedido
            </a>
        </div>

        <div class="card-body">
            <?php if (isset($mensaje)) echo $mensaje; ?>

            <div class="table-responsive mt-3">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Total Estimado</th>
                            <th>Estado</th>
                            <th>Fecha Pedido</th>
                            <th>Fecha de Entrega</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pedidos)): ?>
                            <?php foreach ($pedidos as $p): ?>
                                <tr>
                                    <td><?= $p["id_pedido"] ?></td>
                                    <td><?= htmlspecialchars($p["usuario"]) ?></td>
                                    <td>$<?= number_format($p["total_estimado"], 2, ",", ".") ?></td>
                                    <td><?= htmlspecialchars($p["estado"]) ?></td>
                                    <td><?= date("d/m/Y", strtotime($p["fecha_pedido"])) ?></td>
                                    <td><?= date("d/m/Y", strtotime($p["fecha_entrega"])) ?></td>
                                    <td class="text-center">
                                        <a href="<?= BASE_URL ?>index.php?controlador=pedidos&accion=actualizarForm&id=<?= $p["id_pedido"] ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>index.php?controlador=pedidos&accion=eliminar&id=<?= $p["id_pedido"] ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('¿Eliminar pedido?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">
                                    <i class="bi bi-info-circle me-1"></i> No hay pedidos registrados.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<footer class="text-center mt-5 py-4 text-muted small">
     <?= date("Y") ?> Domo Creativo — Gestión de pedidos.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
