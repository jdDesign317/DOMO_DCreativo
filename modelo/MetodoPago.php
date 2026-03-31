<?php
require_once __DIR__ . "/conexion.php";

class MetodoPago {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
    }

    public function listar() {
        $sql = "SELECT * FROM metodos_pago ORDER BY id_metodo_pago ASC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
