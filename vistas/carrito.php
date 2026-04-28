<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once "../controladores/CarritoControlador.php";
$carritoControlador = new CarritoControlador();

/* PROCESAR ACCIONES */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["accion"])) {
    if ($_POST["accion"] == "cerrar") {
        $carritoControlador->cerrar($_POST["id_carrito"]);
    }
}

/* LISTAR CARRITOS */
$listaCarritos = $carritoControlador->listarTodos();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carritos | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/carrito.css">
</head>

<body class="light-theme">

<!-- 🔝 NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top navbar-light shadow-sm glass-navbar">
    <div class="container">
        <a class="navbar-brand text-danger fw-bold" href="../index.php">Domo Creativo</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="menu">
            <ul class="navbar-nav">

                <li class="nav-item"><a class="nav-link" href="productos.php">Productos</a></li>

                <li class="nav-item">
                    <a class="nav-link active fw-semibold" href="carrito.php">Carrito</a>
                </li>

                <li class="nav-item"><a class="nav-link" href="pedidos.php">Pedidos</a></li>

            </ul>
        </div>
    </div>
</nav>


<div class="container page-container mt-5 pt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-danger fw-bold mb-0">Gestion de Carrito</h4>

        <a href="productos.php" class="btn btn-danger">
            <i class="bi bi-plus-circle me-1"></i> ver productos
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">

            <thead style="background-color:#f2f2f2;">
                <tr>
                    <th>ID</th>
                    <th>ID Usuario</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($listaCarritos)): ?>
                    <?php foreach ($listaCarritos as $carrito): ?>
                        <tr>
                            <td><?= $carrito["id_carrito"] ?></td>
                            <td><?= $carrito["id_usuario"] ?></td>
                            <td>
                                <span class="badge bg-secondary">
                                    <?= ucfirst($carrito["estado"]) ?>
                                </span>
                            </td>
                            <td><?= $carrito["fecha"] ?></td>

                            <td class="text-center">
                                <!-- VER DETALLE -->
                                <a href="carrito_detalle.php?id_carrito=<?= $carrito["id_carrito"] ?>" 
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-list-ul"></i>
                                </a>

                                <!-- CERRAR -->
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="accion" value="cerrar">
                                    <input type="hidden" name="id_carrito" value="<?= $carrito["id_carrito"] ?>">

                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('¿Cerrar carrito?');">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">

                            <p>No hay carritos registrados.</p>

                            <a href="productos.php" class="btn btn-danger btn-sm">
                                <i class="bi bi-bag"></i> Empezar a comprar
                            </a>

                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>

</div>

</body>
</html>