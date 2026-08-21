<?php

require_once __DIR__ . "/../config/Conexion.php";

class Presupuestos
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = (new Conexion())->getConexion();
    }

    // LISTAR (con nombre del tipo de evento)
    public function listar()
    {
        $sql = "SELECT presupuestos.*,
                       tipos_evento.nombre AS tipo_evento
                FROM presupuestos
                LEFT JOIN tipos_evento ON presupuestos.id_tipo_evento = tipos_evento.id_tipo_evento
                ORDER BY presupuestos.id_presupuesto DESC";

        $resultado = $this->conexion->query($sql);

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // BUSCAR POR ID (con nombre del tipo de evento)
    public function buscarPorId($id_presupuesto)
    {
        $sql = "SELECT presupuestos.*,
                       tipos_evento.nombre AS tipo_evento
                FROM presupuestos
                LEFT JOIN tipos_evento ON presupuestos.id_tipo_evento = tipos_evento.id_tipo_evento
                WHERE presupuestos.id_presupuesto = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_presupuesto);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    // CREAR
    public function crear($id_usuario, $id_tipo_evento, $descripcion, $total_estimado)
    {
        $sql = "INSERT INTO presupuestos
                (id_usuario, id_tipo_evento, descripcion, total_estimado, estado)
                VALUES (?, ?, ?, ?, 'pendiente')";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param(
            "iisd",
            $id_usuario,
            $id_tipo_evento,
            $descripcion,
            $total_estimado
        );

        return $stmt->execute();
    }

    // ACTUALIZAR
    public function actualizar($id_presupuesto, $descripcion, $total_estimado, $estado)
    {
        $sql = "UPDATE presupuestos SET
                    descripcion    = ?,
                    total_estimado = ?,
                    estado         = ?
                WHERE id_presupuesto = ?";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param(
            "sdsi",
            $descripcion,
            $total_estimado,
            $estado,
            $id_presupuesto
        );

        return $stmt->execute();
    }

    // ELIMINAR
    public function eliminar($id_presupuesto)
    {
        $sql = "DELETE FROM presupuestos
                WHERE id_presupuesto = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_presupuesto);

        return $stmt->execute();
    }
}
