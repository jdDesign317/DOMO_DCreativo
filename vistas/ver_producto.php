<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

require_once "../controladores/ProductosControlador.php";
$controller = new ProductosControlador();

// Validar ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("ID no válido");
}

$id = intval($_GET["id"]);
$producto = $controller->obtener($id);

if (!$producto) {
    die("Producto no encontrado.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Producto | Domo Creativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h3 class="text-danger"><i class="bi bi-box-seam"></i> Detalle del Producto</h3>

<ul class="list-group mb-3">
    <li class="list-group-item"><strong>ID:</strong> <?= $producto["id_producto"] ?></li>
    <li class="list-group-item"><strong>Nombre:</strong> <?= htmlspecialchars($producto["nombre"]) ?></li>
    <li class="list-group-item"><strong>Descripción:</strong> <?= htmlspecialchars($producto["descripcion"]) ?></li>
    <li class="list-group-item"><strong>Precio:</strong> $<?= number_format($producto["precio"], 2) ?></li>
</ul>

<a href="productos.php" class="btn btn-secondary">Volver</a>

</body>
</html>
