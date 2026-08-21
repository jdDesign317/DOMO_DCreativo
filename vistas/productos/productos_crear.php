<?php

// VALIDAR SESION
if (!isset($_SESSION["id_usuario"])) {

  header("Location: vistas/auth/login.php");
    exit;
}

require_once __DIR__ . "/../../controladores/ProductosControlador.php";

$ctrl = new ProductosControlador();

$mensaje = "";

// GUARDAR PRODUCTO
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $data = [
        "nombre" => trim($_POST["nombre"]),
        "descripcion" => trim($_POST["descripcion"]),
        "precio" => floatval($_POST["precio"])
    ];

    $resultado = $ctrl->crear($data);

    if ($resultado) {

        header("Location: index.php?accion=productos");
        exit;

    } else {

        $mensaje = "Error al crear producto";
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Producto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/DOMOCreativo/assets/css/estilos.css">
</head>

<body>

<div class="container mt-4">

    <h4 class="text-domocreativo mb-3">Nuevo Producto</h4>

    <?= $mensaje ?>

    <form method="POST">

        <!-- NOMBRE -->
        <input type="text"
               name="nombre"
               class="form-control mb-2"
               placeholder="Nombre del producto"
               required>

        <!-- DESCRIPCION -->
        <textarea name="descripcion"
                  class="form-control mb-2"
                  placeholder="Descripción"
                  required></textarea>

        <!-- PRECIO -->
        <input type="number"
               name="precio"
               step="0.01"
               class="form-control mb-3"
               placeholder="Precio"
               required>

        <button class="btn btn-domocreativo w-100">
            Guardar producto
        </button>

        <a href="index.php?accion=productos"
           class="btn btn-secondary w-100 mt-2">
            Volver
        </a>

    </form>

</div>

</body>
</html>