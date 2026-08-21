<?php

require_once __DIR__ . "/../../controladores/PresupuestosControlador.php";

$controlador = new PresupuestosControlador();

$presupuestos = $controlador->listar();

?>

<div class="container mt-4">

    <h4 class="text-domocreativo mb-3">
        Presupuestos
    </h4>

    <div class="mb-3">

        <a href="index.php?accion=presupuestos_crear"
           class="btn btn-domocreativo me-2">
            Nuevo Presupuesto
        </a>

        <a href="index.php"
           class="btn btn-secondary">
            Volver al panel 
        </a>

    </div>

    <table class="table table-bordered">

        <tr>
            <th>ID</th>
            <th>Descripción</th>
            <th>Total estimado</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>

        <?php foreach ($presupuestos as $presupuesto) { ?>

            <tr>

                <td><?= $presupuesto["id_presupuesto"] ?></td>

                <td><?= htmlspecialchars($presupuesto["descripcion"]) ?></td>

                <td>$<?= $presupuesto["total_estimado"] ?></td>

                <td><?= $presupuesto["estado"] ?></td>

                <td>

                    <a href="index.php?accion=presupuestos_ver&id=<?= $presupuesto["id_presupuesto"] ?>"
                       class="btn btn-sm btn-secondary">
                        Ver
                    </a>

                    <a href="index.php?accion=presupuestos_editar&id=<?= $presupuesto["id_presupuesto"] ?>"
                       class="btn btn-sm btn-domocreativo">
                        Editar
                    </a>

                </td>

            </tr>

        <?php } ?>

    </table>

</div>