<?php

session_start();

// VALIDAR SESION
if (!isset($_SESSION["id_usuario"])) {

header("Location: vistas/auth/login.php");
    exit;
}

require_once __DIR__ . "/../../controladores/UsuariosControlador.php";

$usuariosControlador = new UsuariosControlador();

// VALIDAR ID
$id_usuario = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($id_usuario > 0) {

    // BAJA LOGICA
    $usuariosControlador->eliminar($id_usuario);
}

// REDIRECCION
header("Location: index.php?accion=usuarios");
exit;