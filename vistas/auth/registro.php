<?php

session_start();

error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once __DIR__ . "/../../controladores/UsuariosControlador.php";

$usuariosControlador = new UsuariosControlador();

$mensaje = "";

/* SI YA HAY SESIÓN */
if (isset($_SESSION["id_usuario"])) {

    header("Location: ../../index.php");
    exit;
}

/* REGISTRO */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre    = trim($_POST["nombre"]);
    $apellidos = trim($_POST["apellidos"]);
    $telefono  = trim($_POST["telefono"]);
    $email     = trim($_POST["email"]);
    $password  = trim($_POST["password"]);

    $id_perfil = 1; // cliente

    if (
        empty($nombre) ||
        empty($email) ||
        empty($password)
    ) {

        $mensaje = "<div class='alert alert-warning mt-3 text-center'>
                        Completa los campos obligatorios.
                    </div>";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $mensaje = "<div class='alert alert-warning mt-3 text-center'>
                        Email inválido.
                    </div>";

    } elseif (strlen($password) < 6) {

        $mensaje = "<div class='alert alert-warning mt-3 text-center'>
                        La contraseña debe tener al menos 6 caracteres.
                    </div>";

    } else {

        $resultado = $usuariosControlador->crear(
            $nombre,
            $apellidos,
            $telefono,
            $email,
            $password,
            $id_perfil
        );
if ($resultado) {

    header("Location: login.php?registro=ok");
    exit;

} else {

    $mensaje = "<div class='alert alert-danger mt-3 text-center'>
                    Error al registrar o email ya existe.
                </div>";
}
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <title>Registro | DOMOCreativo</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- ESTILOS DOMO -->
    <link rel="stylesheet" href="../../assets/css/estilos.css">

</head>

<body class="d-flex justify-content-center align-items-center min-vh-100">

<div class="login-card">

    <h3 class="text-center fw-bold text-domocreativo mb-3">
        Registro
    </h3>

    <p class="text-center text-muted mb-4">
        Crear cuenta en DOMOCreativo
    </p>

    <?= $mensaje ?>

    <form method="POST">

        <!-- NOMBRE -->
        <input type="text" name="nombre" class="form-control mb-3" placeholder="Nombre">

        <!-- APELLIDOS -->
        <input type="text" name="apellidos" class="form-control mb-3" placeholder="Apellidos">

        <!-- TELEFONO -->
        <input type="text" name="telefono" class="form-control mb-3" placeholder="Teléfono">

        <!-- EMAIL -->
        <input type="email" name="email" class="form-control mb-3" placeholder="Email">

        <!-- PASSWORD CON MOSTRAR/OCULTAR -->
        <div class="input-group mb-3">

            <input 
                type="password" 
                name="password" 
                id="password"
                class="form-control" 
                placeholder="Contraseña"
                required
            >

            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                👁
            </button>

        </div>

        <!-- BOTON -->
        <button type="submit" class="btn btn-domocreativo w-100">
            Registrarme
        </button>

    </form>

    <div class="text-center mt-3">
        
       <a href="login.php" class="text-decoration-none text-domocreativo">
            ¿Ya tenés cuenta? Iniciar sesión
       </a>

    </div>

</div>

<!-- JS  MOSTRAR/OCULTAR PASSWORD -->
<script>
document.getElementById("togglePassword").addEventListener("click", function () {

    const input = document.getElementById("password");

    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }

});
</script>

</body>
</html>