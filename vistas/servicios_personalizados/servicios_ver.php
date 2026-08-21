<?php

require_once __DIR__ . "/../../controladores/ServiciosPersonalizadosControlador.php";

$controlador = new ServiciosPersonalizadosControlador();

$id = $_GET["id"];

$servicio = $controlador->ver($id);

?>

<body>

<div class="container mt-4">

    <h4 class="text-domocreativo mb-3">
        Detalle del servicio personalizado
    </h4>

    <table class="table table-bordered">

        <tr>
            <th>ID</th>
            <td><?= $servicio["id_servicio_personalizado"] ?></td>
        </tr>

        <tr>
            <th>Usuario</th>
            <td><?= $servicio["id_usuario"] ?></td>
        </tr>

        <tr>
            <th>Producto</th>
            <td><?= $servicio["id_producto"] ?></td>
        </tr>

        <tr>
            <th>Color</th>
            <td><?= $servicio["color"] ?></td>
        </tr>

        <tr>
            <th>Texto Personalizado</th>
            <td><?= $servicio["texto_personalizado"] ?></td>
        </tr>

        <tr>
            <th>Archivo Diseño</th>
            <td><?= $servicio["archivo_diseno"] ?></td>
        </tr>

        <tr>
            <th>Estado</th>
            <td><?= $servicio["estado"] ?></td>
        </tr>

        <tr>
            <th>Fecha Creación</th>
            <td><?= $servicio["fecha_creacion"] ?></td>
        </tr>

    </table>

    <a href="index.php?accion=servicios_personalizados"
       class="btn btn-secondary">
        Volver
    </a>

    <a href="index.php?accion=servicios_editar&id=<?= $servicio["id_servicio_personalizado"] ?>"
       class="btn btn-domocreativo">
        Editar
    </a>

</div>

</body>