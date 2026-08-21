<h3>Productos</h3>

<div class="mb-3">

    <a href="index.php" class="btn btn-secondary">
        Volver
    </a>

    <a href="index.php?accion=productos_crear" class="btn btn-domocreativo">
        Nuevo producto
    </a>

</div>

<table class="table table-bordered">

    <tr>
        <th>Id</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($productos as $p): ?>

        <tr>

            <td><?= $p["id_producto"] ?></td>

            <td><?= $p["nombre"] ?></td>

            <td><?= $p["descripcion"] ?></td>

            <td>$ <?= $p["precio"] ?></td>

            <td>
                <?php if ($p["activo"] == 1): ?>
                    <span class="badge bg-success">Activo</span>
                <?php else: ?>
                    <span class="badge bg-secondary">Inactivo</span>
                <?php endif; ?>
            </td>

            <td>

                <a href="index.php?accion=productos_ver&id=<?= $p["id_producto"] ?>"
                   class="btn btn-info btn-sm">
                    Ver
                </a>

                <a href="index.php?accion=productos_editar&id=<?= $p["id_producto"] ?>"
                   class="btn btn-warning btn-sm">
                    Editar
                </a>

                <?php if ($p["activo"] == 1): ?>

                    <a href="index.php?accion=productos_eliminar&id=<?= $p["id_producto"] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('¿Dar de baja este producto?')">
                        Eliminar
                    </a>

                <?php else: ?>

                    <a href="index.php?accion=productos_reactivar&id=<?= $p["id_producto"] ?>"
                       class="btn btn-success btn-sm"
                       onclick="return confirm('¿Reactivar este producto?')">
                        Reactivar
                    </a>

                <?php endif; ?>

            </td>

        </tr>

    <?php endforeach; ?>

</table>