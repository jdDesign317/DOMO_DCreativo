<?php
session_start();

$mensaje = $_SESSION["mensaje"] ?? "";
unset($_SESSION["mensaje"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/estilos.css">
</head>

<body style="background: var(--domo-bg);">

<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">

    <div class="card p-4 shadow" style="width: 400px;">

        <h4 class="text-center text-domocreativo">Recuperar contraseña</h4>

        <p class="text-center text-muted">Ingresá tu correo</p>

        <?php if ($mensaje): ?>
            <div class="alert alert-info text-center">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="../../controladores/procesar_forgot.php">

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <button class="btn btn-domocreativo w-100">
                Enviar código
            </button>

        </form>

        <div class="text-center mt-3">
            <a href="login.php">Volver al login</a>
        </div>

    </div>

</div>

</body>
</html>