<?php
require_once "Conexion.php";

class Presupuestos {

    private $conn;

    public function __construct() {
        $conexion = new Conexion();
        $this->conn = $conexion->getConexion();   // ESTO DEVUELVE MYSQLI
    }

    /* 
        LISTAR TODOS
     */
    public function listar() {

        $sql = "SELECT * FROM presupuestos ORDER BY id_presupuesto DESC";
        $result = $this->conn->query($sql);

        $data = [];
        if ($result) {
            while ($fila = $result->fetch_assoc()) {
                $data[] = $fila;
            }
        }
        return $data;
    }

    /* 
        BUSCAR UNO
    */
    public function buscar($id) {

        $sql = "SELECT * FROM presupuestos WHERE id_presupuesto = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    /* 
        CREAR
     */
    public function crear($id_usuario, $total_estimado, $estado) {

        $sql = "INSERT INTO presupuestos (id_usuario, total_estimado, estado) 
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ids", $id_usuario, $total_estimado, $estado);

        return $stmt->execute();
    }

    /* 
        ACTUALIZAR
     */
    public function actualizar($id, $datos) {

        $sql = "UPDATE presupuestos 
                SET id_usuario = ?, 
                    total_estimado = ?, 
                    estado = ? 
                WHERE id_presupuesto = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "idsi",
            $datos["id_usuario"],
            $datos["total_estimado"],
            $datos["estado"],
            $id
        );

        return $stmt->execute();
    }

    /* 
        ELIMINAR
    */
    public function eliminar($id) {

        $sql = "DELETE FROM presupuestos WHERE id_presupuesto = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}
?>
