<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once "../controladores/CarritoControlador.php";
require_once "../controladores/ProductosControlador.php";

$carritoController   = new CarritoControlador();
$productosController = new ProductosControlador();

/*  PROCESAR ELIMINAR PRODUCTO */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["accion"])) {
    if ($_POST["accion"] == "quitar") {
        $carritoController->quitar($_POST["id_detalle"], $_POST["id_carrito"]);
    }
}

if (!isset($_GET["id_carrito"]) || !is_numeric($_GET["id_carrito"])) {
    header("Location: carrito.php");
    exit;
}

$id_carrito = intval($_GET["id_carrito"]);
$carrito    = $carritoController->obtener($id_carrito);

if (!$carrito) {
    echo "<div class='alert alert-warning text-center mt-5'>⚠️ Carrito #{$id_carrito} no encontrado.</div>";
    exit;
}

$detalles = $carritoController->getModelo()->listarDetalles($id_carrito);
$productos = $productosController->listar();
$totalGeneral = array_sum(array_map(fn($d) => $d["cantidad"] * $d["precio"], $detalles));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Carrito Detalle #<?= htmlspecialchars($id_carrito) ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/carrito_detalle.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="text-danger">Carrito Detalle #<?= htmlspecialchars($id_carrito) ?></h3>
            <small class="text-muted">
                Usuario: <?= htmlspecialchars($carrito["id_usuario"] ?? "—") ?> • 
                Fecha: <?= htmlspecialchars($carrito["fecha"] ?? "") ?>
            </small>
        </div>
        <div class="text-end">
            <a href="carrito.php" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Resumen -->
    <div class="card mb-3">
        <div class="card-body">
            <strong>Total general:</strong>
            <div class="fs-5 text-danger">
                $<?= number_format($totalGeneral, 2) ?>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detalles as $d): ?>
                        <tr>
                            <td><?= htmlspecialchars($d['nombre']) ?></td>
                            <td><?= $d['cantidad'] ?></td>
                            <td>$<?= number_format($d['precio'], 2) ?></td>
                            <td>$<?= number_format($d['cantidad'] * $d['precio'], 2) ?></td>
                            <td>

                                <!--  ELIMINAR CORREGIDO -->
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="accion" value="quitar">
                                    <input type="hidden" name="id_detalle" value="<?= $d['id_detalle'] ?>">
                                    <input type="hidden" name="id_carrito" value="<?= $id_carrito ?>">

                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-between">
                <a href="productos.php" class="btn btn-sm btn-outline-primary">
                    Ver productos
                </a>

                <strong>Total: $<?= number_format($totalGeneral, 2) ?></strong>
            </div>

        </div>
    </div>
</div>

</body>
</html>