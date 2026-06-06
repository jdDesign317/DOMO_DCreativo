<?php

require_once __DIR__ . "/../modelo/Conexion.php";
require_once __DIR__ . "/../modelo/Carrito.php";

class CarritoControlador {

    private $carrito;

    public function __construct() {
        $conexion = (new Conexion())->getConexion();
        $this->carrito = new Carrito($conexion);
    }

    // LISTAR CARRITO
    public function listar($id_usuario) {
        return $this->carrito->obtenerPorUsuario($id_usuario);
    }

    // AGREGAR PRODUCTO
    public function agregar($id_usuario, $id_producto, $cantidad) {
        return $this->carrito->agregar($id_usuario, $id_producto, $cantidad);
    }

    // ELIMINAR PRODUCTO
    public function eliminar($id_carrito) {
        return $this->carrito->eliminar($id_carrito);
    }
}

// ACCIONES SIMPLES (COMO TU ESTILO USUARIOS)

$controlador = new CarritoControlador();

$accion = $_GET["accion"] ?? "";

if ($accion == "agregar") {

    session_start();

    $controlador->agregar(
        $_SESSION["id_usuario"],
        $_GET["id"],
        1
    );

    header("Location: ../vistas/carrito/carrito.php");
    exit;
}

if ($accion == "eliminar") {

    $controlador->eliminar($_GET["id"]);

    header("Location: ../vistas/carrito/carrito.php");
    exit;
}