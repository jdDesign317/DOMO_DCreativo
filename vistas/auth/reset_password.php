<?php

session_start();

if (!isset($_SESSION["email_recuperacion"])) {

    header("Location: forgot.php");
    exit;
}

$mensaje = $_SESSION["mensaje_reset"] ?? "";

unset($_SESSION["mensaje_reset"]);

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Nueva contraseña | DOMOCreativo</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ICONOS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../../assets/css/estilos.css">

</head>

<body class="d-flex align-items-center justify-content-center"
      style="min-height:100vh; background: var(--domo-bg);">

<div class="login-card">

    <!-- TITULO -->
    <div class="text-center mb-4">

        <h2 class="fw-bold text-domocreativo">
            Nueva contraseña
        </h2>

        <p class="text-muted mb-0">
            Ingresá tu nueva contraseña
        </p>

    </div>

    <!-- MENSAJE -->
    <?= $mensaje ?>

    <!-- FORMULARIO -->
    <form method="POST"
          action="../../controladores/procesar_reset.php">

        <!-- PASSWORD -->
        <div class="mb-3">

            <label class="form-label fw-semibold">
                Nueva contraseña
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

        <!-- CONFIRMAR PASSWORD -->
        <div class="mb-3">

            <label class="form-label fw-semibold">
                Repetir contraseña
            </label>

            <div class="input-group">

                <input
                    type="password"
                    name="confirmar_password"
                    id="confirmar_password"
                    class="form-control"
                    required
                >

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    id="toggleConfirmar"
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
            <i class="bi bi-shield-lock me-1"></i>
            Actualizar contraseña
        </button>

    </form>

    <!-- VOLVER -->
    <div class="text-center mt-4">

        <a
            href="login.php"
            class="text-decoration-none fw-semibold text-domocreativo"
        >
            ← Volver al login
        </a>

    </div>

</div>

<!-- JS -->
<script>

const formulario = document.querySelector("form");

formulario.addEventListener("submit", function(e) {

    const password =
        document.getElementById("password").value;

    const confirmar =
        document.getElementById("confirmar_password").value;

    if (password !== confirmar) {

        e.preventDefault();

        alert("Las contraseñas no coinciden.");
    }
});

// MOSTRAR PASSWORD
document.getElementById("togglePassword")
.addEventListener("click", function() {

    const input = document.getElementById("password");

    const icon = this.querySelector("i");

    if (input.type === "password") {

        input.type = "text";

        icon.classList.replace(
            "bi-eye",
            "bi-eye-slash"
        );

    } else {

        input.type = "password";

        icon.classList.replace(
            "bi-eye-slash",
            "bi-eye"
        );
    }
});

// MOSTRAR CONFIRMACION
document.getElementById("toggleConfirmar")
.addEventListener("click", function() {

    const input =
        document.getElementById("confirmar_password");

    const icon = this.querySelector("i");

    if (input.type === "password") {

        input.type = "text";

        icon.classList.replace(
            "bi-eye",
            "bi-eye-slash"
        );

    } else {

        input.type = "password";

        icon.classList.replace(
            "bi-eye-slash",
            "bi-eye"
        );
    }
});

</script>

</body>
</html>