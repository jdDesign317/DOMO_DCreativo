<?php

require_once __DIR__ . '/../../../modelo/admin/maestras/TiposServicio.php';

class TiposServicioControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new TiposServicio();
    }

    // LISTAR SOLO ACTIVOS
    public function listar() {
        return $this->modelo->listar();
    }

    // LISTAR TODOS (para panel admin)
    public function listarTodos() {
        return $this->modelo->listarTodos();
    }

    // BUSCAR POR ID
    public function buscarPorId($id_tipo_servicio) {

        if (empty($id_tipo_servicio)) {
            return false;
        }

        return $this->modelo->buscarPorId($id_tipo_servicio);
    }

    // CREAR
    public function crear($nombre) {

        $nombre = strtolower(trim($nombre));

        if (empty($nombre)) return "vacio";

        if ($this->modelo->existeNombre($nombre)) return "duplicado";

        return $this->modelo->crear($nombre);
    }

    // ACTUALIZAR
    public function actualizar($id_tipo_servicio, $nombre) {

        $nombre = strtolower(trim($nombre));

        if (empty($id_tipo_servicio) || empty($nombre)) return "vacio";

        if ($this->modelo->existeNombreParaOtro($nombre, $id_tipo_servicio)) return "duplicado";

        return $this->modelo->actualizar($id_tipo_servicio, $nombre);
    }

    // BAJA LOGICA
    public function desactivar($id_tipo_servicio) {

        if (empty($id_tipo_servicio)) {
            return false;
        }

        return $this->modelo->desactivar($id_tipo_servicio);
    }

    // REACTIVAR
    public function reactivar($id_tipo_servicio) {

        if (empty($id_tipo_servicio)) {
            return false;
        }

        return $this->modelo->reactivar($id_tipo_servicio);
    }
}
