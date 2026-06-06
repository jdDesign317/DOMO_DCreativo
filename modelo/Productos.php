<?php

require_once __DIR__ . "/../config/Conexion.php";

class Productos
{
    private $db;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->db = $conexion->getConexion();
    }

    // LISTAR
    public function listar()
    {
        $sql = "SELECT * FROM productos";

        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // BUSCAR POR ID
    public function buscarPorId($id_producto)
    {
        $sql = "SELECT * FROM productos
                WHERE id_producto = $id_producto";

        return $this->db->query($sql)->fetch_assoc();
    }

    // CREAR
    public function crear($nombre, $descripcion, $precio)
    {
        $sql = "INSERT INTO productos
                (nombre, descripcion, precio)
                VALUES
                ('$nombre', '$descripcion', '$precio')";

        return $this->db->query($sql);
    }

    // ACTUALIZAR
    public function actualizar($id_producto, $nombre, $descripcion, $precio)
    {
        $sql = "UPDATE productos SET
                    nombre = '$nombre',
                    descripcion = '$descripcion',
                    precio = '$precio'
                WHERE id_producto = $id_producto";

        return $this->db->query($sql);
    }

    // ELIMINAR
    public function eliminar($id_producto)
    {
        $sql = "DELETE FROM productos
                WHERE id_producto = $id_producto";

        return $this->db->query($sql);
    }
}