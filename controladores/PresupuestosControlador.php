<?php
require_once __DIR__ . "/../modelo/Presupuestos.php";

class PresupuestosControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new Presupuestos();  // CORRECTO
    }

    public function listar() {
        return $this->modelo->listar();
    }

    public function buscar($id) {
        return $this->modelo->buscar($id);
    }

    public function crear($id_usuario, $total_estimado, $estado) {
        return $this->modelo->crear($id_usuario, $total_estimado, $estado);
    }

    public function actualizar($id, $datos) {
        return $this->modelo->actualizar($id, $datos);
    }

    public function eliminar($id) {
        return $this->modelo->eliminar($id);
    }
}
