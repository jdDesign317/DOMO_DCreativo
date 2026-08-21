<?php

require_once __DIR__ . "/../config/Conexion.php";

class ServiciosPersonalizados
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = (new Conexion())->getConexion();
    }

    // LISTAR
    public function listar()
    {
        $sql = "SELECT * FROM servicios_personalizados";

        $resultado = $this->conexion->query($sql);

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // BUSCAR POR ID
    public function buscarPorId($id)
{
    $sql = "SELECT 
                servicios_personalizados.*,
                productos.nombre AS nombre_producto
            FROM servicios_personalizados
            INNER JOIN productos 
                ON servicios_personalizados.id_producto = productos.id_producto
            WHERE servicios_personalizados.id_servicio_personalizado = ?";

    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();
    return $resultado->fetch_assoc();
}

    // CREAR
    public function crear(
        $id_usuario,
        $id_producto,
        $color,
        $texto_personalizado,
        $archivo_diseno
    )
    {
        $sql = "INSERT INTO servicios_personalizados
                (
                    id_usuario,
                    id_producto,
                    color,
                    texto_personalizado,
                    archivo_diseno
                )
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param(
            "iisss",
            $id_usuario,
            $id_producto,
            $color,
            $texto_personalizado,
            $archivo_diseno
        );

        return $stmt->execute();
    }

    // ACTUALIZAR
    public function actualizar(
        $id_servicio_personalizado,
        $id_producto,
        $color,
        $texto_personalizado,
        $archivo_diseno,
        $estado
    )
    {
        $sql = "UPDATE servicios_personalizados SET
                    id_producto = ?,
                    color = ?,
                    texto_personalizado = ?,
                    archivo_diseno = ?,
                    estado = ?
                WHERE id_servicio_personalizado = ?";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param(
            "issssi",
            $id_producto,
            $color,
            $texto_personalizado,
            $archivo_diseno,
            $estado,
            $id_servicio_personalizado
        );

        return $stmt->execute();
    }

    // ELIMINAR
    public function eliminar($id_servicio_personalizado)
    {
        $sql = "DELETE FROM servicios_personalizados
                WHERE id_servicio_personalizado = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_servicio_personalizado);

        return $stmt->execute();
    }
}