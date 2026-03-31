<?php
require_once __DIR__ . "/conexion.php";

class Factura {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
    }

    public function listar() {
        $sql = "SELECT f.id_factura, f.id_pedido, f.fecha_emision, f.monto_total, f.archivo_factura, f.estado
                FROM facturas f
                ORDER BY f.id_factura DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insertar($id_pedido, $monto_total, $archivo_factura, $estado = 'generada', $fecha_emision = null) {
        if ($fecha_emision === null) {
            $stmt = $this->db->prepare(
                "INSERT INTO facturas (id_pedido, monto_total, archivo_factura, estado) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("idss", $id_pedido, $monto_total, $archivo_factura, $estado);
        } else {
            $stmt = $this->db->prepare(
                "INSERT INTO facturas (id_pedido, fecha_emision, monto_total, archivo_factura, estado) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("isdss", $id_pedido, $fecha_emision, $monto_total, $archivo_factura, $estado);
        }
        return $stmt->execute();
    }

    public function obtener($id) {
        $stmt = $this->db->prepare("SELECT * FROM facturas WHERE id_factura=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function actualizar($id, $id_pedido, $fecha_emision, $monto_total, $archivo_factura, $estado) {
        $stmt = $this->db->prepare(
            "UPDATE facturas SET id_pedido=?, fecha_emision=?, monto_total=?, archivo_factura=?, estado=? WHERE id_factura=?"
        );
        $stmt->bind_param("isdssi", $id_pedido, $fecha_emision, $monto_total, $archivo_factura, $estado, $id);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM facturas WHERE id_factura=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
