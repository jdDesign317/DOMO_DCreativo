<?php
// VALIDAR SESION
if (!isset($_SESSION["id_usuario"])) {
    header("Location: vistas/auth/login.php");
    exit;
}

// SOLO ADMIN
if ($_SESSION["id_perfil"] != 2) {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . "/../../../../controladores/admin/maestras/UnidadesControlador.php";

$ctrl    = new UnidadesControlador();
$mensaje = "";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: index.php?accion=unidades");
    exit;
}

$id_unidad = intval($_GET["id"]);
$unidad    = $ctrl->buscarPorId($id_unidad);

if (!$unidad) {
    header("Location: index.php?accion=unidades");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre    = trim($_POST["nombre"]);
    $resultado = $ctrl->actualizar($id_unidad, $nombre);

    if ($resultado === "vacio") {
        $mensaje = "El nombre no puede estar vacío.";
    } elseif ($resultado === "duplicado") {
        $mensaje = "Ya existe una unidad con ese nombre.";
    } elseif ($resultado) {
        header("Location: index.php?accion=unidades");
        exit;
    } else {
        $mensaje = "Error al actualizar la unidad.";
    }
}
?>

<h4 class="text-domocreativo mb-3">Editar Unidad</h4>

<?php if ($mensaje) : ?>
    <div class="alert alert-danger"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-5">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text"
                       name="nombre"
                       class="form-control"
                       value="<?= htmlspecialchars($unidad["nombre"]) ?>"
                       required>
            </div>
            <div class="mb-3">
                <label class="form-label">Estado actual</label>
                <input type="text"
                       class="form-control"
                       value="<?= $unidad["activo"] == 1 ? 'Activa' : 'Inactiva' ?>"
                       disabled>
                <small class="text-muted">El estado se cambia desde el listado con Desactivar / Reactivar.</small>
            </div>
            <button type="submit" class="btn btn-domocreativo w-100">Guardar cambios</button>
            <a href="index.php?accion=unidades" class="btn btn-secondary w-100 mt-2">Volver</a>
        </form>
    </div>
</div>
