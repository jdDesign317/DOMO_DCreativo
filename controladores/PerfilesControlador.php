<?php
require_once __DIR__ . "/../modelo/Perfil.php";

class PerfilesControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new Perfil();
    }

    public function listar() {
        return $this->modelo->listar();
    }

    public function insertar($descripcion) {
        return $this->modelo->insertar($descripcion);
    }

    public function obtener($id) {
        return $this->modelo->obtener($id);
    }

    public function actualizar($id, $descripcion) {
        return $this->modelo->actualizar($id, $descripcion);
    }

    public function eliminar($id) {
        return $this->modelo->eliminar($id);
    }
}
