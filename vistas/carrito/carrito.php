<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// VALIDAR SESION
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../auth/login.php");
    exit;
}

error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once __DIR__ . "/../../controladores/CarritoControlador.php";

$carritoControlador = new CarritoControlador();

// OBTENER CARRITO DEL USUARIO
$id_usuario = $_SESSION["id_usuario"];
$carrito = $carritoControlador->listar($id_usuario);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carrito | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/estilos.css">
</head>

<body>

<div class="container mt-4">

    <h4 class="text-domocreativo mb-3">Mi Carrito</h4>

    <!-- BOTON VOLVER -->
    <a href="index.php?accion=productos" class="btn btn-secondary mb-3">
        Volver
    </a>

    <!-- TABLA -->
    <table class="table table-bordered table-hover">

        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

        <?php
        $total = 0;
        foreach ($carrito as $item):
            $subtotal = $item["precio"] * $item["cantidad"];
            $total += $subtotal;
        ?>

            <tr>

                <td><?= htmlspecialchars($item["nombre"]) ?></td>

                <td>$<?= htmlspecialchars($item["precio"]) ?></td>

                <td><?= htmlspecialchars($item["cantidad"]) ?></td>

                <td>$<?= $subtotal ?></td>

                <td>

                    <!-- ELIMINAR -->
                    <a href="../../controladores/CarritoControlador.php?accion=eliminar&id=<?= $item["id_carrito"] ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Eliminar producto del carrito?')">
                        Eliminar
                    </a>

                </td>

            </tr>

        <?php endforeach; ?>

        <!-- TOTAL -->
        <tr>
            <td colspan="3"><b>Total</b></td>
            <td colspan="2"><b>$<?= $total ?></b></td>
        </tr>

        </tbody>

    </table>

</div>

</body>
</html>