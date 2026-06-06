<?php



require_once __DIR__ . "/../../config/Conexion.php";

$db = (new Conexion())->getConexion();

// VALIDAR SESIÓN
if (!isset($_SESSION["id_usuario"])) {

    header("Location: ../auth/login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];

// BUSCAR CARRITO ACTIVO
$sql = "SELECT * FROM carrito 
        WHERE id_usuario = $id_usuario 
        AND estado = 'activo'";

$resultado = $db->query($sql);
$carrito = $resultado->fetch_assoc();

$items = [];
$total = 0;

// SI EXISTE CARRITO, TRAER PRODUCTOS
if ($carrito) {

    $id_carrito = $carrito["id_carrito"];

    $sql = "SELECT cd.*, p.nombre 
            FROM carrito_detalle cd
            INNER JOIN productos p 
            ON cd.id_producto = p.id_producto
            WHERE cd.id_carrito = $id_carrito";

    $items = $db->query($sql);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <title>Carrito</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/estilos.css">

</head>

<body>

<div class="container mt-4">

    <h3 class="text-domocreativo">Carrito de compras</h3>

    <table class="table table-bordered mt-3">

        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Subtotal</th>
            <th>Acción</th>
        </tr>

        <?php if ($items && $items->num_rows > 0): ?>

            <?php while ($i = $items->fetch_assoc()): ?>

                <?php
                    $subtotal = $i["cantidad"] * $i["precio_unitario"];
                    $total += $subtotal;
                ?>

                <tr>

                    <td><?= $i["nombre"] ?></td>

                    <td><?= $i["cantidad"] ?></td>

                    <td>$<?= $i["precio_unitario"] ?></td>

                    <td>$<?= $subtotal ?></td>

                    <td>
                        <a href="index.php?accion=carrito_quitar&id=<?= $i["id_detalle"] ?>"
                           class="btn btn-danger btn-sm">
                            quitar
                        </a>
                        
                    </td>

                </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>
                <td colspan="5" class="text-center">
                    Carrito vacío
                </td>
            </tr>

        <?php endif; ?>

    </table>

    <h4>Total: $<?= $total ?></h4>

   
    <div class="mb-3">

        <a href="index.php" class="btn btn-secondary">
            Volver
        </a>

        <a href="index.php?accion=productos" class="btn btn-warning">
            Seguir comprando
        </a>

        <a href="index.php?accion=carrito_vaciar" class="btn btn-danger">
            Vaciar carrito
        </a>

    </div>
        

</div>

</body>
</html>


