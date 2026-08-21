<?php

require_once __DIR__ . "/../../controladores/PresupuestosControlador.php";
require_once __DIR__ . "/../../config/Conexion.php";

$controlador = new PresupuestosControlador();

// TRAER TIPOS DE EVENTO PARA EL SELECT
$conexion = (new Conexion())->getConexion();
$tipos_evento = $conexion->query(
    "SELECT id_tipo_evento, nombre FROM tipos_evento WHERE activo = 1"
)->fetch_all(MYSQLI_ASSOC);

$mensaje = "";

if ($_POST) {

    $id_usuario     = $_SESSION["id_usuario"];
    $id_tipo_evento = intval($_POST["id_tipo_evento"]);
    $descripcion    = trim($_POST["descripcion"]);
    $total_estimado = floatval($_POST["total_estimado"]);

    if (empty($descripcion) || $id_tipo_evento <= 0) {

        $mensaje = "Completá el tipo de evento y la descripción.";

    } else {

        $controlador->crear($id_usuario, $id_tipo_evento, $descripcion, $total_estimado);

        header("Location: index.php?accion=presupuestos");
        exit;
    }
}

?>

<div class="container mt-4">

    <h4 class="text-domocreativo mb-3">
        Crear Presupuesto
    </h4>

    <?php if ($mensaje): ?>
        <div class="alert alert-warning"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">

            <label class="form-label">Tipo de evento</label>

            <select name="id_tipo_evento" class="form-control" required>
                <option value="">Seleccionar...</option>
                <?php foreach ($tipos_evento as $tipo): ?>
                    <option value="<?= $tipo['id_tipo_evento'] ?>">
                        <?= htmlspecialchars($tipo['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

        </div>

        <div class="mb-3">

            <label class="form-label">Descripción</label>

            <textarea name="descripcion"
                      class="form-control"
                      rows="3"
                      required></textarea>

        </div>

        <div class="mb-3">

            <label class="form-label">Total estimado</label>

            <input type="number"
                   step="0.01"
                   name="total_estimado"
                   class="form-control"
                   required>

        </div>

        <button type="submit" class="btn btn-domocreativo">
            Guardar
        </button>

        <a href="index.php?accion=presupuestos"
           class="btn btn-secondary">
            Volver
        </a>

    </form>

</div>
