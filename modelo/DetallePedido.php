<?php
require_once "Conexion.php";

class DetallePedido {

    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function obtenerDetallePorPedido($id_pedido) {

        $sql = "SELECT 
                    detalle_pedido.id_detalle,
                    detalle_pedido.id_producto,
                    productos.nombre AS nombre_producto,
                    detalle_pedido.cantidad,
                    detalle_pedido.precio_unitario
                FROM detalle_pedido
                INNER JOIN productos 
                    ON detalle_pedido.id_producto = productos.id_producto
                WHERE detalle_pedido.id_pedido = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_pedido);
        $stmt->execute();

        $resultado = $stmt->get_result();

        $detalles = [];
        while ($fila = $resultado->fetch_assoc()) {
            $detalles[] = $fila;
        }

        $stmt->close();

        return $detalles;
    }
}