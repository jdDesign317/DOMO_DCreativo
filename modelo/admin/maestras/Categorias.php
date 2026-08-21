<?php

require_once __DIR__ . "/../../../config/Conexion.php";

class Categorias {

    private $conexion;

    public function __construct() {
        $this->conexion = (new Conexion())->getConexion();
    }

    // LISTAR SOLO ACTIVAS (para selects y vistas públicas)
    public function listar() {

        $sql = "SELECT * FROM categorias
                WHERE activo = 1
                ORDER BY nombre ASC";

        $resultado = $this->conexion->query($sql);

        $categorias = [];

        while ($fila = $resultado->fetch_assoc()) {
            $categorias[] = $fila;
        }

        return $categorias;
    }

    // LISTAR TODAS (activas e inactivas — para panel admin)
    public function listarTodas() {

        $sql = "SELECT * FROM categorias
                ORDER BY nombre ASC";

        $resultado = $this->conexion->query($sql);

        $categorias = [];

        while ($fila = $resultado->fetch_assoc()) {
            $categorias[] = $fila;
        }

        return $categorias;
    }

    // BUSCAR POR ID
    public function buscarPorId($id_categoria) {

        $sql = "SELECT * FROM categorias
                WHERE id_categoria = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_categoria);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // CREAR
    public function crear($nombre) {

        $nombre = strtolower(trim($nombre));

        $sql = "INSERT INTO categorias (nombre, activo)
                VALUES (?, 1)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);

        return $stmt->execute();
    }

    // ACTUALIZAR
    public function actualizar($id_categoria, $nombre) {

        $nombre = strtolower(trim($nombre));

        $sql = "UPDATE categorias SET
                nombre = ?
                WHERE id_categoria = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $nombre, $id_categoria);

        return $stmt->execute();
    }

    // BAJA LOGICA
    public function desactivar($id_categoria) {

        $sql = "UPDATE categorias
                SET activo = 0
                WHERE id_categoria = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_categoria);

        return $stmt->execute();
    }

    // REACTIVAR
    public function reactivar($id_categoria) {

        $sql = "UPDATE categorias
                SET activo = 1
                WHERE id_categoria = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_categoria);

        return $stmt->execute();
    }

    // VERIFICAR NOMBRE DUPLICADO (para crear)
    public function existeNombre($nombre) {

        $nombre = strtolower(trim($nombre));

        $sql = "SELECT COUNT(*) as total FROM categorias
                WHERE nombre = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_assoc();

        return $resultado["total"] > 0;
    }

    // VERIFICAR NOMBRE DUPLICADO EN OTRO REGISTRO (para editar)
    public function existeNombreParaOtro($nombre, $id_categoria) {

        $nombre = strtolower(trim($nombre));

        $sql = "SELECT COUNT(*) as total FROM categorias
                WHERE nombre = ? AND id_categoria != ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $nombre, $id_categoria);
        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_assoc();

        return $resultado["total"] > 0;
    }

}

