<?php
error_reporting(E_ALL);
ini_set("display_errors", "On");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// VALIDAR SESION
if (!isset($_SESSION["id_usuario"])) {
    header("Location: vistas/auth/login.php");
    exit;
}

// CONTROL DE PERFIL (SOLO ADMIN)
if ($_SESSION["id_perfil"] != 2) {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . "/../../controladores/UsuariosControlador.php";
require_once __DIR__ . "/../../config/Conexion.php";

$usuariosControlador = new UsuariosControlador();
$mensaje = "";

$conexion = (new Conexion())->getConexion();

// PERFILES
$perfiles = $conexion->query("SELECT id_perfil, nombre FROM perfiles")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre    = strtolower(trim($_POST["nombre"]));
    $apellidos = strtolower(trim($_POST["apellidos"]));
    $telefono  = trim($_POST["telefono"]);
    $email     = strtolower(trim($_POST["email"]));
    $localidad = trim($_POST["localidad"]); // VARCHAR, no FK
    $password  = trim($_POST["password"]);
    $id_perfil = intval($_POST["id_perfil"]);

    $resultado = $usuariosControlador->crear(
        $nombre,
        $apellidos,
        $telefono,
        $email,
        $localidad,
        $password,
        $id_perfil
    );

    if ($resultado) {
        header("Location: index.php?accion=usuarios");
        exit;
    } else {
        $mensaje = "<div class='alert alert-danger text-center mt-3'>Error al crear usuario. Verificá los datos ingresados.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/estilos.css">
</head>

<body>
<div class="container mt-4">

    <h4 class="text-domocreativo mb-3">Crear Usuario</h4>

    <?= $mensaje ?>

    <form method="POST">

        <div class="mb-2">
            <label class="form-label">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
        </div>

        <div class="mb-2">
            <label class="form-label">Apellidos</label>
            <input type="text" name="apellidos" class="form-control" placeholder="Apellidos">
        </div>

        <div class="mb-2">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" placeholder="Teléfono">
        </div>

        <div class="mb-2">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>

        <!-- LOCALIDAD COMO TEXTO LIBRE -->
        <div class="mb-2">
            <label class="form-label">Localidad</label>
            <input type="text" name="localidad" class="form-control" placeholder="Localidad">
        </div>

        <div class="mb-2">
            <label class="form-label">Contraseña <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
        </div>

        <!-- PERFIL -->
        <div class="mb-3">
            <label class="form-label">Perfil <span class="text-danger">*</span></label>
            <select name="id_perfil" class="form-control" required>
                <option value="">Seleccionar perfil</option>
                <?php foreach ($perfiles as $perfil): ?>
                    <option value="<?= $perfil['id_perfil'] ?>">
                        <?= htmlspecialchars($perfil['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-domocreativo">Crear usuario</button>
        <a href="index.php?accion=usuarios" class="btn btn-secondary">Volver</a>

    </form>

</div>
</body>
</html>