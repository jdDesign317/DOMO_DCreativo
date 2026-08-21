<?php

// NOTA: session_start() lo hace index.php antes de incluir este archivo

require_once __DIR__ . "/../../config/Conexion.php";

$db = (new Conexion())->getConexion();

// VALIDAR SESIÓN
if (!isset($_SESSION["id_usuario"])) {

    header("Location: /DOMOCreativo/vistas/auth/login.php");
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
header("Location: /DOMOCreativo/index.php?accion=carrito");
exit;
