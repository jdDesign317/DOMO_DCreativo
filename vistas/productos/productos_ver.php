
<?php

// VALIDAR SESION
if (!isset($_SESSION["id_usuario"])) {

   header("Location: vistas/auth/login.php");
    exit;
}

require_once __DIR__ . "/../../controladores/ProductosControlador.php";

$controladorProductos = new ProductosControlador();

// VALIDAR ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    echo "ID inválido";
    exit;
}

$id_producto = intval($_GET["id"]);

// BUSCAR PRODUCTO
$producto = $controladorProductos->buscarPorId($id_producto);

if (!$producto) {

    echo "Producto no encontrado";
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Ver producto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/DOMOCreativo/assets/css/estilos.css">

</head>

<body>

<div class="container mt-4">

    <h4 class="text-domocreativo mb-3">
        Detalle del producto
    </h4>

    <table class="table table-bordered">

        <tr>
            <th>ID</th>
            <td><?= $producto["id_producto"] ?></td>
        </tr>

        <tr>
            <th>Nombre</th>
            <td><?= htmlspecialchars($producto["nombre"]) ?></td>
        </tr>

        <tr>
            <th>Descripción</th>
            <td><?= htmlspecialchars($producto["descripcion"]) ?></td>
        </tr>

        <tr>
            <th>Precio</th>
            <td>$<?= htmlspecialchars($producto["precio"]) ?></td>
        </tr>

    </table>

    <a href="index.php?accion=productos" class="btn btn-secondary">
        Volver
    </a>

    <a href="index.php?accion=productos_editar&id=<?= $producto["id_producto"] ?>"
       class="btn btn-domocreativo">
        Editar
    </a>

</div>

</body>
</html>