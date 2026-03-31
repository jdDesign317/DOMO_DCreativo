<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once "../controladores/UsuariosControlador.php";
$usuariosControlador = new UsuariosControlador();

// Obtener ID del usuario desde la URL
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$usuario = $usuariosControlador->ver($id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Usuario | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Estilos -->
    <link rel="stylesheet" href="../assets/css/usuarios.css">
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
                <li class="nav-item"><a class="nav-link" href="productos.php">Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="presupuestos.php">Presupuestos</a></li>
                <li class="nav-item"><a class="nav-link active fw-semibold" href="usuarios.php">Usuarios</a></li>
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
<div class="container page-container">
    <div class="card soft-card shadow-sm mt-5">
        <div class="card-header bg-white border-0 text-danger fw-semibold">
            <i class="bi bi-person-lines-fill me-2"></i> Detalle del Usuario
        </div>
        <div class="card-body">
            <?php if ($usuario): ?>
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td><?= $usuario["id_usuario"] ?></td>
                    </tr>
                    <tr>
                        <th>Nombre</th>
                        <td><?= htmlspecialchars($usuario["nombre"]) ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= htmlspecialchars($usuario["email"]) ?></td>
                    </tr>
                    <tr>
                        <th>Perfil</th>
                        <td><?= htmlspecialchars($usuario["perfil"] ?? "Sin perfil") ?></td>
                    </tr>
                </table>
            <?php else: ?>
                <div class="alert alert-danger text-center">❌ Usuario no encontrado.</div>
            <?php endif; ?>

            <div class="mt-3 d-flex justify-content-between">
                <a href="usuarios.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
                <?php if ($usuario): ?>
                    <a href="usuarios_editar.php?id=<?= $usuario["id_usuario"] ?>" class="btn btn-outline-primary">
                        <i class="bi bi-pencil-square"></i> Editar
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<footer class="text-center mt-5 py-4 text-muted small">
    © <?= date("Y") ?> Domo Creativo — Gestión de usuarios.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
