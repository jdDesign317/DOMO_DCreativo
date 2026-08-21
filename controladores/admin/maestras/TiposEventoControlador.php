<?php

require_once __DIR__ . '/../../../modelo/admin/maestras/TiposEvento.php';

class TiposEventoControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new TiposEvento();
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
    public function buscarPorId($id_tipo_evento) {

        if (empty($id_tipo_evento)) {
            return false;
        }

        return $this->modelo->buscarPorId($id_tipo_evento);
    }

    // CREAR
    public function crear($nombre) {

        $nombre = strtolower(trim($nombre));

        if (empty($nombre)) return "vacio";

        if ($this->modelo->existeNombre($nombre)) return "duplicado";

        return $this->modelo->crear($nombre);
    }

    // ACTUALIZAR
    public function actualizar($id_tipo_evento, $nombre) {

        $nombre = strtolower(trim($nombre));

        if (empty($id_tipo_evento) || empty($nombre)) return "vacio";

        if ($this->modelo->existeNombreParaOtro($nombre, $id_tipo_evento)) return "duplicado";

        return $this->modelo->actualizar($id_tipo_evento, $nombre);
    }

    // BAJA LOGICA
    public function desactivar($id_tipo_evento) {

        if (empty($id_tipo_evento)) {
            return false;
        }

        return $this->modelo->desactivar($id_tipo_evento);
    }

    // REACTIVAR
    public function reactivar($id_tipo_evento) {

        if (empty($id_tipo_evento)) {
            return false;
        }

        return $this->modelo->reactivar($id_tipo_evento);
    }
}
