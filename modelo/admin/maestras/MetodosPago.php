<?php

require_once __DIR__ . "/../../../config/Conexion.php";

class MetodosPago {

    private $conexion;

    public function __construct() {
        $this->conexion = (new Conexion())->getConexion();
    }

    // LISTAR SOLO ACTIVOS (para selects y vistas públicas)
    public function listar() {

        $sql = "SELECT * FROM metodos_pago
                WHERE activo = 1
                ORDER BY nombre ASC";

        $resultado = $this->conexion->query($sql);

        $metodos = [];

        while ($fila = $resultado->fetch_assoc()) {
            $metodos[] = $fila;
        }

        return $metodos;
    }

    // LISTAR TODOS (activos e inactivos — para panel admin)
    public function listarTodos() {

        $sql = "SELECT * FROM metodos_pago
                ORDER BY nombre ASC";

        $resultado = $this->conexion->query($sql);

        $metodos = [];

        while ($fila = $resultado->fetch_assoc()) {
            $metodos[] = $fila;
        }

        return $metodos;
    }

    // BUSCAR POR ID
    public function buscarPorId($id_metodo_pago) {

        $sql = "SELECT * FROM metodos_pago
                WHERE id_metodo_pago = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_metodo_pago);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // CREAR
    public function crear($nombre) {

        $nombre = strtolower(trim($nombre));

        $sql = "INSERT INTO metodos_pago (nombre, activo)
                VALUES (?, 1)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);

        return $stmt->execute();
    }

    // ACTUALIZAR
    public function actualizar($id_metodo_pago, $nombre) {

        $nombre = strtolower(trim($nombre));

        $sql = "UPDATE metodos_pago SET
                nombre = ?
                WHERE id_metodo_pago = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $nombre, $id_metodo_pago);

        return $stmt->execute();
    }

    // BAJA LOGICA
    public function desactivar($id_metodo_pago) {

        $sql = "UPDATE metodos_pago
                SET activo = 0
                WHERE id_metodo_pago = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_metodo_pago);

        return $stmt->execute();
    }

    // REACTIVAR
    public function reactivar($id_metodo_pago) {

        $sql = "UPDATE metodos_pago
                SET activo = 1
                WHERE id_metodo_pago = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_metodo_pago);

        return $stmt->execute();
    }

    // VERIFICAR NOMBRE DUPLICADO (para crear)
    public function existeNombre($nombre) {

        $nombre = strtolower(trim($nombre));

        $sql = "SELECT COUNT(*) as total FROM metodos_pago
                WHERE nombre = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_assoc();

        return $resultado["total"] > 0;
    }

    // VERIFICAR NOMBRE DUPLICADO EN OTRO REGISTRO (para editar)
    public function existeNombreParaOtro($nombre, $id_metodo_pago) {

        $nombre = strtolower(trim($nombre));

        $sql = "SELECT COUNT(*) as total FROM metodos_pago
                WHERE nombre = ? AND id_metodo_pago != ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $nombre, $id_metodo_pago);
        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_assoc();

        return $resultado["total"] > 0;
    }

}
