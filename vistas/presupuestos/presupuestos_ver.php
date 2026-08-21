<?php

require_once __DIR__ . "/../../controladores/PresupuestosControlador.php";

$controlador = new PresupuestosControlador();

$id = $_GET["id"];

$presupuesto = $controlador->ver($id);

?>

<div class="container mt-4">

    <h4 class="text-domocreativo mb-3">
        Detalle del presupuesto
    </h4>

    <table class="table table-bordered">

        <tr>
            <th>ID</th>
            <td><?= $presupuesto["id_presupuesto"] ?></td>
        </tr>

        <tr>
            <th>Tipo de evento</th>
            <td><?= htmlspecialchars($presupuesto["tipo_evento"] ?? "-") ?></td>
        </tr>

        <tr>
            <th>Total estimado</th>
            <td>$<?= $presupuesto["total_estimado"] ?></td>
        </tr>

        <tr>
            <th>Descripción</th>
            <td><?= $presupuesto["descripcion"] ?></td>
        </tr>

        <tr>
            <th>Estado</th>
            <td><?= $presupuesto["estado"] ?></td>
        </tr>

    </table>

    <a href="index.php?accion=presupuestos"
       class="btn btn-secondary">
        Volver
    </a>

    <a href="index.php?accion=presupuestos_editar&id=<?= $presupuesto["id_presupuesto"] ?>"
       class="btn btn-domocreativo">
        Editar
    </a>

</div>