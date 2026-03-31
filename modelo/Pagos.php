<?php
require_once __DIR__ . "/conexion.php";

class Pago {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
    }

    /* 
       LISTAR PAGOS
     */
    public function listar() {
        $sql = "SELECT p.id_pago, p.id_pedido, p.monto, p.fecha_pago, m.nombre AS metodo_nombre
                FROM pagos p
                INNER JOIN metodos_pago m ON p.id_metodo_pago = m.id_metodo_pago
                ORDER BY p.id_pago DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* 
       INSERTAR PAGO
    */
    public function insertar($id_pedido, $id_metodo_pago, $monto, $fecha_pago = null) {
        if (empty($fecha_pago)) {
            // Si no se pasa fecha, se usa el CURRENT_TIMESTAMP por defecto
            $stmt = $this->db->prepare(
                "INSERT INTO pagos (id_pedido, id_metodo_pago, monto) VALUES (?, ?, ?)"
            );
            $stmt->bind_param("iid", $id_pedido, $id_metodo_pago, $monto);
        } else {
            // Si se pasa fecha, se guarda como string en formato DATETIME
            $stmt = $this->db->prepare(
                "INSERT INTO pagos (id_pedido, id_metodo_pago, monto, fecha_pago) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("iids", $id_pedido, $id_metodo_pago, $monto, $fecha_pago);
        }
        return $stmt->execute();
    }

    /* 
       OBTENER PAGO POR ID
     */
    public function obtener($id) {
        $stmt = $this->db->prepare("SELECT * FROM pagos WHERE id_pago=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /* 
       ACTUALIZAR PAGO
     */
    public function actualizar($id, $id_pedido, $id_metodo_pago, $monto, $fecha_pago) {
        $stmt = $this->db->prepare(
            "UPDATE pagos SET id_pedido=?, id_metodo_pago=?, monto=?, fecha_pago=? WHERE id_pago=?"
        );
        $stmt->bind_param("iidsi", $id_pedido, $id_metodo_pago, $monto, $fecha_pago, $id);
        return $stmt->execute();
    }

    /* 
       ELIMINAR PAGO
     */
    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM pagos WHERE id_pago=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
