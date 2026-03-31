<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

require_once "../controladores/ProductosControlador.php";
$controller = new ProductosControlador();

// Validar ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("ID no válido");
}

$id = intval($_GET["id"]);
$producto = $controller->obtener($id);

if (!$producto) {
    die("Producto no encontrado.");
}

// --- ACTUALIZAR ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "actualizar") {
    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $precio = floatval($_POST["precio"]);

    if (!empty($nombre) && !empty($descripcion) && $precio > 0) {
        if ($controller->actualizar($id, $nombre, $descripcion, $precio)) {
            $mensaje = "<div class='alert alert-success mt-3 text-center'>Producto actualizado correctamente.</div>";
            $producto = $controller->obtener($id); // refrescar datos
        } else {
            $mensaje = "<div class='alert alert-danger mt-3 text-center'>Error al actualizar el producto.</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-warning mt-3 text-center'>Todos los campos son obligatorios y el precio debe ser mayor a 0.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
                <li class="nav-item"><a class="nav-link active fw-semibold" href="productos.php">Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="presupuestos.php">Presupuestos</a></li>
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

    <div class="card shadow-sm soft-card">
        <div class="card-header bg-white border-0 text-danger fw-semibold">
            <i class="bi bi-pencil-square"></i> Editar Producto
        </div>

        <div class="card-body">
            <?php if (isset($mensaje)) echo $mensaje; ?>

            <form method="POST" class="mt-3">
                <input type="hidden" name="accion" value="actualizar">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre</label>
                    <input type="text" name="nombre" class="form-control"
                           value="<?= htmlspecialchars($producto['nombre']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Precio</label>
                    <input type="number" step="0.01" name="precio" class="form-control"
                           value="<?= $producto['precio'] ?>" required>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-save"></i> Guardar Cambios
                    </button>
                    <a href="productos.php" class="btn btn-secondary px-4 ms-2">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<footer class="text-center mt-5 py-4 text-muted small">
    © <?= date("Y") ?> Domo Creativo — Gestión de productos.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
