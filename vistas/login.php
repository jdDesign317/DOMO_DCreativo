<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once "../controladores/UsuariosControlador.php";
$usuariosControlador = new UsuariosControlador();
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Verificar credenciales devuelve el usuario completo si son correctas
    $usuario = $usuariosControlador->verificarCredenciales($email, $password);

    if ($usuario) {
        // Guardamos el usuario en la sesión
        $_SESSION["usuario"] = [
            "id_usuario" => $usuario["id_usuario"],
            "nombre"     => $usuario["nombre"],
            "email"      => $usuario["email"],
            "perfil"     => $usuario["perfil"]
        ];
        header("Location: usuarios.php");
        exit;
    } else {
        $mensaje = "<div class='alert alert-danger mt-3 text-center'>Correo o contraseña incorrectos.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FUENTE POPPINS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- CSS PERSONAL -->
    <link rel="stylesheet" href="../assets/css/login.css">
    <!-- ICONOS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<div class="login-card">
    <h3 class="login-title">Domo Creativo</h3>
    <p class="text-center text-muted mb-4">Iniciá sesión para continuar</p>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label fw-semibold">Correo electrónico</label>
            <input type="email" name="email" class="form-control" placeholder="ejemplo@correo.com" required> 
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Contraseña</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="Tu contraseña" required> 
                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-danger w-100 mt-3">
            <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar
        </button>
    </form>

    <?= $mensaje ?>

    <p class="text-center text-small mt-4 text-muted">
        ¿No tenés cuenta?
        <a href="registro.php" class="text-danger fw-semibold">Suscribite aquí</a>
    </p>
    
    <p class="text-center mt-3">
        <a href="forgot.php">¿Olvidaste tu contraseña?</a>
    </p>

</div>

<script>
document.getElementById("togglePassword").addEventListener("click", function() {
    const input = document.getElementById("password");
    const icon = this.querySelector("i");
    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("bi-eye", "bi-eye-slash");
    } else {
        input.type = "password";
        icon.classList.replace("bi-eye-slash", "bi-eye");
    }
});
</script>
</body>
</html>
