<?php

require_once __DIR__ . "/../../controladores/PresupuestosControlador.php";

$controlador = new PresupuestosControlador();

$id = $_GET["id"];

$presupuesto = $controlador->ver($id);

if ($_POST) {

    $controlador->editar(
        $_POST["id_presupuesto"],
        $_POST["descripcion"],
        $_POST["total_estimado"],
        $_POST["estado"]
    );

    header("Location: index.php?accion=presupuestos");
    exit;
}

?>

<div class="container mt-4">

    <h4 class="text-domocreativo mb-3">
        Editar Presupuesto
    </h4>

    <form method="POST">

        <input type="hidden"
               name="id_presupuesto"
               value="<?= $presupuesto["id_presupuesto"] ?>">

        <div class="mb-3">

            <label class="form-label">Descripción</label>

            <textarea name="descripcion"
                      class="form-control"
                      rows="3"
                      required><?= $presupuesto["descripcion"] ?></textarea>

        </div>

        <div class="mb-3">

            <label class="form-label">Total estimado</label>

            <input type="number"
                   name="total_estimado"
                   class="form-control"
                   value="<?= $presupuesto["total_estimado"] ?>"
                   required>

        </div>

        <div class="mb-3">

            <label class="form-label">Estado</label>

            <select name="estado" class="form-control">

                <option value="pendiente"
                    <?= $presupuesto["estado"] == "pendiente" ? "selected" : "" ?>>
                    Pendiente
                </option>

                <option value="aprobado"
                    <?= $presupuesto["estado"] == "aprobado" ? "selected" : "" ?>>
                    Aprobado
                </option>

                <option value="rechazado"
                    <?= $presupuesto["estado"] == "rechazado" ? "selected" : "" ?>>
                    Rechazado
                </option>

            </select>

        </div>

        <button type="submit" class="btn btn-domocreativo">
            Guardar Cambios
        </button>

        <a href="index.php?accion=presupuestos"
           class="btn btn-secondary">
            Volver
        </a>

    </form>

</div>