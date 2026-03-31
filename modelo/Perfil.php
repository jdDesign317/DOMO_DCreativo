<?php
require_once "Conexion.php";

class Perfil {
    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

    public function listar() {
        $sql = "SELECT * FROM perfiles ORDER BY id_perfil ASC";
        $result = $this->conexion->query($sql);

        $perfiles = [];
        if ($result && $result->num_rows > 0) {
            while ($fila = $result->fetch_assoc()) {
                $perfiles[] = $fila;
            }
        }
        return $perfiles;
    }

    public function insertar($nombre) {
        $sql = "INSERT INTO perfiles (nombre) VALUES (?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);
        return $stmt->execute();
    }

    public function obtener($id) {
        $sql = "SELECT * FROM perfiles WHERE id_perfil = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function actualizar($id, $nombre) {
        $sql = "UPDATE perfiles SET nombre=? WHERE id_perfil=?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $nombre, $id);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $sql = "DELETE FROM perfiles WHERE id_perfil=?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
