<?php

require_once __DIR__ . "/../config/Conexion.php";

class Auditoria
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = (new Conexion())->getConexion();
    }

    // REGISTRAR UNA ACCIÓN DE AUDITORÍA
    // $datos_anteriores / $datos_nuevos: arrays asociativos (ej: ["precio" => 2500]).
    // Se guardan en la base como texto JSON.
    public function registrar($id_usuario, $accion, $tabla_afectada, $registro_id,
                               $descripcion = null, $datos_anteriores = null, $datos_nuevos = null)
    {
        $json_anteriores = $datos_anteriores ? json_encode($datos_anteriores, JSON_UNESCAPED_UNICODE) : null;
        $json_nuevos = $datos_nuevos ? json_encode($datos_nuevos, JSON_UNESCAPED_UNICODE) : null;

        $sql = "INSERT INTO auditoria
                (id_usuario, accion, tabla_afectada, registro_id, descripcion, datos_anteriores, datos_nuevos)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param(
            "ississs",
            $id_usuario,
            $accion,
            $tabla_afectada,
            $registro_id,
            $descripcion,
            $json_anteriores,
            $json_nuevos
        );

        return $stmt->execute();
    }

    // LISTAR AUDITORÍA (para verla desde el panel admin)
    public function listar()
    {
        $sql = "SELECT a.*, u.nombre, u.apellidos
                FROM auditoria a
                INNER JOIN usuarios u ON u.id_usuario = a.id_usuario
                ORDER BY a.fecha DESC";

        $resultado = $this->conexion->query($sql);

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}