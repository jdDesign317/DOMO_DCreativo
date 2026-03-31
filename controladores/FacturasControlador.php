<?php
require_once __DIR__ . "/../modelo/Factura.php";

class FacturasControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new Factura();
    }

    public function listar() {
        return $this->modelo->listar();
    }

    public function insertar($id_pedido, $monto_total, $archivo_factura, $estado = 'generada', $fecha_emision = null) {
        return $this->modelo->insertar($id_pedido, $monto_total, $archivo_factura, $estado, $fecha_emision);
    }

    public function obtener($id) {
        return $this->modelo->obtener($id);
    }

    public function actualizar($id, $id_pedido, $fecha_emision, $monto_total, $archivo_factura, $estado) {
        return $this->modelo->actualizar($id, $id_pedido, $fecha_emision, $monto_total, $archivo_factura, $estado);
    }

    public function eliminar($id) {
        return $this->modelo->eliminar($id);
    }
}
