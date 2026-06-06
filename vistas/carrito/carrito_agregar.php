<?php
session_start();

// VALIDAR SESIÓN
if (!isset($_SESSION["id_usuario"])) {

    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . "/../../config/Conexion.php";

$db = (new Conexion())->getConexion();

// VALIDAR ID PRODUCTO
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    header("Location: index.php?accion=productos");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];
$id_producto = (int) $_GET["id"];

// BUSCAR CARRITO ACTIVO
$sql = "SELECT id_carrito 
        FROM carrito 
        WHERE id_usuario = $id_usuario 
        AND estado = 'activo' 
        LIMIT 1";

$resultado = $db->query($sql);
$carrito = $resultado->fetch_assoc();

// SI NO EXISTE → CREAR
if (!$carrito) {

    $db->query("INSERT INTO carrito (id_usuario) VALUES ($id_usuario)");
    $id_carrito = $db->insert_id;

} else {

    $id_carrito = $carrito["id_carrito"];
}

// BUSCAR PRODUCTO EN CARRITO
$sql = "SELECT id_detalle, cantidad 
        FROM carrito_detalle 
        WHERE id_carrito = $id_carrito 
        AND id_producto = $id_producto
        LIMIT 1";

$resultado = $db->query($sql);
$item = $resultado->fetch_assoc();

// PRECIO DEL PRODUCTO
$precio = $db->query("
    SELECT precio 
    FROM productos 
    WHERE id_producto = $id_producto
")->fetch_assoc();

$precio_unitario = $precio["precio"];

// SI EXISTE → SUMAR
if ($item) {

    $id_detalle = $item["id_detalle"];

    $db->query("
        UPDATE carrito_detalle 
        SET cantidad = cantidad + 1 
        WHERE id_detalle = $id_detalle
    ");

} else {

    // SI NO EXISTE → INSERTAR
    $db->query("
        INSERT INTO carrito_detalle 
        (id_carrito, id_producto, cantidad, precio_unitario)
        VALUES 
        ($id_carrito, $id_producto, 1, $precio_unitario)
    ");
}

// REDIRECCIÓN AL CARRITO
header("Location: index.php?accion=carrito");
exit;