<?php

class Carrito {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // AGREGAR
    public function agregar($id_usuario, $id_producto, $cantidad) {

        $sql = "INSERT INTO carrito (id_usuario, id_producto, cantidad)
                VALUES (?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("iii", $id_usuario, $id_producto, $cantidad);

        return $stmt->execute();
    }

    // LISTAR POR USUARIO
    public function obtenerPorUsuario($id_usuario) {

        $sql = "SELECT c.id_carrito, c.cantidad,
                       p.nombre, p.precio
                FROM carrito c
                INNER JOIN productos p ON p.id_producto = c.id_producto
                WHERE c.id_usuario = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ELIMINAR
    public function eliminar($id_carrito) {

        $sql = "DELETE FROM carrito WHERE id_carrito = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_carrito);

        return $stmt->execute();
    }
}