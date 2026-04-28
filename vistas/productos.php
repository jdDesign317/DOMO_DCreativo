<?php
session_start();


/* CONTROL DE SESIÓN */
if (!isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit;
}

/* CONTROLADORES */
require_once "../controladores/ProductosControlador.php";
require_once "../controladores/CarritoControlador.php";


/* INSTANCIAS */
$productosController = new ProductosControlador();
$carritoController   = new CarritoControlador();

/* 
   AGREGAR PRODUCTO AL CARRITO
*/
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "agregar") {
    $id_producto = intval($_POST["id_producto"]);
    $cantidad    = intval($_POST["cantidad"]);

    $carritoController->agregar($id_producto, $cantidad);
}

/* 
   LISTAR PRODUCTOS
*/
$productos = $productosController->listar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Productos | Domo Creativo</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ÍCONOS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- ESTILOS -->
    <link rel="stylesheet" href="../assets/css/productos.css">
</head>

<body class="light-theme">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top navbar-light shadow-sm glass-navbar">
    <div class="container">
        <a class="navbar-brand text-danger fw-bold" href="../index.php">Domo Creativo</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="menu">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="usuarios.php">Usuarios</a></li>
            <li class="nav-item"><a class="nav-link" href="perfiles.php">Perfiles</a></li>
            <li class="nav-item"><a class="nav-link active fw-semibold" href="productos.php">Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="pedidos.php">Pedidos</a></li>
                <li class="nav-item"><a class="nav-link" href="presupuestos.php">Presupuestos</a></li>
                <li class="nav-item"><a class="nav-link" href="carrito.php">Carrito</a></li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="../index.php">
                        <i class="bi bi-box-arrow-right"></i> Salir
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- CONTENIDO -->
<div class="container page-container mt-5 pt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-danger fw-bold mb-0">Listado de Productos</h4>

        <a href="productos_crear.php" class="btn btn-outline-danger">
            <i class="bi bi-plus-circle me-1"></i> Producto Personalizado
        </a>
    </div>

    <div class="row">
        <?php foreach ($productos as $p): ?>
        <div class="col-md-4 mb-3">
            <div class="card soft-card shadow-sm h-100">
                <div class="card-body d-flex flex-column">

                    <h5 class="card-title"><?= htmlspecialchars($p['nombre']) ?></h5>

                    <p class="card-text">
                        <?= htmlspecialchars($p['descripcion']) ?>
                    </p>

                    <p class="fw-bold text-success mb-3">
                        $<?= number_format($p['precio'], 2) ?>
                    </p>

                    <!-- FORMULARIO AGREGAR -->
                    <form method="post" class="mt-auto">
                        <input type="hidden" name="accion" value="agregar">
                        <input type="hidden" name="id_producto" value="<?= $p['id_producto'] ?>">

                        <div class="input-group mb-2">
                            <input type="number" name="cantidad" min="1" value="1" class="form-control">

                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-cart-plus"></i> Agregar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<footer class="text-center mt-5 py-4 text-muted small">
    <?= date("Y") ?> Domo Creativo — Gestión de productos.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>