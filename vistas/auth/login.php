<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once __DIR__ . "/../../controladores/UsuariosControlador.php";

$usuariosControlador = new UsuariosControlador();

$mensaje = "";

// SI YA HAY SESIÓN
if (isset($_SESSION["id_usuario"])) {

    header("Location: ../../index.php");
    exit;
}

// LOGIN
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    // VALIDACIONES
    if (empty($email) || empty($password)) {

        $mensaje = "
            <div class='alert alert-warning text-center mt-3'>
                Todos los campos son obligatorios.
            </div>
        ";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $mensaje = "
            <div class='alert alert-warning text-center mt-3'>
                Ingresá un correo electrónico válido.
            </div>
        ";

    } else {

        $usuario = $usuariosControlador->verificarCredenciales($email, $password);

        if ($usuario) {

            $_SESSION["usuario"] = [

                "id_usuario" => $usuario["id_usuario"],
                "nombre"     => $usuario["nombre"],
                "email"      => $usuario["email"],
                "id_perfil"  => $usuario["id_perfil"]
            

            ];

           $_SESSION["id_usuario"] = $usuario["id_usuario"];
           $_SESSION["id_perfil"]  = $usuario["id_perfil"];

        
            session_regenerate_id(true);

            header("Location: ../../index.php");
            exit;

        } else {

            $mensaje = "
                <div class='alert alert-danger text-center mt-3'>
                    Credenciales incorrectas.
                </div>
            ";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Login | DOMOCreativo</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ICONOS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../../assets/css/estilos.css">

</head>

<body class="d-flex justify-content-center align-items-center min-vh-100">

<div class="login-card">

    <!-- LOGO -->
    <div class="text-center mb-4">

        <h2 class="fw-bold text-domocreativo">
            DOMOCreativo
        </h2>

        <p class="text-muted mb-0">
            Iniciar sesión
        </p>

    </div>

    <!-- MENSAJE -->
    <?= $mensaje ?>

    <!-- FORMULARIO -->
    <form method="POST">

        <!-- EMAIL -->
        <div class="mb-3">

            <label class="form-label fw-semibold">
                Correo electrónico
            </label>

            <input
                type="email"
                name="email"
                class="form-control"
                placeholder="ejemplo@correo.com"
                required
            >

        </div>

        <!-- PASSWORD -->
        <div class="mb-3">

            <label class="form-label fw-semibold">
                Contraseña
            </label>

            <div class="input-group">

                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control"
                    required
                >

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    id="togglePassword"
                >
                    <i class="bi bi-eye"></i>
                </button>

            </div>

        </div>

        <!-- BOTON -->
        <button
            type="submit"
            class="btn btn-domocreativo w-100 mt-3"
        >
            <i class="bi bi-box-arrow-in-right me-1"></i>
            Ingresar
        </button>

    </form>

    <!-- RECUPERAR -->
    <div class="text-center mt-4">

        <a href="forgot.php"
           class="text-decoration-none fw-semibold text-domocreativo">
            ¿Olvidaste tu contraseña?
        </a>

    </div>

    <!-- REGISTRO -->
    <div class="text-center mt-3">

        <span class="text-muted">
            ¿No tenés cuenta?
        </span>

        <a href="registro.php"
           class="text-decoration-none fw-bold text-domocreativo">
            Registrarse
        </a>

    </div>

</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- MOSTRAR / OCULTAR PASSWORD -->
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