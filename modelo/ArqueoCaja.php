<?php
require_once __DIR__ . "/conexion.php";

class ArqueoCaja {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
    }

    /* 
       LISTAR ARQUEOS
     */
    public function listar() {
        $sql = "SELECT a.id_arqueo, a.fecha_apertura, a.fecha_cierre, a.monto_inicial, a.monto_final,
                       a.total_ingresos, a.total_egresos, a.observaciones, a.estado, u.nombre AS usuario_nombre
                FROM arqueo_caja a
                LEFT JOIN usuarios u ON a.id_usuario = u.id_usuario
                ORDER BY a.id_arqueo DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* 
       INSERTAR ARQUEO
     */
    public function insertar($id_usuario, $monto_inicial, $monto_final, $total_ingresos, $total_egresos, $observaciones, $estado, $fecha_apertura = null, $fecha_cierre = null) {
        $stmt = $this->db->prepare(
            "INSERT INTO arqueo_caja (id_usuario, fecha_apertura, fecha_cierre, monto_inicial, monto_final, total_ingresos, total_egresos, observaciones, estado)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("issddddss", $id_usuario, $fecha_apertura, $fecha_cierre, $monto_inicial, $monto_final, $total_ingresos, $total_egresos, $observaciones, $estado);
        return $stmt->execute();
    }

    /* 
       OBTENER ARQUEO POR ID
     */
    public function obtener($id) {
        $stmt = $this->db->prepare("SELECT * FROM arqueo_caja WHERE id_arqueo=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /* 
       ACTUALIZAR ARQUEO
   */
    public function actualizar($id, $id_usuario, $fecha_apertura, $fecha_cierre, $monto_inicial, $monto_final, $total_ingresos, $total_egresos, $observaciones, $estado) {
        $stmt = $this->db->prepare(
            "UPDATE arqueo_caja SET id_usuario=?, fecha_apertura=?, fecha_cierre=?, monto_inicial=?, monto_final=?, total_ingresos=?, total_egresos=?, observaciones=?, estado=? WHERE id_arqueo=?"
        );
        $stmt->bind_param("issddddssi", $id_usuario, $fecha_apertura, $fecha_cierre, $monto_inicial, $monto_final, $total_ingresos, $total_egresos, $observaciones, $estado, $id);
        return $stmt->execute();
    }

    /* 
       ELIMINAR ARQUEO
     */
    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM arqueo_caja WHERE id_arqueo=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
