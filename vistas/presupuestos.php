<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once "../controladores/PresupuestosControlador.php";
$presupuestosControlador = new PresupuestosControlador();

/* 
   ELIMINAR PRESUPUESTO
 */
if (isset($_GET["eliminar"])) {
    $id = intval($_GET["eliminar"]);
    if ($presupuestosControlador->eliminar($id)) {
        $mensaje = "<div class='alert alert-success mt-3 text-center'>Presupuesto eliminado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger mt-3 text-center'>No se pudo eliminar el presupuesto.</div>";
    }
}

/* 
   LISTAR PRESUPUESTOS
 */
$listaPresupuestos = $presupuestosControlador->listar();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Presupuestos | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="../assets/css/presupuestos.css">
</head>

<body class="light-theme">

<!-- NAVBAR UNIFICADA -->
<nav class="navbar navbar-expand-lg fixed-top navbar-light shadow-sm glass-navbar">
    <div class="container">

        <a class="navbar-brand text-danger fw-bold" href="../index.php">Domo Creativo</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="menu">
            <ul class="navbar-nav">

                <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>

                <li class="nav-item"><a class="nav-link" href="usuarios.php">Usuarios</a></li>

                <li class="nav-item"><a class="nav-link" href="perfiles.php">Perfiles</a></li>

                <li class="nav-item"><a class="nav-link" href="productos.php">Productos</a></li>

                <li class="nav-item"><a class="nav-link active fw-semibold" href="presupuestos.php">Presupuestos</a></li>

                <li class="nav-item"><a class="nav-link" href="carrito.php">Carrito</a></li>

                <li class="nav-item">
                    <a class="nav-link text-danger" href="../index.php">
                        <i class="bi bi-box-arrow-right"></i> Salir
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<!-- CONTENIDO -->
<div class="container page-container">

    <div class="card soft-card shadow-sm mt-5">

        <div class="card-header bg-white border-0 text-danger fw-semibold d-flex justify-content-between align-items-center">
            <span><i class="bi bi-file-earmark-text me-2"></i> Gestión de Presupuestos</span>

            <a href="presupuestos_crear.php" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Presupuesto
            </a>
        </div>

        <div class="card-body">

            <?php if (isset($mensaje)) echo $mensaje; ?>

            <div class="table-responsive mt-3">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>ID Usuario</th>
                            <th>Total Estimado</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($listaPresupuestos)): ?>
                            <?php foreach ($listaPresupuestos as $presupuesto): ?>
                                <tr>
                                    <td><?= $presupuesto["id_presupuesto"] ?></td>
                                    <td><?= $presupuesto["id_usuario"] ?></td>
                                    <td>$<?= number_format($presupuesto["total_estimado"], 2) ?></td>
                                    <td><?= ucfirst($presupuesto["estado"]) ?></td>
                                    <td><?= $presupuesto["fecha_creacion"] ?></td>

                                    <td class="text-center">
                                        <a href="detalle_presupuestos.php?id_presupuesto=<?= $presupuesto["id_presupuesto"] ?>" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-list-ul"></i>
                                        </a>

                                        <a href="presupuestos_editar.php?id=<?= $presupuesto["id_presupuesto"] ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <a href="?eliminar=<?= $presupuesto["id_presupuesto"] ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('¿Eliminar presupuesto?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No hay presupuestos registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>

    </div>
</div>

<footer class="text-center mt-5 py-4 text-muted small">
     <?= date("Y") ?> Domo Creativo — Gestión de presupuestos.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
