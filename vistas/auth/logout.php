<?php

session_start();

// ELIMINAR VARIABLES DE SESION
$_SESSION = [];

// DESTRUIR SESION
session_destroy();

// REDIRECCIONAR AL LOGIN
header("Location: /DOMOCreativo/vistas/auth/login.php");
exit;

?>