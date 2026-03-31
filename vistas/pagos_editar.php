<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once "../controladores/PagosControlador.php";
require_once "../modelo/MetodoPago.php";

$pagosController = new PagosControlador();
$metodos_pago = (new MetodoPago())->listar();

/* 
   OBTENER PAGO A EDITAR
 */
if (!isset($_GET["id"])) {
    header("Location: pagos.php");
    exit;
}

$id = intval($_GET["id"]);
$pago = $pagosController->obtener($id);

if (!$pago) {
    echo "<div class='alert alert-danger'>Pago no encontrado.</div>";
    exit;
}

/* 
   ACTUALIZAR PAGO
 */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "actualizar") {
    $id_pedido      = intval($_POST["id_pedido"]);
    $id_metodo_pago = intval($_POST["id_metodo_pago"]);
    $monto          = floatval($_POST["monto"]);
    $fecha_pago     = !empty($_POST["fecha_pago"]) ? date("Y-m-d H:i:s", strtotime($_POST["fecha_pago"])) : $pago["fecha_pago"];

    $pagosController->actualizar($id, $id_pedido, $id_metodo_pago, $monto, $fecha_pago);
    header("Location: pagos.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Pago | Domo Creativo</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Estilos -->
    <link rel="stylesheet" href="../assets/css/pagos.css">
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
                <li class="nav-item"><a class="nav-link active fw-semibold" href="pagos.php">Pagos</a></li>
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
        <h4 class="text-danger fw-bold mb-0">Editar Pago</h4>
        <a href="pagos.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <!-- FORMULARIO DE EDICIÓN -->
    <form method="POST" class="row g-3">
        <input type="hidden" name="accion" value="actualizar">

        <div class="col-md-3">
            <label class="form-label fw-semibold">Pedido</label>
            <input type="number" name="id_pedido" class="form-control" value="<?= $pago['id_pedido'] ?>" required>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold">Método de Pago</label>
            <select name="id_metodo_pago" class="form-select" required>
                <?php foreach ($metodos_pago as $m): ?>
                    <option value="<?= $m['id_metodo_pago'] ?>" <?= ($m['id_metodo_pago'] == $pago['id_metodo_pago']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold">Monto</label>
            <input type="number" step="0.01" name="monto" class="form-control" value="<?= $pago['monto'] ?>" required>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold">Fecha</label>
            <input type="datetime-local" name="fecha_pago" class="form-control"
                   value="<?= date('Y-m-d\TH:i', strtotime($pago['fecha_pago'])) ?>">
        </div>

        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-danger px-4">
                <i class="bi bi-save me-1"></i> Actualizar
            </button>
        </div>
    </form>
</div>

<!-- FOOTER -->
<footer class="text-center mt-5 py-4 text-muted small">
    © <?= date("Y") ?> Domo Creativo — Gestión de pagos.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
