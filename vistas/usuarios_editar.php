<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once "../controladores/UsuariosControlador.php";
$usuariosControlador = new UsuariosControlador();

// --- VALIDAR ID ---
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    echo "<div class='container mt-5'><div class='alert alert-danger text-center'>ID de usuario no válido.</div></div>";
    exit;
}

$id_usuario = intval($_GET["id"]);
$usuario = $usuariosControlador->ver($id_usuario); // usamos ver() que llama a buscarPorId()

if (!$usuario) {
    echo "<div class='container mt-5'><div class='alert alert-danger text-center'>Usuario no encontrado.</div></div>";
    exit;
}

// --- ACTUALIZAR USUARIO ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "editar") {
    $nombre   = trim($_POST["nombre"]);
    $email    = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $id_perfil = intval($_POST["id_perfil"]);

    // Si el campo contraseña está vacío, mantenemos la actual
    if ($password === "") {
        $password_hash = $usuario["password_hash"]; 
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
    }

    $resultado = $usuariosControlador->actualizar($id_usuario, $nombre, $email, $password_hash, $id_perfil);

    if ($resultado) {
        echo "<script>alert('Usuario actualizado correctamente ✅'); window.location.href='usuarios.php';</script>";
        exit;
    } else {
        echo "<div class='alert alert-danger text-center mt-3'>❌ Error al actualizar el usuario.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fuente Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Estilos globales -->
    <link rel="stylesheet" href="../assets/css/global.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="light-theme">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top navbar-light shadow-sm glass-navbar">
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
    <div class="card soft-card shadow-sm mt-5">
        <div class="card-header bg-white border-0 border-bottom text-danger fw-semibold">
            <i class="bi bi-pencil-square me-2"></i> Editar Usuario
        </div>

        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="accion" value="editar">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nombre</label>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($usuario["nombre"]) ?>" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($usuario["email"]) ?>" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nueva Contraseña (opcional)</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Dejar en blanco para mantener la actual">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword" title="Mostrar/Ocultar contraseña">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">ID de Perfil</label>
                    <input type="number" name="id_perfil" min="1" value="<?= htmlspecialchars($usuario["id_perfil"]) ?>" class="form-control" required>
                </div>

                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-save me-1"></i> Guardar Cambios
                    </button>
                    <a href="usuarios.php" class="btn btn-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> Volver
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<footer class="text-center mt-5 py-4 text-muted small">
    © <?= date("Y") ?> Domo Creativo — Edición de usuario.
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Mostrar/Ocultar contraseña -->
<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
});
</script>

</body>
</html>
