<?php

require_once __DIR__ . '/../../../modelo/admin/maestras/MetodosPago.php';

class MetodosPagoControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new MetodosPago();
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
    public function buscarPorId($id_metodo_pago) {

        if (empty($id_metodo_pago)) {
            return false;
        }

        return $this->modelo->buscarPorId($id_metodo_pago);
    }

    // CREAR
    public function crear($nombre) {

        $nombre = strtolower(trim($nombre));

        if (empty($nombre)) return "vacio";

        if ($this->modelo->existeNombre($nombre)) return "duplicado";

        return $this->modelo->crear($nombre);
    }

    // ACTUALIZAR
    public function actualizar($id_metodo_pago, $nombre) {

        $nombre = strtolower(trim($nombre));

        if (empty($id_metodo_pago) || empty($nombre)) return "vacio";

        if ($this->modelo->existeNombreParaOtro($nombre, $id_metodo_pago)) return "duplicado";

        return $this->modelo->actualizar($id_metodo_pago, $nombre);
    }

    // BAJA LOGICA
    public function desactivar($id_metodo_pago) {

        if (empty($id_metodo_pago)) {
            return false;
        }

        return $this->modelo->desactivar($id_metodo_pago);
    }

    // REACTIVAR
    public function reactivar($id_metodo_pago) {

        if (empty($id_metodo_pago)) {
            return false;
        }

        return $this->modelo->reactivar($id_metodo_pago);
    }
}
