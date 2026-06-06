<?php

// VALIDAR SESION
if (!isset($_SESSION["id_usuario"])) {

    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . "/../../controladores/ProductosControlador.php";

$controlador = new ProductosControlador();

$mensaje = "";

// VALIDAR ID
if (!isset($_GET["id"])) {

    echo "ID inválido";
    exit;
}

$id_producto = $_GET["id"];

// BUSCAR PRODUCTO
$producto = $controlador->buscarPorId($id_producto);

if (!$producto) {

    echo "Producto no encontrado";
    exit;
}

// ACTUALIZAR
if ($_POST) {

    $datos = [

        "id_producto" => $id_producto,
        "nombre" => $_POST["nombre"],
        "descripcion" => $_POST["descripcion"],
        "precio" => $_POST["precio"]

    ];

    $resultado = $controlador->actualizar($datos);

    if ($resultado) {

        header("Location: index.php?accion=productos");
        exit;

    } else {

        $mensaje = "Error al actualizar";
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <title>Editar producto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/DOMOCreativo/assets/css/estilos.css">

</head>

<body>

<div class="container mt-4">

    <h4>Editar producto</h4>

    <?= $mensaje ?>

    <form method="POST">

        <!-- NOMBRE -->
        <div class="mb-2">
            <label>Nombre</label>
            <input type="text" name="nombre"
                   value="<?= $producto["nombre"] ?>"
                   class="form-control" required>
        </div>

        <!-- DESCRIPCION -->
        <div class="mb-2">
            <label>Descripción</label>
            <input type="text" name="descripcion"
                   value="<?= $producto["descripcion"] ?>"
                   class="form-control" required>
        </div>

        <!-- PRECIO -->
        <div class="mb-3">
            <label>Precio</label>
            <input type="number" name="precio"
                   value="<?= $producto["precio"] ?>"
                   class="form-control" required>
        </div>

        <button class="btn btn-domocreativo w-100">
            Guardar
        </button>

      

        <a href="index.php?accion=productos" class="btn btn-secondary w-100 mt-2">
            Volver
        </a>

    </form>

</div>

</body>
</html>