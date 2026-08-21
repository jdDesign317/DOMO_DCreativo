<?php

require_once __DIR__ . "/../../controladores/ServiciosPersonalizadosControlador.php";

$serviciosControlador = new ServiciosPersonalizadosControlador();

// LISTAR
$servicios = $serviciosControlador->listar();

?>

<h3>Servicios Personalizados</h3>

<div class="mb-3">

    <a href="index.php" class="btn btn-secondary">
        Volver al panel 
    </a>

    <a href="index.php?accion=servicios_crear" class="btn btn-domocreativo">
        Nuevo Servicio
    </a>

</div>

<table class="table table-bordered table-hover">

    <thead>

        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Producto</th>
            <th>Color</th>
            <th>Texto</th>
            <th>Estado</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>

    </thead>

    <tbody>

    <?php foreach ($servicios as $servicio): ?>

        <tr>

            <td><?= $servicio["id_servicio_personalizado"] ?></td>

            <td><?= $servicio["id_usuario"] ?></td>

            <td><?= $servicio["id_producto"] ?></td>

            <td><?= $servicio["color"] ?></td>

            <td><?= $servicio["texto_personalizado"] ?></td>

            <td><?= $servicio["estado"] ?></td>

            <td><?= $servicio["fecha_creacion"] ?></td>

            <td>

                <a href="index.php?accion=servicios_ver&id=<?= $servicio["id_servicio_personalizado"] ?>"
                   class="btn btn-info btn-sm">
                    Ver
                </a>

                <a href="index.php?accion=servicios_editar&id=<?= $servicio["id_servicio_personalizado"] ?>"
                   class="btn btn-warning btn-sm">
                    Editar
                </a>

                <a href="index.php?accion=servicios_eliminar&id=<?= $servicio["id_servicio_personalizado"] ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('¿Eliminar servicio?')">
                    Eliminar
                </a>

            </td>

        </tr>

    <?php endforeach; ?>

    </tbody>

</table>