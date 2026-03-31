<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fuente -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Estilos -->
    <link rel="stylesheet" href="../assets/css/login.css">

    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<?php
error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once "../controladores/UsuariosControlador.php";
$usuariosControlador = new UsuariosControlador();

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre   = trim($_POST["nombre"]);
    $email    = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Perfil por defecto (usuario normal)
    $id_perfil = 2;

    // Encriptar contraseña antes de guardar
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $resultado = $usuariosControlador->crear($nombre, $email, $password_hash, $id_perfil);

    if ($resultado) {
        echo "<script>alert('Registro exitoso. Ahora podés iniciar sesión.'); window.location.href='login.php';</script>";
        exit;
    } else {
        $mensaje = "<div class='alert alert-danger mt-3 text-center'>Error al registrar usuario. Intentalo nuevamente.</div>";
    }
}
?>

<div class="login-card">
    <h3 class="login-title">Crear Cuenta</h3>
    <p class="text-center text-muted mb-4">Suscribite para acceder a Domo Creativo</p>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label fw-semibold">Nombre completo</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Correo electrónico</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3 position-relative">
            <label class="form-label fw-semibold">Contraseña</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" required>
                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-danger w-100 mt-3">
            <i class="bi bi-person-plus me-1"></i> Registrarme
        </button>
    </form>

    <?= $mensaje ?>

    <p class="text-center text-small mt-4 text-muted">
        ¿Ya tenés cuenta?
        <a href="login.php" class="text-center text-fucsia fw-bold mb-3">Iniciá sesión</a>
    </p>                    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
