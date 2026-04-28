<?php

require_once __DIR__ . "/../modelo/Carrito.php";

class CarritoControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new Carrito();
    }

    // Obtener carrito por ID
    public function obtener($id_carrito) {
        return $this->modelo->buscarPorId((int)$id_carrito);
    }

    // Listar todos los carritos
    public function listarTodos(): array {
        return $this->modelo->listarTodos();
    }

    // Agregar producto al carrito
    public function agregar($id_producto, $cantidad = 1) {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["usuario"]["id_usuario"])) {
            header("Location: /plataforma_domocreativo1/vistas/login.php");
            exit;
        }

        $id_usuario = (int)$_SESSION["usuario"]["id_usuario"];

        $id_carrito = $this->modelo->obtenerCarritoActivo($id_usuario);

        if (!$id_carrito) {
            $id_carrito = $this->modelo->crearCarrito($id_usuario);
        }

        if (!$id_carrito) {
            die("Error: no se pudo obtener o crear el carrito");
        }

        $this->modelo->agregarProducto($id_carrito, (int)$id_producto, (int)$cantidad);

        header("Location: /plataforma_domocreativo1/vistas/carrito_detalle.php?id_carrito=" . $id_carrito);
        exit;
    }

    // Actualizar cantidades
    public function actualizar($id_carrito) {

        if (isset($_POST["cantidad"])) {
            foreach ($_POST["cantidad"] as $id_detalle => $cantidad) {
                $this->modelo->actualizarCantidad((int)$id_detalle, (int)$cantidad);
            }
        }

        header("Location: /plataforma_domocreativo1/vistas/carrito_detalle.php?id_carrito=" . (int)$id_carrito);
        exit;
    }

    // Quitar producto
    public function quitar($id_detalle, $id_carrito) {

        $this->modelo->quitar((int)$id_detalle);

        header("Location: /plataforma_domocreativo1/vistas/carrito_detalle.php?id_carrito=" . (int)$id_carrito);
        exit;
    }

    // Vaciar carrito
    public function vaciar($id_carrito) {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["usuario"]["id_usuario"])) {
            header("Location: /plataforma_domocreativo1/vistas/login.php");
            exit;
        }

        $this->modelo->vaciar((int)$id_carrito);

        header("Location: /plataforma_domocreativo1/vistas/carrito_detalle.php?id_carrito=" . (int)$id_carrito);
        exit;
    }

    //  Cerrar carrito 
    public function cerrar($id_carrito) {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["usuario"]["id_usuario"])) {
            return false;
        }

        return $this->modelo->cerrar((int)$id_carrito);
    }

    public function getModelo() {
        return $this->modelo;
    }
}