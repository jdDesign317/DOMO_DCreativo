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

$mensaje = "";

// VALIDAR ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    echo "ID inválido";
    exit;
}

$id_usuario = intval($_GET["id"]);

// BUSCAR POR ID (correcto)
$usuario = $usuariosControlador->buscarPorId($id_usuario);

if (!$usuario) {

    echo "Usuario no encontrado";
    exit;
}

// ACTUALIZAR
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = strtolower(trim($_POST["nombre"]));
    $apellidos = strtolower(trim($_POST["apellidos"]));
    $telefono = trim($_POST["telefono"]);
    $email = strtolower(trim($_POST["email"]));
    $password = trim($_POST["password"]);
    $id_perfil = intval($_POST["id_perfil"]);

    // PASSWORD
    if ($password == "") {

        $password_hash = $usuario["password_hash"];

    } else {

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
    }

    $resultado = $usuariosControlador->actualizar(
        $id_usuario,
        $nombre,
        $apellidos,
        $telefono,
        $email,
        $password_hash,
        $id_perfil
    );

    if ($resultado) {

       header("Location: index.php?accion=usuarios");
        exit;

    } else {

        $mensaje = "Error al actualizar usuario";
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/estilos.css">
</head>

<body>

<div class="container mt-4">

    <h4 class="text-domocreativo mb-3">Editar Usuario</h4>

    <?= $mensaje ?>

    <form method="POST">

        <!-- NOMBRE -->
        <input type="text" name="nombre"
               value="<?= htmlspecialchars($usuario["nombre"]) ?>"
               class="form-control mb-2" required>

        <!-- APELLIDOS -->
        <input type="text" name="apellidos"
               value="<?= htmlspecialchars($usuario["apellidos"]) ?>"
               class="form-control mb-2" required>

        <!-- TELEFONO -->
        <input type="text" name="telefono"
               value="<?= htmlspecialchars($usuario["telefono"]) ?>"
               class="form-control mb-2">

        <!-- EMAIL -->
        <input type="email" name="email"
               value="<?= htmlspecialchars($usuario["email"]) ?>"
               class="form-control mb-2" required>

        <!-- PASSWORD -->
        <input type="password" name="password"
               class="form-control mb-2"
               placeholder="Nueva contraseña (opcional)">

        <!-- PERFIL (simple para examen) -->
        <select name="id_perfil" class="form-control mb-3" required>

    <option value="1" <?= $usuario["id_perfil"] == 1 ? "selected" : "" ?>>
        Cliente
    </option>

    <option value="2" <?= $usuario["id_perfil"] == 2 ? "selected" : "" ?>>
        Administrador
    </option>

    <option value="3" <?= $usuario["id_perfil"] == 3 ? "selected" : "" ?>>
        Diseñador gráfico
    </option>

</select>
        <button class="btn btn-domocreativo w-100">
            Guardar cambios
        </button>

        <a href="index.php?accion=usuarios" class="btn btn-secondary w-100 mt-2">
            Volver
        </a>

    </form>

</div>

</body>
</html>