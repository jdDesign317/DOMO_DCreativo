<?php
error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once "../controladores/PresupuestosControlador.php";
require_once "../controladores/UsuariosControlador.php";

$presupuestosControlador = new PresupuestosControlador();
$usuariosControlador = new UsuariosControlador();

$mensaje = "";

// Obtener lista de usuarios (para asignar presupuesto)
$usuarios = $usuariosControlador->listar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_usuario = $_POST["id_usuario"];
    $total_estimado = $_POST["total_estimado"];
    $estado = $_POST["estado"];

    if ($presupuestosControlador->crear($id_usuario, $total_estimado, $estado)) {
        $mensaje = "<div class='alert alert-success text-center'> Presupuesto creado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger text-center'> Error al crear el presupuesto.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Presupuesto | Domo Creativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/estilos.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg" style="max-width: 600px; margin: 0 auto; border-radius: 15px;">
        <div class="card-body">
            <h3 class="text-center text-danger fw-bold mb-4">Nuevo Presupuesto</h3>

            <?= $mensaje ?>

            <form method="POST">

                <div class="mb-3">
                    <label for="id_usuario" class="form-label fw-semibold">Usuario asignado</label>
                    <select name="id_usuario" id="id_usuario" class="form-select" required>
                        <option value="">-- Seleccionar usuario --</option>
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?= htmlspecialchars($usuario["id_usuario"]) ?>">
                                <?= htmlspecialchars($usuario["nombre"]) ?> (<?= htmlspecialchars($usuario["email"]) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="total_estimado" class="form-label fw-semibold">Total estimado</label>
                    <input type="number" step="0.01" name="total_estimado" id="total_estimado" class="form-control" placeholder="Ej: 1200.50" required>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label fw-semibold">Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="pendiente">Pendiente</option>
                        <option value="aprobado">Aprobado</option>
                        <option value="rechazado">Rechazado</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-danger w-100 mt-3">
                    <i class="bi bi-plus-circle me-1"></i> Crear Presupuesto
                </button>

                <div class="text-center mt-3">
                    <a href="presupuestos.php" class="text-decoration-none text-danger">
                        <i class="bi bi-arrow-left"></i> Volver a la lista
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</body>
</html>
