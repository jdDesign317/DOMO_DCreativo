<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once "../controladores/MensajesControlador.php";

$mensajesController = new MensajesControlador();

/* 
   OBTENER MENSAJE A EDITAR
 */
if (!isset($_GET["id"])) {
    header("Location: mensajes.php");
    exit;
}

$id = intval($_GET["id"]);
$mensaje = $mensajesController->obtener($id);

if (!$mensaje) {
    echo "<div class='alert alert-danger'>Mensaje no encontrado.</div>";
    exit;
}

/* 
   ACTUALIZAR MENSAJE
 */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "actualizar") {
    $id_usuario = intval($_POST["id_usuario"]);
    $remitente  = trim($_POST["remitente"]);
    $texto      = trim($_POST["mensaje"]);
    $fecha      = !empty($_POST["fecha"])
        ? date("Y-m-d H:i:s", strtotime($_POST["fecha"]))
        : $mensaje["fecha"];

    $mensajesController->actualizar($id, $id_usuario, $remitente, $texto, $fecha);
    header("Location: mensajes.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Mensaje | Domo Creativo</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/mensajes.css">
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
                <li class="nav-item"><a class="nav-link active fw-semibold" href="mensajes.php">Mensajes</a></li>
                <li class="nav-item"><a class="nav-link" href="usuarios.php">Usuarios</a></li>
                <li class="nav-item"><a class="nav-link" href="pagos.php">Pagos</a></li>
                <li class="nav-item"><a class="nav-link" href="facturas.php">Facturas</a></li>
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
        <h4 class="text-danger fw-bold mb-0">Editar Mensaje</h4>
        <a href="mensajes.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <!-- FORMULARIO DE EDICIÓN -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="accion" value="actualizar">

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Usuario</label>
                    <input type="number" name="id_usuario" class="form-control" value="<?= $mensaje['id_usuario'] ?>" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Remitente</label>
                    <input type="text" name="remitente" class="form-control" value="<?= htmlspecialchars($mensaje['remitente']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mensaje</label>
                    <textarea name="mensaje" class="form-control" rows="3" required><?= htmlspecialchars($mensaje['mensaje']) ?></textarea>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Fecha</label>
                    <input type="datetime-local" name="fecha" class="form-control"
                           value="<?= date('Y-m-d\TH:i', strtotime($mensaje['fecha'])) ?>">
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
    © <?= date("Y") ?> Domo Creativo — Gestión de mensajes.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
