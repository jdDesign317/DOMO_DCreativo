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

require_once __DIR__ . "/../../../../controladores/admin/maestras/MetodosPagoControlador.php";

$ctrl = new MetodosPagoControlador();

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: index.php?accion=metodos_pago");
    exit;
}

$id_metodo_pago = intval($_GET["id"]);
$metodo         = $ctrl->buscarPorId($id_metodo_pago);

if (!$metodo) {
    header("Location: index.php?accion=metodos_pago");
    exit;
}
?>

<h4 class="text-domocreativo mb-3">Ver Método de Pago</h4>

<div class="row">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">ID</label>
                    <p class="form-control-plaintext"><?= $metodo["id_metodo_pago"] ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre</label>
                    <p class="form-control-plaintext"><?= htmlspecialchars($metodo["nombre"]) ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Estado</label>
                    <p class="form-control-plaintext">
                        <?php if ($metodo["activo"] == 1) : ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else : ?>
                            <span class="badge bg-secondary">Inactivo</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <a href="index.php?accion=metodos_pago_editar&id=<?= $metodo["id_metodo_pago"] ?>"
               class="btn btn-warning">Editar</a>
            <a href="index.php?accion=metodos_pago" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
