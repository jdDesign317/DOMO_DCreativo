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

require_once __DIR__ . "/../../../../controladores/admin/maestras/TiposEventoControlador.php";

$ctrl    = new TiposEventoControlador();
$mensaje = "";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: index.php?accion=tipos_evento");
    exit;
}

$id_tipo_evento = intval($_GET["id"]);
$tipo           = $ctrl->buscarPorId($id_tipo_evento);

if (!$tipo) {
    header("Location: index.php?accion=tipos_evento");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre    = trim($_POST["nombre"]);
    $resultado = $ctrl->actualizar($id_tipo_evento, $nombre);

    if ($resultado === "vacio") {
        $mensaje = "El nombre no puede estar vacío.";
    } elseif ($resultado === "duplicado") {
        $mensaje = "Ya existe un tipo de evento con ese nombre.";
    } elseif ($resultado) {
        header("Location: index.php?accion=tipos_evento");
        exit;
    } else {
        $mensaje = "Error al actualizar el tipo de evento.";
    }
}
?>

<h4 class="text-domocreativo mb-3">Editar Tipo de Evento</h4>

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
                       value="<?= htmlspecialchars($tipo["nombre"]) ?>"
                       required>
            </div>
            <div class="mb-3">
                <label class="form-label">Estado actual</label>
                <input type="text"
                       class="form-control"
                       value="<?= $tipo["activo"] == 1 ? 'Activo' : 'Inactivo' ?>"
                       disabled>
                <small class="text-muted">El estado se cambia desde el listado con Desactivar / Reactivar.</small>
            </div>
            <button type="submit" class="btn btn-domocreativo w-100">Guardar cambios</button>
            <a href="index.php?accion=tipos_evento" class="btn btn-secondary w-100 mt-2">Volver</a>
        </form>
    </div>
</div>
