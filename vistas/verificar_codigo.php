<?php
require_once "../modelo/conexion.php";

$conexionObj = new Conexion();
$conexion = $conexionObj->getConexion();

$mensaje = "";
$exito = false;

// Capturar email desde la URL
$email = $_GET["email"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $codigo = trim($_POST["codigo"]);

    $sql = "SELECT * FROM usuarios 
            WHERE email = ? 
            AND codigo_recuperacion = ? 
            AND codigo_expira > NOW()";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $email, $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        $exito = true;

        $mensaje = "<div class='alert alert-success text-center'>
                        Código correcto ✅ Redirigiendo...
                    </div>";

        // Redirección automática al reset de contraseña
        header("refresh:2;url=reset_password.php?email=" . urlencode($email));

    } else {

        $mensaje = "<div class='alert alert-danger text-center'>
                        Código incorrecto o vencido ❌
                    </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar código</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center" style="height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow-lg border-0 rounded-4 p-4">
                
                <h3 class="text-center mb-3">Verificar código</h3>

                <p class="text-center text-muted">
                    Ingresá el código que te enviamos 📩
                </p>

                <?= $mensaje ?>

                <form method="POST">

                    <!-- Email oculto -->
                    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Código</label>
                        <input type="text" name="codigo" class="form-control text-center" placeholder="Ej: 123456" required>
                    </div>

                    <button type="submit" class="btn btn-danger w-100">
                        Verificar
                    </button>

                </form>

                <p class="text-center mt-3">
                    <a href="forgot.php" class="text-decoration-none">
                        ← Volver
                    </a>
                </p>

            </div>

        </div>
    </div>
</div>

</body>
</html>