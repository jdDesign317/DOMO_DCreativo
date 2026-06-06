<?php

require_once "../../controladores/UsuariosControlador.php";

$controlador = new UsuariosControlador();
$usuarios = $controlador->listar();

?>

<h2>Usuarios</h2>

<table border="1" cellpadding="8">

    <tr>
        <th>Nombre</th>
        <th>Email</th>
        <th>Perfil</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($usuarios as $usuario): ?>

    <tr>

        <td><?php echo strtolower($usuario["nombre"]); ?></td>

        <td><?php echo strtolower($usuario["email"]); ?></td>

        <td><?php echo strtolower($usuario["perfil"]); ?></td>

        <td>
            <?php echo strtolower($usuario["estado"]); ?>
        </td>

        <td>

            <?php if ($usuario["estado"] == "inactivo") { ?>

                <a href="../../controladores/UsuariosControlador.php?accion=reactivar&id=<?php echo $usuario["id_usuario"]; ?>">
                    reactivar
                </a>

            <?php } ?>

        </td>

    </tr>

    <?php endforeach; ?>

</table>