<?php
session_start();

// VALIDAR SESION
if (!isset($_SESSION["id_usuario"])) {
   header("Location: vistas/auth/login.php");
    exit;
}

// SOLO ADMIN
if ($_SESSION["id_perfil"] != 2) {
    header("Location: ../../index.php");
    exit;
}

require_once __DIR__ . "/../../controladores/PerfilesControlador.php";

$perfilesControlador = new PerfilesControlador();

$mensaje = "";

// VALIDAR ID
if (!isset($_GET["id"])) {
    header("Location: index.php?accion=perfiles");
    exit;
}

$id_perfil = (int) $_GET["id"];

$perfil = $perfilesControlador->obtener($id_perfil);

if (!$perfil) {
    header("Location: index.php?accion=perfiles");
    exit;
}

// ACTUALIZAR
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"]);

    if ($perfilesControlador->actualizar($id_perfil, $nombre)) {

        $mensaje = "
            <div class='alert alert-success'>
                Perfil actualizado correctamente.
            </div>
        ";

        $perfil = $perfilesControlador->obtener($id_perfil);

    } else {

        $mensaje = "
            <div class='alert alert-danger'>
                Error al actualizar el perfil.
            </div>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Editar Perfil | DOMOCreativo</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../assets/css/estilos.css">

</head>

<body>

<div class="container mt-4">

    <h3 class="text-domocreativo mb-4">
        Editar Perfil
    </h3>

    <?= $mensaje ?>

    <div class="card">

        <div class="card-body">

            <form method="POST">

                <div class="mb-3">

                    <label class="form-label">
                        Nombre
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        class="form-control"
                        value="<?= htmlspecialchars($perfil["nombre"]) ?>"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="btn btn-domocreativo"
                >
                    Guardar
                </button>

                <a
                    href="perfiles.php"
                    class="btn btn-secondary"
                >
                    Volver
                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>