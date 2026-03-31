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

/* 
   OBTENER FACTURA A EDITAR
 */
if (!isset($_GET["id"])) {
    header("Location: facturas.php");
    exit;
}

$id = intval($_GET["id"]);
$factura = $facturasController->obtener($id);

if (!$factura) {
    echo "<div class='alert alert-danger'>Factura no encontrada.</div>";
    exit;
}

/* 
   ACTUALIZAR FACTURA
 */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "actualizar") {
    $id_pedido       = intval($_POST["id_pedido"]);
    $monto_input     = str_replace(',', '.', trim($_POST["monto_total"]));
    $monto_total     = floatval($monto_input);
    $archivo_factura = trim($_POST["archivo_factura"]);
    $estado          = !empty($_POST["estado"]) ? trim($_POST["estado"]) : "generada";
    $fecha_emision   = !empty($_POST["fecha_emision"])
        ? date("Y-m-d H:i:s", strtotime($_POST["fecha_emision"]))
        : $factura["fecha_emision"];

    $facturasController->actualizar($id, $id_pedido, $fecha_emision, $monto_total, $archivo_factura, $estado);
    header("Location: facturas.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Factura | Domo Creativo</title>
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
<div class="container page-container mt-5 pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-danger fw-bold mb-0">Editar Factura</h4>
        <a href="facturas.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <!-- FORMULARIO DE EDICIÓN -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="accion" value="actualizar">

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Pedido</label>
                    <input type="number" name="id_pedido" class="form-control" value="<?= $factura['id_pedido'] ?>" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Monto Total</label>
                    <input type="text" name="monto_total" class="form-control" value="<?= $factura['monto_total'] ?>" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Archivo</label>
                    <input type="text" name="archivo_factura" class="form-control" value="<?= htmlspecialchars($factura['archivo_factura']) ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Estado</label>
                    <input type="text" name="estado" class="form-control" value="<?= htmlspecialchars($factura['estado']) ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Fecha Emisión</label>
                    <input type="datetime-local" name="fecha_emision" class="form-control"
                           value="<?= date('Y-m-d\TH:i', strtotime($factura['fecha_emision'])) ?>">
                </div>

                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-save me-1"></i> Actualizar
                    </button>
                </div>
            </form>
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
