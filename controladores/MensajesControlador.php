<?php
require_once __DIR__ . "/../modelo/Mensaje.php";

class MensajesControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new Mensaje();
    }

    public function listar() {
        return $this->modelo->listar();
    }

    public function insertar($id_usuario, $remitente, $mensaje, $fecha = null) {
        return $this->modelo->insertar($id_usuario, $remitente, $mensaje, $fecha);
    }

    public function obtener($id) {
        return $this->modelo->obtener($id);
    }

    public function actualizar($id, $id_usuario, $remitente, $mensaje, $fecha) {
        return $this->modelo->actualizar($id, $id_usuario, $remitente, $mensaje, $fecha);
    }

    public function eliminar($id) {
        return $this->modelo->eliminar($id);
    }
}
