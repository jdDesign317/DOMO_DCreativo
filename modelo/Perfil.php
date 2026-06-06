<?php

require_once __DIR__ . "/../config/Conexion.php";

class Perfil
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = (new Conexion())->getConexion();
    }

    // LISTAR PERFILES
    public function listar()
    {
        $sql = "SELECT *
                FROM perfiles
                ORDER BY nombre ASC";

        $resultado = $this->conexion->query($sql);

        $perfiles = [];

        while ($fila = $resultado->fetch_assoc()) {
            $perfiles[] = $fila;
        }

        return $perfiles;
    }

    // CREAR PERFIL
    public function insertar($nombre)
    {
        $nombre = strtolower(trim($nombre));

        $sql = "INSERT INTO perfiles (nombre)
                VALUES (?)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);

        return $stmt->execute();
    }

    // BUSCAR PERFIL POR ID
    public function obtener($id_perfil)
    {
        $sql = "SELECT *
                FROM perfiles
                WHERE id_perfil = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_perfil);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // ACTUALIZAR PERFIL
    public function actualizar($id_perfil, $nombre)
    {
        $nombre = strtolower(trim($nombre));

        $sql = "UPDATE perfiles
                SET nombre = ?
                WHERE id_perfil = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param(
            "si",
            $nombre,
            $id_perfil
        );

        return $stmt->execute();
    }

    // ELIMINAR PERFIL
    public function eliminar($id_perfil)
    {
        $sql = "DELETE FROM perfiles
                WHERE id_perfil = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_perfil);

        return $stmt->execute();
    }
}