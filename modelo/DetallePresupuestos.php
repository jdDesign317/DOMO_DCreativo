<?php
// modelo/DetallePresupuestos.php
require_once "conexion.php";  // Usa tu clase correcta

class DetallePresupuestos
{
    private $db;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion(); // mysqli
    }

    /* 
       LISTAR DETALLES POR PRESUPUESTO
    */
    public function listarPorPresupuesto($id_presupuesto)
    {
        $sql = "SELECT dp.*, p.nombre AS producto_nombre
                FROM detalle_presupuestos dp
                INNER JOIN productos p ON dp.id_producto = p.id_producto
                WHERE dp.id_presupuesto = ?";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];

        $stmt->bind_param("i", $id_presupuesto);
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

        $stmt->close();
        return $rows;
    }

    /* 
       OBTENER DETALLE
     */
    public function obtener($id_detalle)
    {
        $sql = "SELECT * FROM detalle_presupuestos WHERE id_detalle = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $id_detalle);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result ? $result->fetch_assoc() : null;

        $stmt->close();
        return $row;
    }

    /* 
       INSERTAR DETALLE
     */
    public function insertar($id_presupuesto, $id_producto, $cantidad, $precio_unitario)
    {
        $sql = "INSERT INTO detalle_presupuestos 
                (id_presupuesto, id_producto, cantidad, precio_unitario)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("iiid",
            $id_presupuesto,
            $id_producto,
            $cantidad,
            $precio_unitario
        );

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /* 
       ACTUALIZAR (Si luego lo necesitás)
     */
    public function actualizar($id_detalle, $id_producto, $cantidad, $precio_unitario)
    {
        $sql = "UPDATE detalle_presupuestos
                SET id_producto = ?, cantidad = ?, precio_unitario = ?
                WHERE id_detalle = ?";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("iidi",
            $id_producto,
            $cantidad,
            $precio_unitario,
            $id_detalle
        );

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /* 
       ELIMINAR
     */
    public function eliminar($id_detalle)
    {
        $sql = "DELETE FROM detalle_presupuestos WHERE id_detalle = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("i", $id_detalle);

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /* 
       ELIMINAR TODOS POR PRESUPUESTO
     */
public function eliminarPorPresupuesto($id_presupuesto)
{
    $sql = "DELETE FROM detalle_presupuestos WHERE id_presupuesto = ?";
    $stmt = $this->db->prepare($sql);
    if (!$stmt) return false;
    $stmt->bind_param("i", $id_presupuesto);
    return $stmt->execute();
}

    
}
?>
