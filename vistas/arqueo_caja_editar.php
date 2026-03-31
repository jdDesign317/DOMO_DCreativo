<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once "../controladores/ArqueoCajaControlador.php";

$arqueoController = new ArqueoCajaControlador();

/* 
   OBTENER ARQUEO A EDITAR
*/
if (!isset($_GET["id"])) {
    header("Location: arqueo_caja.php");
    exit;
}

$id = intval($_GET["id"]);
$arqueo = $arqueoController->obtener($id);

if (!$arqueo) {
    echo "<div class='alert alert-danger'>Arqueo de caja no encontrado.</div>";
    exit;
}

/* 
   ACTUALIZAR ARQUEO
*/
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "actualizar") {
    $id_usuario     = intval($_POST["id_usuario"]);
    $monto_inicial  = floatval(str_replace(',', '.', $_POST["monto_inicial"]));
    $monto_final    = floatval(str_replace(',', '.', $_POST["monto_final"]));
    $total_ingresos = floatval(str_replace(',', '.', $_POST["total_ingresos"]));
    $total_egresos  = floatval(str_replace(',', '.', $_POST["total_egresos"]));
    $observaciones  = trim($_POST["observaciones"]);
    $estado         = $_POST["estado"];
    $fecha_apertura = !empty($_POST["fecha_apertura"]) ? date("Y-m-d H:i:s", strtotime($_POST["fecha_apertura"])) : $arqueo["fecha_apertura"];
    $fecha_cierre   = !empty($_POST["fecha_cierre"]) ? date("Y-m-d H:i:s", strtotime($_POST["fecha_cierre"])) : $arqueo["fecha_cierre"];

    $arqueoController->actualizar($id, $id_usuario, $fecha_apertura, $fecha_cierre, $monto_inicial, $monto_final, $total_ingresos, $total_egresos, $observaciones, $estado);
    header("Location: arqueo_caja.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Arqueo de Caja | Domo Creativo</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/arqueo.css">
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
                <li class="nav-item"><a class="nav-link active fw-semibold" href="arqueo_caja.php">Arqueo Caja</a></li>
                <li class="nav-item"><a class="nav-link" href="pagos.php">Pagos</a></li>
                <li class="nav-item"><a class="nav-link" href="facturas.php">Facturas</a></li>
                <li class="nav-item"><a class="nav-link" href="mensajes.php">Mensajes</a></li>
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
        <h4 class="text-danger fw-bold mb-0">Editar Arqueo de Caja</h4>
        <a href="arqueo_caja.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <!-- FORMULARIO DE EDICIÓN -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="accion" value="actualizar">

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Usuario</label>
                    <input type="number" name="id_usuario" class="form-control" value="<?= $arqueo['id_usuario'] ?>" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Monto Inicial</label>
                    <input type="text" name="monto_inicial" class="form-control" value="<?= $arqueo['monto_inicial'] ?>" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Monto Final</label>
                    <input type="text" name="monto_final" class="form-control" value="<?= $arqueo['monto_final'] ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Ingresos</label>
                    <input type="text" name="total_ingresos" class="form-control" value="<?= $arqueo['total_ingresos'] ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Egresos</label>
                    <input type="text" name="total_egresos" class="form-control" value="<?= $arqueo['total_egresos'] ?>">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2"><?= htmlspecialchars($arqueo['observaciones']) ?></textarea>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="abierta" <?= $arqueo['estado'] === 'abierta' ? 'selected' : '' ?>>Abierta</option>
                        <option value="cerrada" <?= $arqueo['estado'] === 'cerrada' ? 'selected' : '' ?>>Cerrada</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Fecha Apertura</label>
                    <input type="datetime-local" name="fecha_apertura" class="form-control"
                           value="<?= date('Y-m-d\TH:i', strtotime($arqueo['fecha_apertura'])) ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Fecha Cierre</label>
                    <input type="datetime-local" name="fecha_cierre" class="form-control"
                           value="<?= $arqueo['fecha_cierre'] ? date('Y-m-d\TH:i', strtotime($arqueo['fecha_cierre'])) : '' ?>">
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
    © <?= date("Y") ?> Domo Creativo — Gestión de arqueo de caja.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
