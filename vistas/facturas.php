<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once "../controladores/FacturasControlador.php";

$facturasController = new FacturasControlador();
$facturas = $facturasController->listar();

$error = null;

/* 
   AGREGAR FACTURA
 */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "insertar") {
    $id_pedido       = intval($_POST["id_pedido"]);
    $monto_input     = str_replace(',', '.', trim($_POST["monto_total"]));
    $monto_total     = floatval($monto_input);
    $archivo_factura = trim($_POST["archivo_factura"]);
    $estado          = !empty($_POST["estado"]) ? trim($_POST["estado"]) : "generada";
    $fecha_emision   = !empty($_POST["fecha_emision"])
        ? date("Y-m-d H:i:s", strtotime($_POST["fecha_emision"]))
        : null;

    $ok = $facturasController->insertar($id_pedido, $monto_total, $archivo_factura, $estado, $fecha_emision);

    if ($ok) {
        header("Location: facturas.php");
        exit;
    } else {
        $error = "No se pudo guardar la factura. Verificá los datos ingresados.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Facturas | Domo Creativo</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/facturas.css">
</head>
<body class="light-theme">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top navbar-light shadow-sm glass-navbar">
    <div class="container">
        <a class="navbar-brand text-danger fw-bold" href="../index.php">Domo Creativo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="menu">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link active fw-semibold" href="facturas.php">Facturas</a></li>
                <li class="nav-item"><a class="nav-link" href="pagos.php">Pagos</a></li>
                <li class="nav-item"><a class="nav-link" href="productos.php">Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="usuarios.php">Usuarios</a></li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="../logout.php">
                        <i class="bi bi-box-arrow-right"></i> Salir
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- CONTENIDO -->
<div class="container mt-5 pt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-danger fw-bold mb-0">Gestión de Facturas</h4>
        <a href="facturas.php" class="btn btn-outline-danger">
            <i class="bi bi-file-earmark-text me-1"></i> Nueva Factura
        </a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <!-- FORMULARIO DE CREACIÓN -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="accion" value="insertar">

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Pedido</label>
                    <input type="number" name="id_pedido" class="form-control" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Monto Total</label>
                    <input type="text" name="monto_total" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Archivo</label>
                    <input type="text" name="archivo_factura" class="form-control" placeholder="factura_123.pdf">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Estado</label>
                    <input type="text" name="estado" class="form-control" value="generada">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Fecha Emisión</label>
                    <input type="datetime-local" name="fecha_emision" class="form-control">
                </div>

                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-save me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLA DE FACTURAS -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Pedido</th>
                        <th>Fecha Emisión</th>
                        <th>Monto Total</th>
                        <th>Archivo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($facturas as $f): ?>
                    <tr>
                        <td><?= $f['id_factura'] ?></td>
                        <td><?= $f['id_pedido'] ?></td>
                        <td><?= $f['fecha_emision'] ?></td>
                        <td>$<?= number_format($f['monto_total'], 2) ?></td>
                        <td><?= htmlspecialchars($f['archivo_factura']) ?></td>
                        <td><?= htmlspecialchars($f['estado']) ?></td>
                        <td>
                            <a href="facturas_editar.php?id=<?= $f['id_factura'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="../index.php?controlador=facturas&accion=eliminar&id=<?= $f['id_factura'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar factura?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="text-center mt-5 py-4 text-muted small">
    © <?= date("Y") ?> Domo Creativo — Gestión de facturas.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
