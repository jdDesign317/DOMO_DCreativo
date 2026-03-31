<?php
session_start();

require_once "../controladores/PresupuestosControlador.php";
require_once "../controladores/DetallePresupuestosControlador.php";
require_once "../controladores/ProductosControlador.php";

$presupuestoController = new PresupuestosControlador();
$detalleController = new DetallePresupuestosControlador();
$productosController = new ProductosControlador();

// Validar parámetro
if (!isset($_GET["id_presupuesto"]) || !is_numeric($_GET["id_presupuesto"])) {
    header("Location: presupuestos.php");
    exit;
}

$id_presupuesto = intval($_GET["id_presupuesto"]);
$presupuesto = $presupuestoController->buscar($id_presupuesto);

if (!$presupuesto) {
    echo "<div class='alert alert-warning text-center mt-5'>⚠ Presupuesto #{$id_presupuesto} no encontrado.</div>";
    exit;
}

$detalles = $detalleController->listarPorPresupuesto($id_presupuesto);
$productos = $productosController->listar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Detalle Presupuesto #<?= htmlspecialchars($id_presupuesto) ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- Bootstrap + Iconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilos propios -->
    <link rel="stylesheet" href="../assets/css/detalle_presupuestos.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand text-danger fw-bold" href="../index.php">Domo Creativo</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="presupuestos.php">Presupuestos</a></li>
                <li class="nav-item"><a class="nav-link" href="productos.php">Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="usuarios.php">Usuarios</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5 pt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="text-danger">Detalle Presupuesto #<?= htmlspecialchars($id_presupuesto) ?></h3>
            <small class="text-muted">
                Usuario: <?= htmlspecialchars($presupuesto["id_usuario"] ?? "—") ?> • 
                Fecha: <?= htmlspecialchars($presupuesto["fecha_creacion"] ?? "") ?>
            </small>
        </div>
        <div class="text-end">
            <a href="presupuestos.php" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <button id="btnGuardar" class="btn btn-danger me-1">
                <i class="bi bi-save"></i> Guardar
            </button>
            <button id="btnPDF" class="btn btn-outline-dark me-1">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </button>
            <button id="btnWord" class="btn btn-outline-primary me-1">
                <i class="bi bi-file-earmark-word"></i> Word
            </button>
            <button id="btnTicket" class="btn btn-outline-info">
                <i class="bi bi-receipt"></i> Ticket
            </button>
        </div>
    </div>

    <!-- Resumen -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-3">
                    <strong>Total estimado:</strong>
                    <div id="totalEstimadoTexto" class="fs-5 text-danger">
                        $<?= number_format($presupuesto["total_estimado"] ?? 0, 2) ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <strong>Estado:</strong> <?= ucfirst($presupuesto["estado"] ?? "pendiente") ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla editable -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaDetalles" class="table table-bordered table-corporate">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio unitario ($)</th>
                            <th>Subtotal ($)</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detalles as $d): ?>
                        <tr data-id="<?= $d['id_detalle'] ?>">
                            <td>
                                <select class="form-select product-select">
                                    <?php foreach ($productos as $p): ?>
                                        <option value="<?= $p['id_producto'] ?>" <?= ($p['id_producto'] == $d['id_producto'] ? 'selected' : '') ?>>
                                            <?= htmlspecialchars($p['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="number" class="form-control cant small-input" min="1" value="<?= $d['cantidad'] ?>"></td>
                            <td><input type="number" class="form-control precio" min="0" step="0.01" value="<?= $d['precio_unitario'] ?>"></td>
                            <td class="subtotal">$<?= number_format($d['cantidad'] * $d['precio_unitario'], 2) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger btn-eliminar"><i class="bi bi-trash"></i></button>
                                <button class="btn btn-sm btn-outline-secondary btn-clonar"><i class="bi bi-files"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-3 d-flex justify-content-between">
                <button id="btnAgregarFila" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-plus"></i> Agregar fila
                </button>
                <div><strong>Total general: $<span id="totalGeneral">0.00</span></strong></div>
            </div>
        </div>
    </div>
</div>

<!-- Logo oculto para exportación -->
<img id="logoHidden" class="logo-hidden" src="../assets/img/logo.png" alt="logo">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Variables globales para JS -->
<script>
    window.ID_PRESUPUESTO = <?= json_encode($id_presupuesto) ?>;
    window.PRESUPUESTO_USUARIO = <?= json_encode($presupuesto["id_usuario"] ?? "") ?>;
    window.PRESUPUESTO_FECHA = <?= json_encode($presupuesto["fecha_creacion"] ?? "") ?>;
</script>

<!-- Scripts separados -->
<script src="../assets/js/detalle_presupuestos.js"></script>
<script src="../assets/js/detalle_presupuestos_guardar.js"></script>

<!-- Librería jsPDF necesaria para exportar PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script src="../assets/js/detalle_presupuestos_exportar.js"></script>
</body>
</html>