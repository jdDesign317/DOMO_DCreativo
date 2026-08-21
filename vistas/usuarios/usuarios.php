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
$usuarios = $usuariosControlador->listar();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Usuarios | Domo Creativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/estilos.css">
</head>

<body>

<div class="container mt-4">

    <h4 class="text-domocreativo mb-3">Listado de Usuarios</h4>

    <!-- BOTON VOLVER -->
    <a href="index.php" class="btn btn-secondary mb-3">Volver</a>

    <!-- BOTON CREAR -->
    <a href="index.php?accion=usuarios_crear" class="btn btn-domocreativo mb-3">Nuevo Usuario</a>

    <!-- TABLA -->
    <table class="table table-bordered table-hover">

        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Localidad</th>
                <th>Perfil</th>
                <th>Estado</th>
                <th>Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach ($usuarios as $usuario) : ?>

            <tr>
                <td><?= $usuario["id_usuario"] ?></td>
                <td><?= htmlspecialchars($usuario["nombre"]) ?></td>
                <td><?= htmlspecialchars($usuario["apellidos"]) ?></td>
                <td><?= htmlspecialchars($usuario["telefono"]) ?></td>
                <td><?= htmlspecialchars($usuario["email"]) ?></td>
                <td><?= htmlspecialchars($usuario["localidad"] ?? "Sin localidad") ?></td>
                <td><?= htmlspecialchars($usuario["perfil"] ?? "Sin perfil") ?></td>
                <td><?= htmlspecialchars($usuario["estado"]) ?></td>
                <td><?= htmlspecialchars($usuario["fecha_registro"]) ?></td>

                <td>

                    <!-- VER -->
                    <a href="index.php?accion=usuarios_ver&id=<?= $usuario["id_usuario"] ?>" 
                       class="btn btn-sm btn-info">
                        Ver
                    </a>

                    <!-- EDITAR -->
                    <a href="index.php?accion=usuarios_editar&id=<?= $usuario["id_usuario"] ?>" 
                       class="btn btn-sm btn-warning">
                        Editar
                    </a>

                    <!-- ELIMINAR -->
                    <form action="index.php" method="POST"
                          onsubmit="return confirm('¿Eliminar usuario?')"
                          style="display:inline;">
                        <input type="hidden" name="accion" value="usuarios_eliminar">
                        <input type="hidden" name="id" value="<?= $usuario["id_usuario"] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">
                            Eliminar
                        </button>
                    </form>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>

</body>
</html>