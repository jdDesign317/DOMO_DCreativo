<?php
session_start();

// Evitar acceso sin login
if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit;
}

require_once "../controladores/PerfilesControlador.php";

$controller = new PerfilesControlador();
$mensaje = null;

/* AGREGAR PERFIL */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["accion"]) && $_POST["accion"] == "agregar") {
    $descripcion = trim($_POST["descripcion"]);

    if (!empty($descripcion)) {
        $controller->insertar($descripcion);
        $mensaje = "<div class='alert alert-success mt-3 text-center'>Perfil agregado correctamente.</div>";
    }
}

/* ELIMINAR PERFIL */
if (isset($_GET["eliminar"])) {
    $controller->eliminar($_GET["eliminar"]);
    $mensaje = "<div class='alert alert-success mt-3 text-center'>Perfil eliminado.</div>";
}

$perfiles = $controller->listar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Perfiles | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

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
                <li class="nav-item"><a class="nav-link" href="usuarios.php">Usuarios</a></li>
                <li class="nav-item"><a class="nav-link active fw-semibold" href="perfiles.php">Perfiles</a></li>
                <li class="nav-item"><a class="nav-link" href="productos.php">Productos</a></li> 
                <li class="nav-item"><a class="nav-link" href="presupuestos.php">Presupuestos</a></li>
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

<div class="container mt-5 pt-5">

    <div class="card shadow-sm soft-card">
        <div class="card-header bg-white border-0 text-danger fw-semibold">
            <i class="bi bi-people"></i> Gestión de Perfiles
        </div>

        <div class="card-body">

            <?php if ($mensaje) echo $mensaje; ?>

            <!-- FORMULARIO AGREGAR -->
            <form method="POST" class="row g-3 mb-4">
                <input type="hidden" name="accion" value="agregar">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nombre</label>
                    <input type="text" name="descripcion" class="form-control" required>
                </div>

                <div class="col-md-12 text-center">
                    <button class="btn btn-danger px-4 mt-2">
                        <i class="bi bi-save"></i> Guardar Perfil
                    </button>
                    <a href="perfiles.php" class="btn btn-secondary px-4 mt-2 ms-2">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>

            </form>

            <!-- TABLA -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($perfiles as $p): ?>
                        <tr>
                            <td><?= $p["id_perfil"] ?></td>
                            <td><?= htmlspecialchars($p["nombre"]) ?></td>
                            <td class="text-center">
                                <a href="perfiles_editar.php?id=<?= $p["id_perfil"] ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="?eliminar=<?= $p["id_perfil"] ?>"
                                   onclick="return confirm('¿Eliminar este perfil?')"
                                   class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<footer class="text-center text-muted small py-4">
    © <?= date("Y") ?> Domo Creativo — Gestión de perfiles.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
