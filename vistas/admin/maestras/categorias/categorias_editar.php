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

require_once __DIR__ . "/../../../../controladores/admin/maestras/CategoriasControlador.php";

$ctrl    = new CategoriasControlador();
$mensaje = "";

// VALIDAR ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: index.php?accion=categorias");
    exit;
}

$id_categoria = intval($_GET["id"]);

// BUSCAR CATEGORIA
$categoria = $ctrl->buscarPorId($id_categoria);

if (!$categoria) {
    header("Location: index.php?accion=categorias");
    exit;
}

// ACTUALIZAR
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"]);

    $resultado = $ctrl->actualizar($id_categoria, $nombre);

    if ($resultado) {
        header("Location: index.php?accion=categorias");
        exit;
    } else {
        $mensaje = "Error al actualizar la categoría.";
    }
}
?>

<h4 class="text-domocreativo mb-3">Editar Categoría</h4>

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
                       value="<?= htmlspecialchars($categoria["nombre"]) ?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Estado actual</label>
                <input type="text"
                       class="form-control"
                       value="<?= $categoria["activo"] == 1 ? 'Activa' : 'Inactiva' ?>"
                       disabled>
                <small class="text-muted">
                    El estado se cambia desde el listado con Desactivar / Reactivar.
                </small>
            </div>

            <button type="submit" class="btn btn-domocreativo w-100">
                Guardar cambios
            </button>

            <a href="index.php?accion=categorias" class="btn btn-secondary w-100 mt-2">
                Volver
            </a>

        </form>

    </div>
</div>