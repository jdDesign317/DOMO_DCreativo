<?php
require_once __DIR__ . "/../modelo/Pagos.php";
require_once __DIR__ . "/../modelo/MetodoPago.php";

class PagosControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new Pago();
    }

    /* 
       LISTAR PAGOS
     */
    public function listar() {
        return $this->modelo->listar();
    }

    /* 
       INSERTAR PAGO
     */
    public function insertar($id_pedido, $id_metodo_pago, $monto, $fecha_pago = null) {
        return $this->modelo->insertar($id_pedido, $id_metodo_pago, $monto, $fecha_pago);
    }

    /* 
       OBTENER PAGO POR ID
     */
    public function obtener($id) {
        return $this->modelo->obtener($id);
    }

    /* 
       ACTUALIZAR PAGO
     */
    public function actualizar($id, $id_pedido, $id_metodo_pago, $monto, $fecha_pago) {
        return $this->modelo->actualizar($id, $id_pedido, $id_metodo_pago, $monto, $fecha_pago);
    }

    /* =
       ELIMINAR PAGO
     */
    public function eliminar($id) {
        return $this->modelo->eliminar($id);
    }
}
