<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once "../controladores/PagosControlador.php";
require_once "../modelo/MetodoPago.php";

$pagosController = new PagosControlador();
$pagos = $pagosController->listar();
$metodos_pago = (new MetodoPago())->listar();

$error = null;

/* 
   AGREGAR PAGO
 */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "insertar") {
    $id_pedido      = intval($_POST["id_pedido"]);
    $id_metodo_pago = intval($_POST["id_metodo_pago"]);
    $monto_input    = str_replace(',', '.', trim($_POST["monto"]));
    $monto          = floatval($monto_input);
    $fecha_pago     = !empty($_POST["fecha_pago"])
        ? date("Y-m-d H:i:s", strtotime($_POST["fecha_pago"]))
        : null;

    $ok = $pagosController->insertar($id_pedido, $id_metodo_pago, $monto, $fecha_pago);

    if ($ok) {
        header("Location: pagos.php");
        exit;
    } else {
        $error = "No se pudo guardar el pago. Verificá los datos ingresados.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagos | Domo Creativo</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/pagos.css">
</head>
<body class="light-theme">

<nav class="navbar navbar-expand-lg fixed-top navbar-light shadow-sm glass-navbar">
    <div class="container">
        <a class="navbar-brand text-danger fw-bold" href="../index.php">Domo Creativo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="menu">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link active fw-semibold" href="pagos.php">Pagos</a></li>
                <li class="nav-item"><a class="nav-link" href="productos.php">Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="usuarios.php">Usuarios</a></li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="../logout.php">
                        <i class="bi bi-box-arrow-right"></i> Salir
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5 pt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-danger fw-bold mb-0">Gestión de Pagos</h4>
        <a href="pagos.php" class="btn btn-outline-danger">
            <i class="bi bi-cash-coin me-1"></i> Nuevo Pago
        </a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="accion" value="insertar">

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Pedido</label>
                    <input type="number" name="id_pedido" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Método de Pago</label>
                    <select name="id_metodo_pago" class="form-select" required>
                        <option value="">Seleccionar...</option>
                        <?php foreach ($metodos_pago as $m): ?>
                            <option value="<?= $m['id_metodo_pago'] ?>"><?= htmlspecialchars($m['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Monto</label>
                    <input type="text" name="monto" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Fecha</label>
                    <input type="datetime-local" name="fecha_pago" class="form-control">
                </div>

                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-save me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Pedido</th>
                        <th>Método</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pagos as $p): ?>
                    <tr>
                        <td><?= $p['id_pago'] ?></td>
                        <td><?= $p['id_pedido'] ?></td>
                        <td><?= htmlspecialchars($p['metodo_nombre']) ?></td>
                        <td>$<?= number_format($p['monto'], 2) ?></td>
                        <td><?= $p['fecha_pago'] ?></td>
                        <td>
                            <a href="pagos_editar.php?id=<?= $p['id_pago'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="../index.php?controlador=pagos&accion=eliminar&id=<?= $p['id_pago'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar pago?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer class="text-center mt-5 py-4 text-muted small">
    © <?= date("Y") ?> Domo Creativo — Gestión de pagos.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
