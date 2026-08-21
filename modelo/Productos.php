<?php

require_once __DIR__ . "/../config/Conexion.php";

class Productos
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = (new Conexion())->getConexion();
    }

    // LISTAR SOLO ACTIVOS (para el cliente)
    public function listar()
    {
        $sql = "SELECT * FROM productos WHERE activo = 1";
        $resultado = $this->conexion->query($sql);

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // LISTAR TODOS, ACTIVOS E INACTIVOS (para el panel admin)
    public function listarTodas()
    {
        $sql = "SELECT * FROM productos";
        $resultado = $this->conexion->query($sql);

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // BUSCAR POR ID
    public function buscarPorId($id_producto)
    {
        $sql = "SELECT * FROM productos WHERE id_producto = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // CREAR
    public function crear($nombre, $descripcion, $precio)
    {
        $sql = "INSERT INTO productos (nombre, descripcion, precio)
                VALUES (?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssd", $nombre, $descripcion, $precio);
        $stmt->execute();

        return $this->conexion->insert_id;
    }

    // ACTUALIZAR
    public function actualizar($id_producto, $nombre, $descripcion, $precio)
    {
        $sql = "UPDATE productos SET
                    nombre = ?,
                    descripcion = ?,
                    precio = ?
                WHERE id_producto = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssdi", $nombre, $descripcion, $precio, $id_producto);

        return $stmt->execute();
    }

    // BAJA LOGICA (antes era DELETE físico)
    public function desactivar($id_producto)
    {
        $sql = "UPDATE productos SET activo = 0 WHERE id_producto = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_producto);

        return $stmt->execute();
    }

    // REACTIVAR
    public function reactivar($id_producto)
    {
        $sql = "UPDATE productos SET activo = 1 WHERE id_producto = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_producto);

        return $stmt->execute();
    }
}