<?php

// VALIDAR SESION
if (!isset($_SESSION["id_usuario"])) {

    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . "/../../controladores/ProductosControlador.php";

$controller = new ProductosControlador();

// VALIDAR ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    die("ID no válido");
}

$id = intval($_GET["id"]);

// ELIMINAR
$resultado = $controller->eliminar($id);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Eliminar producto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5 text-center">

    <?php if ($resultado) { ?>

        <div class="alert alert-success">
            Producto eliminado correctamente
        </div>

    <?php } else { ?>

        <div class="alert alert-danger">
            Error al eliminar producto
        </div>

    <?php } ?>

    <a href="index.php?accion=productos" class="btn btn-primary mt-3">
        Volver
    </a>

</body>
</html>