<h3>Productos</h3>

<div class="mb-3">

    <a href="/DOMOCreativo/index.php" class="btn btn-secondary">
        Volver
    </a>

</div>

<table class="table table-bordered">

    <tr>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Agregar</th>
    </tr>

    <?php foreach ($productos as $p): ?>

        <tr>

            <td><?= $p["nombre"] ?></td>

            <td><?= $p["descripcion"] ?></td>

            <td>$ <?= $p["precio"] ?></td>

            <td>
                <!-- BOTÓN CARRITO -->
                <a href="/DOMOCreativo/index.php?accion=carrito_agregar&id=<?= $p["id_producto"] ?>"
                   class="btn btn-carrito btn-sm">
                    <span class="icono-carrito">🛒</span>
                </a>
            </td>

        </tr>

    <?php endforeach; ?>

</table>
