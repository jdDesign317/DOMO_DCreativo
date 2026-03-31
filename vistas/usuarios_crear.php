<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Usuario | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fuente -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Estilos propios -->
    <link rel="stylesheet" href="../assets/css/usuarios.css">
</head>
<body>

<?php
error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once "../controladores/UsuariosControlador.php";
$usuariosControlador = new UsuariosControlador();

$mensaje = "";

// --- CREAR NUEVO USUARIO ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "crear") {
    $nombre   = trim($_POST["nombre"]);
    $email    = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $id_perfil = intval($_POST["id_perfil"]);

    // Encriptar contraseña antes de guardar
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $resultado = $usuariosControlador->crear($nombre, $email, $password_hash, $id_perfil);

    if ($resultado) {
        echo "<script>alert('Usuario creado correctamente ✅'); window.location.href='usuarios.php';</script>";
        exit;
    } else {
        $mensaje = "<div class='alert alert-danger text-center mt-3'>❌ Error al crear el usuario. Verificá los datos.</div>";
    }
}
?>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand text-danger fw-bold" href="../index.php">Domo Creativo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="menu">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="productos.php">Productos</a></li>
                <li class="nav-item"><a class="nav-link active fw-semibold" href="usuarios.php">Usuarios</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- CONTENIDO -->
<div class="container page-container">
    <div class="card shadow-sm soft-card">
        <div class="card-header bg-white border-0 border-bottom text-danger fw-semibold">
            <i class="bi bi-person-plus-fill me-2"></i> Crear Nuevo Usuario
        </div>

        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="accion" value="crear">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Correo electrónico</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Contraseña</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">ID de Perfil</label>
                    <input type="number" name="id_perfil" min="1" class="form-control" required>
                </div>

                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-save me-1"></i> Crear Usuario
                    </button>
                    <a href="usuarios.php" class="btn btn-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> Volver
                    </a>
                </div>
            </form>

            <?= $mensaje ?>
        </div>
    </div>
</div>

<footer class="text-center text-muted small py-4">
    © <?= date("Y") ?> Domo Creativo — Alta de usuarios.
</footer>

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
