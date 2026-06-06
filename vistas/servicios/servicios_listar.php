<h3 class="mb-3">Pedidos personalizados (Diseñador)</h3>

<table class="table table-bordered">

    <thead>
        <tr>
            <th>Cliente</th>
            <th>Producto</th>
            <th>Color</th>
            <th>Texto</th>
            <th>Fecha</th>
            <th>Acción</th>
        </tr>
    </thead>

    <tbody>

    <?php while ($s = $servicios->fetch_assoc()) { ?>

        <tr>

            <td>
                <?= $s["cliente"] ?> <?= $s["apellidos"] ?>
            </td>

            <td>
                <?= $s["producto"] ?>
            </td>

            <td>
                <?= $s["color"] ?>
            </td>

            <td>
                <?= $s["texto_personalizado"] ?>
            </td>

            <td>
                <?= $s["fecha_creacion"] ?>
            </td>

            <td>
                <a href="index.php?accion=servicios_editar&id=<?= $s["id_servicio_personalizado"] ?>"
                   class="btn btn-domocreativo btn-sm">
                    Editar
                </a>
            </td>

        </tr>

    <?php } ?>

    </tbody>

</table>