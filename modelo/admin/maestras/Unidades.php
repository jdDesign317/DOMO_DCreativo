<?php

require_once __DIR__ . "/../../../config/Conexion.php";

class Unidades {

    private $conexion;

    public function __construct() {
        $this->conexion = (new Conexion())->getConexion();
    }

    // LISTAR SOLO ACTIVAS (para selects y vistas públicas)
    public function listar() {

        $sql = "SELECT * FROM unidades
                WHERE activo = 1
                ORDER BY nombre ASC";

        $resultado = $this->conexion->query($sql);

        $unidades = [];

        while ($fila = $resultado->fetch_assoc()) {
            $unidades[] = $fila;
        }

        return $unidades;
    }

    // LISTAR TODAS (activas e inactivas — para panel admin)
    public function listarTodas() {

        $sql = "SELECT * FROM unidades
                ORDER BY nombre ASC";

        $resultado = $this->conexion->query($sql);

        $unidades = [];

        while ($fila = $resultado->fetch_assoc()) {
            $unidades[] = $fila;
        }

        return $unidades;
    }

    // BUSCAR POR ID
    public function buscarPorId($id_unidad) {

        $sql = "SELECT * FROM unidades
                WHERE id_unidad = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_unidad);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // CREAR
    public function crear($nombre) {

        $nombre = strtolower(trim($nombre));

        $sql = "INSERT INTO unidades (nombre, activo)
                VALUES (?, 1)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);

        return $stmt->execute();
    }

    // ACTUALIZAR
    public function actualizar($id_unidad, $nombre) {

        $nombre = strtolower(trim($nombre));

        $sql = "UPDATE unidades SET
                nombre = ?
                WHERE id_unidad = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $nombre, $id_unidad);

        return $stmt->execute();
    }

    // BAJA LOGICA
    public function desactivar($id_unidad) {

        $sql = "UPDATE unidades
                SET activo = 0
                WHERE id_unidad = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_unidad);

        return $stmt->execute();
    }

    // REACTIVAR
    public function reactivar($id_unidad) {

        $sql = "UPDATE unidades
                SET activo = 1
                WHERE id_unidad = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_unidad);

        return $stmt->execute();
    }

    // VERIFICAR NOMBRE DUPLICADO (para crear)
    public function existeNombre($nombre) {

        $nombre = strtolower(trim($nombre));

        $sql = "SELECT COUNT(*) as total FROM unidades
                WHERE nombre = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_assoc();

        return $resultado["total"] > 0;
    }

    // VERIFICAR NOMBRE DUPLICADO EN OTRO REGISTRO (para editar)
    public function existeNombreParaOtro($nombre, $id_unidad) {

        $nombre = strtolower(trim($nombre));

        $sql = "SELECT COUNT(*) as total FROM unidades
                WHERE nombre = ? AND id_unidad != ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $nombre, $id_unidad);
        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_assoc();

        return $resultado["total"] > 0;
    }

}
