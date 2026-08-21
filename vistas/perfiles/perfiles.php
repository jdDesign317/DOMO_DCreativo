<?php

// VALIDAR SESION
if (!isset($_SESSION["id_usuario"])) {
    header("Location: vistas/auth/login.php");
    exit;
}

// SOLO ADMINISTRADOR
if ($_SESSION["id_perfil"] != 2) {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . "/../../controladores/PerfilesControlador.php";

$perfilesControlador = new PerfilesControlador();

$perfiles = $perfilesControlador->listar();
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Perfiles | DOMOCreativo</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../assets/css/estilos.css">

</head>

<body>

<div class="container mt-4">

    <h3 class="text-domocreativo mb-4">
        Listado de Perfiles
    </h3>

    <a href="../../index.php" class="btn btn-secondary mb-3">
        Volver
    </a>

    <table class="table table-bordered table-hover">

        <thead>

            <tr>
                <th>ID</th>
                <th>Perfil</th>
                <th>Código</th>
                <th>Acciones</th>
            </tr>

        </thead>

        <tbody>

        <?php foreach ($perfiles as $perfil) : ?>

            <tr>

                <td><?= $perfil["id_perfil"] ?></td>

                <td><?= htmlspecialchars($perfil["nombre"]) ?></td>

                <td><?= htmlspecialchars($perfil["codigo"]) ?></td>

                <td>

                    <a
                        href="perfiles_editar.php?id=<?= $perfil["id_perfil"] ?>"
                        class="btn btn-warning btn-sm"
                    >
                        Editar
                    </a>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>

</body>
</html>