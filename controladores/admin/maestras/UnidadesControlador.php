<?php

require_once __DIR__ . '/../../../modelo/admin/maestras/Unidades.php';

class UnidadesControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new Unidades();
    }

    // LISTAR SOLO ACTIVAS
    public function listar() {
        return $this->modelo->listar();
    }

    // LISTAR TODAS (para panel admin)
    public function listarTodas() {
        return $this->modelo->listarTodas();
    }

    // BUSCAR POR ID
    public function buscarPorId($id_unidad) {

        if (empty($id_unidad)) {
            return false;
        }

        return $this->modelo->buscarPorId($id_unidad);
    }

    // CREAR
    public function crear($nombre) {

        $nombre = strtolower(trim($nombre));

        if (empty($nombre)) return "vacio";

        if ($this->modelo->existeNombre($nombre)) return "duplicado";

        return $this->modelo->crear($nombre);
    }

    // ACTUALIZAR
    public function actualizar($id_unidad, $nombre) {

        $nombre = strtolower(trim($nombre));

        if (empty($id_unidad) || empty($nombre)) return "vacio";

        if ($this->modelo->existeNombreParaOtro($nombre, $id_unidad)) return "duplicado";

        return $this->modelo->actualizar($id_unidad, $nombre);
    }

    // BAJA LOGICA
    public function desactivar($id_unidad) {

        if (empty($id_unidad)) {
            return false;
        }

        return $this->modelo->desactivar($id_unidad);
    }

    // REACTIVAR
    public function reactivar($id_unidad) {

        if (empty($id_unidad)) {
            return false;
        }

        return $this->modelo->reactivar($id_unidad);
    }
}
