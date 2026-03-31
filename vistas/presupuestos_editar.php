<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

require_once "../controladores/PresupuestosControlador.php";
$presCtrl = new PresupuestosControlador();

if (!isset($_GET["id"])) {
    die("ID no especificado");
}

$id = intval($_GET["id"]);
$presupuesto = $presCtrl->buscar($id);

if (!$presupuesto) {
    die("Presupuesto no encontrado");
}

$mensaje = null;
$tipoMensaje = "";

/* ACTUALIZAR */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $datos = [
        "id_usuario"     => $_POST["id_usuario"],
        "total_estimado" => $_POST["total_estimado"],
        "estado"         => $_POST["estado"]
    ];

    if ($presCtrl->actualizar($id, $datos)) {
        $mensaje = "Presupuesto actualizado correctamente.";
        $tipoMensaje = "success";

        $presupuesto = $presCtrl->buscar($id); // recarga datos
    } else {
        $mensaje = "Error al actualizar el presupuesto.";
        $tipoMensaje = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Presupuesto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="light-theme">

<div class="container mt-5">

    <h3 class="text-danger mb-4">Editar Presupuesto #<?= $presupuesto["id_presupuesto"] ?></h3>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?= $tipoMensaje ?> text-center"><?= $mensaje ?></div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">ID Usuario</label>
            <input type="number" name="id_usuario" class="form-control" 
                   value="<?= $presupuesto["id_usuario"] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Total Estimado</label>
            <input type="number" step="0.01" name="total_estimado" class="form-control" 
                   value="<?= $presupuesto["total_estimado"] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select" required>
                <option value="pendiente" <?= $presupuesto["estado"] == "pendiente" ? "selected" : "" ?>>Pendiente</option>
                <option value="aceptado" <?= $presupuesto["estado"] == "aceptado" ? "selected" : "" ?>>Aceptado</option>
                <option value="rechazado" <?= $presupuesto["estado"] == "rechazado" ? "selected" : "" ?>>Rechazado</option>
            </select>
        </div>

        <button class="btn btn-danger">Guardar Cambios</button>
        <a href="presupuestos.php" class="btn btn-secondary">Volver</a>

    </form>

</div>

</body>
</html>
