<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once "../controladores/ArqueoCajaControlador.php";

$arqueoController = new ArqueoCajaControlador();
$arqueos = $arqueoController->listar();
$error = null;

/* =
   AGREGAR ARQUEO DE CAJA
 */
if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["accion"]) &&
    $_POST["accion"] === "insertar"
) {
    $id_usuario     = intval($_POST["id_usuario"]);
    $monto_inicial  = floatval(str_replace(',', '.', $_POST["monto_inicial"]));
    $monto_final    = floatval(str_replace(',', '.', $_POST["monto_final"]));
    $total_ingresos = floatval(str_replace(',', '.', $_POST["total_ingresos"]));
    $total_egresos  = floatval(str_replace(',', '.', $_POST["total_egresos"]));
    $observaciones  = trim($_POST["observaciones"]);
    $estado         = $_POST["estado"];

    $fecha_apertura = !empty($_POST["fecha_apertura"])
        ? date("Y-m-d H:i:s", strtotime($_POST["fecha_apertura"]))
        : null;

    $fecha_cierre = !empty($_POST["fecha_cierre"])
        ? date("Y-m-d H:i:s", strtotime($_POST["fecha_cierre"]))
        : null;

    $ok = $arqueoController->insertar(
        $id_usuario,
        $monto_inicial,
        $monto_final,
        $total_ingresos,
        $total_egresos,
        $observaciones,
        $estado,
        $fecha_apertura,
        $fecha_cierre
    );

    if ($ok) {
        header("Location: arqueo_caja.php");
        exit;
    } else {
        $error = "No se pudo guardar el arqueo de caja. Verificá los datos ingresados.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Arqueo de Caja | Domo Creativo</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/arqueo.css">
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
                <li class="nav-item"><a class="nav-link active fw-semibold" href="arqueo_caja.php">Arqueo Caja</a></li>
                <li class="nav-item"><a class="nav-link" href="pagos.php">Pagos</a></li>
                <li class="nav-item"><a class="nav-link" href="facturas.php">Facturas</a></li>
                <li class="nav-item"><a class="nav-link" href="mensajes.php">Mensajes</a></li>
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

<!-- CONTENIDO -->
<div class="container mt-5 pt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-danger fw-bold mb-0">Gestión de Arqueo de Caja</h4>
        <a href="arqueo_caja.php" class="btn btn-outline-danger">
            <i class="bi bi-cash-stack me-1"></i> Nuevo Arqueo
        </a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <!-- FORMULARIO DE CREACIÓN -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="accion" value="insertar">

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Usuario</label>
                    <input type="number" name="id_usuario" class="form-control" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Monto Inicial</label>
                    <input type="text" name="monto_inicial" class="form-control" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Monto Final</label>
                    <input type="text" name="monto_final" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Ingresos</label>
                    <input type="text" name="total_ingresos" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Egresos</label>
                    <input type="text" name="total_egresos" class="form-control">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2"></textarea>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="abierta">Abierta</option>
                        <option value="cerrada">Cerrada</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Fecha Apertura</label>
                    <input type="datetime-local" name="fecha_apertura" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Fecha Cierre</label>
                    <input type="datetime-local" name="fecha_cierre" class="form-control">
                </div>

                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-save me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLA DE ARQUEOS -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Fecha Apertura</th>
                        <th>Fecha Cierre</th>
                        <th>Monto Inicial</th>
                        <th>Monto Final</th>
                        <th>Ingresos</th>
                        <th>Egresos</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($arqueos as $a): ?>
                    <tr>
                        <td><?= $a['id_arqueo'] ?></td>
                        <td><?= htmlspecialchars($a['usuario_nombre']) ?></td>
                        <td><?= $a['fecha_apertura'] ?></td>
                        <td><?= $a['fecha_cierre'] ?></td>
                        <td>$<?= number_format($a['monto_inicial'], 2) ?></td>
                        <td>$<?= number_format($a['monto_final'], 2) ?></td>
                        <td>$<?= number_format($a['total_ingresos'], 2) ?></td>
                        <td>$<?= number_format($a['total_egresos'], 2) ?></td>
                        <td><?= $a['estado'] ?></td>
                        <td><?= htmlspecialchars($a['observaciones']) ?></td>
                        <td>
                            <a href="arqueo_caja_editar.php?id=<?= $a['id_arqueo'] ?>"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <a href="../index.php?controlador=arqueo_caja&accion=eliminar&id=<?= $a['id_arqueo'] ?>"
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('¿Eliminar arqueo de caja?')">
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

<!-- FOOTER -->
<footer class="text-center mt-5 py-4 text-muted small">
    © <?= date("Y") ?> Domo Creativo — Gestión de arqueo de caja.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
