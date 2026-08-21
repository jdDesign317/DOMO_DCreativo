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

$ctrl = new UnidadesControlador();

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
?>

<h4 class="text-domocreativo mb-3">Ver Unidad</h4>

<div class="row">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">ID</label>
                    <p class="form-control-plaintext"><?= $unidad["id_unidad"] ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($unidad["nombre"]) ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Estado</label>
                    <p class="form-control-plaintext">
                        <?php if ($unidad["activo"] == 1) : ?>
                            <span class="badge bg-success">Activa</span>
                        <?php else : ?>
                            <span class="badge bg-secondary">Inactiva</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <a href="index.php?accion=unidades_editar&id=<?= $unidad["id_unidad"] ?>"
               class="btn btn-warning">Editar</a>
            <a href="index.php?accion=unidades" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
