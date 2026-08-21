<?php

require_once __DIR__ . "/../../../config/Conexion.php";

class TiposServicio {

    private $conexion;

    public function __construct() {
        $this->conexion = (new Conexion())->getConexion();
    }

    // LISTAR SOLO ACTIVOS (para selects y vistas públicas)
    public function listar() {

        $sql = "SELECT * FROM tipos_servicio
                WHERE activo = 1
                ORDER BY nombre ASC";

        $resultado = $this->conexion->query($sql);

        $tipos = [];

        while ($fila = $resultado->fetch_assoc()) {
            $tipos[] = $fila;
        }

        return $tipos;
    }

    // LISTAR TODOS (activos e inactivos — para panel admin)
    public function listarTodos() {

        $sql = "SELECT * FROM tipos_servicio
                ORDER BY nombre ASC";

        $resultado = $this->conexion->query($sql);

        $tipos = [];

        while ($fila = $resultado->fetch_assoc()) {
            $tipos[] = $fila;
        }

        return $tipos;
    }

    // BUSCAR POR ID
    public function buscarPorId($id_tipo_servicio) {

        $sql = "SELECT * FROM tipos_servicio
                WHERE id_tipo_servicio = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_tipo_servicio);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // CREAR
    public function crear($nombre) {

        $nombre = strtolower(trim($nombre));

        $sql = "INSERT INTO tipos_servicio (nombre, activo)
                VALUES (?, 1)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);

        return $stmt->execute();
    }

    // ACTUALIZAR
    public function actualizar($id_tipo_servicio, $nombre) {

        $nombre = strtolower(trim($nombre));

        $sql = "UPDATE tipos_servicio SET
                nombre = ?
                WHERE id_tipo_servicio = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $nombre, $id_tipo_servicio);

        return $stmt->execute();
    }

    // BAJA LOGICA
    public function desactivar($id_tipo_servicio) {

        $sql = "UPDATE tipos_servicio
                SET activo = 0
                WHERE id_tipo_servicio = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_tipo_servicio);

        return $stmt->execute();
    }

    // REACTIVAR
    public function reactivar($id_tipo_servicio) {

        $sql = "UPDATE tipos_servicio
                SET activo = 1
                WHERE id_tipo_servicio = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_tipo_servicio);

        return $stmt->execute();
    }

    // VERIFICAR NOMBRE DUPLICADO (para crear)
    public function existeNombre($nombre) {

        $nombre = strtolower(trim($nombre));

        $sql = "SELECT COUNT(*) as total FROM tipos_servicio
                WHERE nombre = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_assoc();

        return $resultado["total"] > 0;
    }

    // VERIFICAR NOMBRE DUPLICADO EN OTRO REGISTRO (para editar)
    public function existeNombreParaOtro($nombre, $id_tipo_servicio) {

        $nombre = strtolower(trim($nombre));

        $sql = "SELECT COUNT(*) as total FROM tipos_servicio
                WHERE nombre = ? AND id_tipo_servicio != ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $nombre, $id_tipo_servicio);
        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_assoc();

        return $resultado["total"] > 0;
    }

}
