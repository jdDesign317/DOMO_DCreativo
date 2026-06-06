<?php

session_start();

require_once __DIR__ . "/../../config/Conexion.php";

$conexionObj = new Conexion();
$conexion = $conexionObj->getConexion();

$mensaje = $_SESSION["mensaje_codigo"] ?? "";
unset($_SESSION["mensaje_codigo"]);

// EMAIL
$email = $_GET["email"] ?? "";

// VALIDAR QUE EXISTA EMAIL
if (empty($email)) {

    header("Location: forgot.php");
    exit;
}

// BUSCAR USUARIO (PARA MOSTRAR INFO BÁSICA)
$sql = "SELECT email FROM usuarios WHERE email = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Usuario no encontrado";
    exit;
}

$usuario = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Verificar código</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/estilos.css">
</head>

<body class="d-flex align-items-center justify-content-center" style="min-height:100vh; background: var(--domo-bg);">

<div class="login-card">

    <h3 class="text-center text-domocreativo">Verificar código</h3>

    <p class="text-center text-muted">
        Email: <?= htmlspecialchars($usuario["email"]) ?>
    </p>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-danger text-center">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="../../controladores/procesar_verificacion.php">

        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

        <div class="mb-3">
            <label>Código recibido</label>
            <input type="text" name="codigo" class="form-control text-center" required>
        </div>

        <button class="btn btn-domocreativo w-100">
            Verificar código
        </button>

    </form>

    <div class="text-center mt-3">
        <a href="forgot.php">← Volver</a>
    </div>

</div>

</body>
</html>