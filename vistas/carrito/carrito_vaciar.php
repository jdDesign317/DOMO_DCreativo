<?php

// NOTA: session_start() lo hace index.php antes de incluir este archivo

require_once __DIR__ . "/../../config/Conexion.php";

$db = (new Conexion())->getConexion();

// VALIDAR SESIÓN
if (!isset($_SESSION["id_usuario"])) {

    header("Location: /DOMOCreativo/vistas/auth/login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];

// BUSCAR CARRITO ACTIVO
$sql = "SELECT * FROM carrito 
        WHERE id_usuario = $id_usuario 
        AND estado = 'activo'";

$resultado = $db->query($sql);
$carrito = $resultado->fetch_assoc();

if ($carrito) {

    $id_carrito = $carrito["id_carrito"];

    // BORRAR TODOS LOS ITEMS DEL CARRITO
    $sql = "DELETE FROM carrito_detalle 
            WHERE id_carrito = $id_carrito";

    $db->query($sql);
}

// REDIRECCIÓN
header("Location: /DOMOCreativo/index.php?accion=carrito");
exit;
