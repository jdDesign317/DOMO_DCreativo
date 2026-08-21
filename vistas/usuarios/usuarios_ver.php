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

$usuariosControlador = new UsuariosControlador();

// VALIDAR ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    echo "ID inválido";
    exit;
}

$id_usuario = intval($_GET["id"]);
$usuario = $usuariosControlador->buscarPorId($id_usuario);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ver Usuario | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/estilos.css">
</head>

<body>

<div class="container mt-4">

    <h4 class="text-domocreativo mb-3">Detalle de Usuario</h4>

    <?php if ($usuario) : ?>

        <table class="table table-bordered">

            <tr>
                <th>ID</th>
                <td><?= $usuario["id_usuario"] ?></td>
            </tr>

            <tr>
                <th>Nombre</th>
                <td><?= htmlspecialchars($usuario["nombre"]) ?></td>
            </tr>

            <tr>
                <th>Apellidos</th>
                <td><?= htmlspecialchars($usuario["apellidos"]) ?></td>
            </tr>

            <tr>
                <th>Teléfono</th>
                <td><?= htmlspecialchars($usuario["telefono"] ?? "-") ?></td>
            </tr>

            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($usuario["email"]) ?></td>
            </tr>

            <tr>
                <th>Localidad</th>
                <td><?= htmlspecialchars($usuario["localidad"] ?? "-") ?></td>
            </tr>

            <tr>
                <th>Perfil</th>
                <td><?= htmlspecialchars($usuario["perfil"] ?? "-") ?></td>
            </tr>

            <tr>
                <th>Estado</th>
                <td><?= htmlspecialchars($usuario["estado"]) ?></td>
            </tr>

            <tr>
                <th>Fecha de registro</th>
                <td><?= htmlspecialchars($usuario["fecha_registro"]) ?></td>
            </tr>

        </table>

        <a href="index.php?accion=usuarios" class="btn btn-secondary">Volver</a>

        <a href="index.php?accion=usuarios_editar&id=<?= $usuario["id_usuario"] ?>"
           class="btn btn-domocreativo">
            Editar
        </a>

    <?php else : ?>

        <div class="alert alert-danger">
            Usuario no encontrado.
        </div>

        <a href="index.php?accion=usuarios" class="btn btn-secondary">Volver</a>

    <?php endif; ?>

</div>

</body>
</html>