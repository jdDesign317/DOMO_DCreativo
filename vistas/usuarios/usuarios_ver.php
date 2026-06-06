<?php

// VALIDAR SESION
if (!isset($_SESSION["id_usuario"])) {

    header("Location: ../auth/login.php");
    exit;
}


error_reporting(E_ALL);
ini_set("display_errors", "On");

require_once __DIR__ . "/../../controladores/UsuariosControlador.php";

$usuariosControlador = new UsuariosControlador();

// ID
$id_usuario = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($id_usuario <= 0) {

    $usuario = null;

} else {

    $usuario = $usuariosControlador->buscarPorId($id_usuario);
}



?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Ver Usuario</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/estilos.css">

</head>

<body>

<div class="container mt-4">

    <h4 class="text-domocreativo mb-3">Detalle Usuario</h4>

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
                <th>Email</th>
                <td><?= htmlspecialchars($usuario["email"]) ?></td>
            </tr>

            <tr>
                <th>Perfil</th>
                <td><?= htmlspecialchars($usuario["perfil"] ?? "") ?></td>
            </tr>

        </table>

        <a href="index.php?accion=usuarios" class="btn btn-secondary">
            Volver
        </a>

        
        
        <a href="index.php?accion=usuarios_editar&id=<?= $usuario["id_usuario"] ?>"
        class="btn btn-domocreativo">
            Editar
        </a>

    <?php else : ?>

        <div class="alert alert-danger">
            Usuario no encontrado
        </div>

        <a href="usuarios.php" class="btn btn-secondary">Volver</a>

    <?php endif; ?>

</div>

</body>
</html>