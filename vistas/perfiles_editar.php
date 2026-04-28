<?php
require_once "../controladores/PerfilesControlador.php";

$controller = new PerfilesControlador();

// Validar ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("ID no válido");
}

$id = intval($_GET["id"]);
$perfil = $controller->obtener($id);

if (!$perfil) {
    die("Perfil no encontrado.");
}

// --- ACTUALIZAR ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "actualizar") {
    $nombre = trim($_POST["nombre"]);

    if (!empty($nombre)) {
        if ($controller->actualizar($id, $nombre)) {
            $mensaje = "<div class='alert alert-success mt-3 text-center'>Perfil actualizado correctamente.</div>";
            $perfil = $controller->obtener($id); // refrescar
        } else {
            $mensaje = "<div class='alert alert-danger mt-3 text-center'>Error al actualizar.</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-warning mt-3 text-center'>El campo no puede estar vacío.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<div class="container mt-5 pt-4">

    <div class="card shadow-sm soft-card">

        <div class="card-header bg-white border-0 text-danger fw-semibold">
            <i class="bi bi-pencil"></i> Editar Perfil
        </div>

        <div class="card-body">

            <?php if (isset($mensaje)) echo $mensaje; ?>

            <form method="POST" class="row g-3">
                <input type="hidden" name="accion" value="actualizar">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nombre</label>
                    <input type="text" name="nombre" class="form-control"
                           value="<?= htmlspecialchars($perfil['nombre']) ?>" required>
                </div>

                <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-save"></i> Guardar Cambios
                    </button>

                    <a href="perfiles.php" class="btn btn-secondary px-4 ms-2">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>

<footer class="text-center text-muted small py-4">
     <?= date("Y") ?> Domo Creativo — Gestión de perfiles.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
