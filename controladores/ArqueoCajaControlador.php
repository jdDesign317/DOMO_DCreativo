<?php
require_once __DIR__ . "/../modelo/ArqueoCaja.php";

class ArqueoCajaControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new ArqueoCaja();
    }

    public function listar() {
        return $this->modelo->listar();
    }

    public function insertar($id_usuario, $monto_inicial, $monto_final, $total_ingresos, $total_egresos, $observaciones, $estado, $fecha_apertura = null, $fecha_cierre = null) {
        return $this->modelo->insertar($id_usuario, $monto_inicial, $monto_final, $total_ingresos, $total_egresos, $observaciones, $estado, $fecha_apertura, $fecha_cierre);
    }

    public function obtener($id) {
        return $this->modelo->obtener($id);
    }

    public function actualizar($id, $id_usuario, $fecha_apertura, $fecha_cierre, $monto_inicial, $monto_final, $total_ingresos, $total_egresos, $observaciones, $estado) {
        return $this->modelo->actualizar($id, $id_usuario, $fecha_apertura, $fecha_cierre, $monto_inicial, $monto_final, $total_ingresos, $total_egresos, $observaciones, $estado);
    }

    public function eliminar($id) {
        return $this->modelo->eliminar($id);
    }
}
