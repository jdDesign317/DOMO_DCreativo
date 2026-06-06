<?php

session_start();

require_once __DIR__ . "/../../config/Conexion.php";

$db = (new Conexion())->getConexion();

// VALIDAR SESIÓN
if (!isset($_SESSION["id_usuario"])) {

    header("Location: ../auth/login.php");
    exit;
}

// VALIDAR ID DETALLE
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    echo "ID inválido";
    exit;
}

$id_detalle = intval($_GET["id"]);

// ELIMINAR ITEM DEL CARRITO
$sql = "DELETE FROM carrito_detalle 
        WHERE id_detalle = $id_detalle";

$db->query($sql);

// REDIRECCIÓN
header("Location: index.php?accion=carrito_ver");
exit;

?>