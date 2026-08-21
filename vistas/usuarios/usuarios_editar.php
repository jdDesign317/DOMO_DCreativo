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

// PERFILES DESDE LA BASE DE DATOS
$perfiles = $conexion->query("SELECT id_perfil, nombre FROM perfiles")->fetch_all(MYSQLI_ASSOC);

// VALIDAR ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    echo "ID inválido";
    exit;
}

$id_usuario = intval($_GET["id"]);
$usuario = $usuariosControlador->buscarPorId($id_usuario);

if (!$usuario) {
    echo "Usuario no encontrado";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre    = strtolower(trim($_POST["nombre"]));
    $apellidos = strtolower(trim($_POST["apellidos"]));
    $telefono  = trim($_POST["telefono"]);
    $email     = strtolower(trim($_POST["email"]));
    $localidad = trim($_POST["localidad"]); // VARCHAR, no FK
    $password  = trim($_POST["password"]);
    $id_perfil = intval($_POST["id_perfil"]);

    $resultado = $usuariosControlador->actualizar(
        $id_usuario,
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
        $mensaje = "<div class='alert alert-danger text-center mt-3'>Error al actualizar usuario.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/estilos.css">
</head>

<body>
<div class="container mt-4">

    <h4 class="text-domocreativo mb-3">Editar Usuario</h4>

    <?= $mensaje ?>

    <form method="POST">

        <div class="mb-2">
            <label class="form-label">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="nombre"
                   value="<?= htmlspecialchars($usuario["nombre"]) ?>"
                   class="form-control" required>
        </div>

        <div class="mb-2">
            <label class="form-label">Apellidos</label>
            <input type="text" name="apellidos"
                   value="<?= htmlspecialchars($usuario["apellidos"]) ?>"
                   class="form-control">
        </div>

        <div class="mb-2">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono"
                   value="<?= htmlspecialchars($usuario["telefono"]) ?>"
                   class="form-control">
        </div>

        <div class="mb-2">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email"
                   value="<?= htmlspecialchars($usuario["email"]) ?>"
                   class="form-control" required>
        </div>

        <!-- LOCALIDAD COMO TEXTO LIBRE -->
        <div class="mb-2">
            <label class="form-label">Localidad</label>
            <input type="text" name="localidad"
                   value="<?= htmlspecialchars($usuario["localidad"] ?? "") ?>"
                   class="form-control" placeholder="Localidad">
        </div>

        <div class="mb-2">
            <label class="form-label">Nueva contraseña <small class="text-muted">(dejá vacío para no cambiar)</small></label>
            <input type="password" name="password"
                   class="form-control"
                   placeholder="Mínimo 6 caracteres">
        </div>

        <!-- PERFIL DESDE LA BASE DE DATOS -->
        <div class="mb-3">
            <label class="form-label">Perfil <span class="text-danger">*</span></label>
            <select name="id_perfil" class="form-control" required>
                <option value="">Seleccionar perfil</option>
                <?php foreach ($perfiles as $perfil): ?>
                    <option value="<?= $perfil['id_perfil'] ?>"
                        <?= $usuario["id_perfil"] == $perfil["id_perfil"] ? "selected" : "" ?>>
                        <?= htmlspecialchars($perfil["nombre"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-domocreativo w-100">Guardar cambios</button>
        <a href="index.php?accion=usuarios" class="btn btn-secondary w-100 mt-2">Volver</a>

    </form>

</div>
</body>
</html>