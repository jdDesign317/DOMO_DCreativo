<?php

session_start();

// ELIMINAR VARIABLES DE SESION
$_SESSION = [];

// DESTRUIR SESION
session_destroy();

// REDIRECCIONAR AL LOGIN

header("Location: login.php");
exit;

?>