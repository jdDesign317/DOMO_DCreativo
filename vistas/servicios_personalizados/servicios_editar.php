<?php

require_once __DIR__ . "/../../controladores/ServiciosPersonalizadosControlador.php";

$controlador = new ServiciosPersonalizadosControlador();

$id = $_GET["id"];

$servicio = $controlador->ver($id);

if ($_POST) {

    $id_producto         = intval($_POST["id_producto"]);
    $color               = trim($_POST["color"]);
    $texto_personalizado = trim($_POST["texto_personalizado"]);
    $archivo_diseno      = trim($_POST["archivo_diseno"]);
    $estado              = $_POST["estado"];

    $controlador->editar(
        $id,
        $id_producto,
        $color,
        $texto_personalizado,
        $archivo_diseno,
        $estado
    );

    header("Location: index.php?accion=servicios_personalizados");
    exit;
}

?>

<h3>Editar Servicio Personalizado</h3>

<form method="POST">

    <input
        type="hidden"
        name="id_servicio_personalizado"
        value="<?= $servicio["id_servicio_personalizado"] ?>">

    <div class="mb-3">

        <label class="form-label">
            ID Producto
        </label>

        <input
            type="number"
            name="id_producto"
            class="form-control"
            value="<?= $servicio["id_producto"] ?>"
            required>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Color
        </label>

        <input
            type="text"
            name="color"
            class="form-control"
            value="<?= $servicio["color"] ?>"
            required>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Texto Personalizado
        </label>

        <textarea
            name="texto_personalizado"
            class="form-control"
            rows="3"><?= $servicio["texto_personalizado"] ?></textarea>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Archivo Diseño
        </label>

        <input
            type="text"
            name="archivo_diseno"
            class="form-control"
            value="<?= $servicio["archivo_diseno"] ?>">

    </div>

    <div class="mb-3">

        <label class="form-label">
            Estado
        </label>

        <select
            name="estado"
            class="form-control">

            <option value="pendiente"
                <?= $servicio["estado"] == "pendiente" ? "selected" : "" ?>>
                Pendiente
            </option>

            <option value="en_diseno"
                <?= $servicio["estado"] == "en_diseno" ? "selected" : "" ?>>
                En diseño
            </option>

            <option value="aprobado"
                <?= $servicio["estado"] == "aprobado" ? "selected" : "" ?>>
                Aprobado
            </option>

            <option value="cancelado"
                <?= $servicio["estado"] == "cancelado" ? "selected" : "" ?>>
                Cancelado
            </option>

        </select>

    </div>

    <button type="submit" class="btn btn-domocreativo">
        Guardar Cambios
    </button>

    <a href="index.php?accion=servicios_personalizados"
       class="btn btn-secondary">
        Volver
    </a>

</form>