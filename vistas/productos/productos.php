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
        <th>Acciones</th>
    </tr>

    <?php foreach ($productos as $p): ?>

        <tr>

            <td><?= $p["id_producto"] ?></td>

            <td><?= $p["nombre"] ?></td>

            <td><?= $p["descripcion"] ?></td>

            <td>$ <?= $p["precio"] ?></td>

            <td>

                <a href="index.php?accion=productos_ver&id=<?= $p["id_producto"] ?>"
                   class="btn btn-info btn-sm">
                    Ver
                </a>

                <a href="index.php?accion=productos_editar&id=<?= $p["id_producto"] ?>"
                   class="btn btn-warning btn-sm">
                    Editar
                </a>

                <a href="index.php?accion=productos_eliminar&id=<?= $p["id_producto"] ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('¿Eliminar producto?')">
                    Eliminar
                </a>

                <!-- BOTÓN CARRITO -->
               <a href="index.php?accion=carrito_agregar&id=<?= $p["id_producto"] ?>"
                class="btn btn-carrito btn-sm">
                    <span class="icono-carrito">🛒</span>
                </a>

            </td>

        </tr>

    <?php endforeach; ?>

</table>