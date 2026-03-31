<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

require_once "../controladores/ProductosControlador.php";
$controller = new ProductosControlador();

$mensaje = "";

// --- CREAR PRODUCTO ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "insertar") {
    $nombre      = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $precio      = floatval($_POST["precio"]);

    if (!empty($nombre) && !empty($descripcion) && $precio > 0) {
        if ($controller->insertar($nombre, $descripcion, $precio)) {
            $mensaje = "<div class='alert alert-success text-center'>✅ Producto creado correctamente.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger text-center'>❌ Error al crear el producto.</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-warning text-center'>⚠️ Todos los campos son obligatorios y el precio debe ser mayor a 0.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Producto | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="container mt-5 pt-4">
    <h3 class="text-danger mb-4">Nuevo Producto</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <?= $mensaje ?>

            <form method="POST" class="row g-3">
                <input type="hidden" name="accion" value="insertar">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Precio ($)</label>
                    <input type="number" name="precio" step="0.01" min="0" class="form-control" required>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                </div>

                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-save me-1"></i> Guardar
                    </button>
                    <!-- BOTÓN VOLVER CORREGIDO -->
                    <a href="productos.php" class="btn btn-secondary ms-2">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<footer class="text-center text-muted small py-4">
    © <?= date("Y") ?> Domo Creativo — Gestión de productos.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
