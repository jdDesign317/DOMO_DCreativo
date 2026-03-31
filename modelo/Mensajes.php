<?php
require_once __DIR__ . "/conexion.php";

class Mensaje {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
    }

    /* 
       LISTAR MENSAJES
     */
    public function listar() {
        $sql = "SELECT m.id_mensaje, m.id_usuario, u.nombre AS usuario_nombre, m.remitente, m.mensaje, m.fecha
                FROM mensajes m
                INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
                ORDER BY m.id_mensaje DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* 
       INSERTAR MENSAJE
     */
    public function insertar($id_usuario, $remitente, $mensaje, $fecha = null) {
        if ($fecha === null) {
            $stmt = $this->db->prepare(
                "INSERT INTO mensajes (id_usuario, remitente, mensaje) VALUES (?, ?, ?)"
            );
            $stmt->bind_param("iss", $id_usuario, $remitente, $mensaje);
        } else {
            $stmt = $this->db->prepare(
                "INSERT INTO mensajes (id_usuario, remitente, mensaje, fecha) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("isss", $id_usuario, $remitente, $mensaje, $fecha);
        }
        return $stmt->execute();
    }

    /* 
       OBTENER MENSAJE POR ID
     */
    public function obtener($id) {
        $stmt = $this->db->prepare("SELECT * FROM mensajes WHERE id_mensaje=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /* 
       ACTUALIZAR MENSAJE
     */
    public function actualizar($id, $id_usuario, $remitente, $mensaje, $fecha) {
        $stmt = $this->db->prepare(
            "UPDATE mensajes SET id_usuario=?, remitente=?, mensaje=?, fecha=? WHERE id_mensaje=?"
        );
        $stmt->bind_param("isssi", $id_usuario, $remitente, $mensaje, $fecha, $id);
        return $stmt->execute();
    }

    /* 
       ELIMINAR MENSAJE
    = */
    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM mensajes WHERE id_mensaje=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
