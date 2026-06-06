<?php


// VALIDAR SESIÓN
if (!isset($_SESSION["id_usuario"])) {

    header("Location: ../auth/login.php");
    exit;
}

error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once __DIR__ . "/../../controladores/UsuariosControlador.php";
require_once __DIR__ . "/../../config/Conexion.php";

$usuariosControlador = new UsuariosControlador();
$mensaje = "";

// CONEXIÓN PARA PERFILES
$conexion = (new Conexion())->getConexion();

$sql = "SELECT id_perfil, nombre FROM perfiles";
$resultado = $conexion->query($sql);

$perfiles = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];

// CREAR USUARIO
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = strtolower(trim($_POST["nombre"]));
    $apellidos = strtolower(trim($_POST["apellidos"]));
    $telefono = trim($_POST["telefono"]);
    $email = strtolower(trim($_POST["email"]));
    $password = trim($_POST["password"]);
    $id_perfil = intval($_POST["id_perfil"]);

    $resultado = $usuariosControlador->crear(
        $nombre,
        $apellidos,
        $telefono,
        $email,
        $password,
        $id_perfil
    );

    //  CORREGIR V/F (no array)
    if ($resultado) {

        header("Location: usuarios.php");
        exit;

    } else {

        $mensaje = "<div class='alert alert-danger text-center mt-3'>
            Error al crear usuario
        </div>";
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../assets/css/estilos.css">

</head>

<body>

<div class="container mt-4">

    <h4 class="text-domocreativo mb-3">Crear Usuario</h4>

    <?= $mensaje ?>

    <form method="POST">

        <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre" required>

        <input type="text" name="apellidos" class="form-control mb-2" placeholder="Apellidos">

        <input type="text" name="telefono" class="form-control mb-2" placeholder="Teléfono">

        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>

        <input type="password" name="password" class="form-control mb-2" placeholder="Contraseña" required>

        <select name="id_perfil" class="form-control mb-3" required>

            <option value="">Seleccionar perfil</option>

            <?php foreach ($perfiles as $perfil) : ?>

                <option value="<?= $perfil["id_perfil"] ?>">
                    <?= $perfil["nombre"] ?>
                </option>

            <?php endforeach; ?>

        </select>

        <button type="submit" class="btn btn-domocreativo">
            Crear usuario
        </button>

        <a href="index.php?accion=usuarios" class="btn btn-secondary">
            Volver
        </a>

    </form>

</div>

</body>
</html>