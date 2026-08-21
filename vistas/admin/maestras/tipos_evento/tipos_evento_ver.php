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

$ctrl = new TiposEventoControlador();

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
?>

<h4 class="text-domocreativo mb-3">Ver Tipo de Evento</h4>

<div class="row">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">ID</label>
                    <p class="form-control-plaintext"><?= $tipo["id_tipo_evento"] ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($tipo["nombre"]) ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Estado</label>
                    <p class="form-control-plaintext">
                        <?php if ($tipo["activo"] == 1) : ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else : ?>
                            <span class="badge bg-secondary">Inactivo</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <a href="index.php?accion=tipos_evento_editar&id=<?= $tipo["id_tipo_evento"] ?>"
               class="btn btn-warning">Editar</a>
            <a href="index.php?accion=tipos_evento" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
