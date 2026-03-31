<?php
require_once "../modelo/conexion.php";

$conexionObj = new Conexion();
$conexion = $conexionObj->getConexion();

$mensaje = "";

// Email desde GET
$email = $_GET["email"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if (!empty($email) && !empty($password)) {

        // Hash
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE usuarios 
                SET password_hash = ?, 
                    codigo_recuperacion = NULL,
                    codigo_expira = NULL
                WHERE email = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ss", $password_hash, $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {

            $mensaje = "<div class='alert alert-success text-center'>
                            Contraseña cambiada con éxito ✅
                        </div>";

            // Redirección opcional al login
            header("refresh:2;url=login.php");

        } else {
            $mensaje = "<div class='alert alert-warning text-center'>
                            No se actualizó ningún usuario ⚠️ Verificá el email
                        </div>";
        }

    } else {
        $mensaje = "<div class='alert alert-danger text-center'>
                        Completa todos los campos
                    </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center" style="height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow-lg border-0 rounded-4 p-4">
                
                <h3 class="text-center mb-3">Nueva contraseña</h3>

                <p class="text-center text-muted mb-4">
                    Ingresá tu nueva contraseña 🔐
                </p>

                <?= $mensaje ?>

                <form method="POST">

                    <!-- Email oculto -->
                    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nueva contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-danger w-100">
                        Actualizar
                    </button>

                </form>

                <p class="text-center mt-3">
                    <a href="login.php" class="text-decoration-none">← Volver al login</a>
                </p>

            </div>

        </div>
    </div>
</div>

</body>
</html>