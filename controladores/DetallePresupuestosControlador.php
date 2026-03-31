<?php
require_once "../modelo/DetallePresupuestos.php";

class DetallePresupuestosControlador
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new DetallePresupuestos();
    }

    /* 
       LISTAR POR PRESUPUESTO
 */
    public function listarPorPresupuesto($id_presupuesto)
    {
        return $this->modelo->listarPorPresupuesto($id_presupuesto);
    }

    /* 
       OBTENER UN DETALLE
    */
    public function obtener($id_detalle)
    {
        return $this->modelo->obtener($id_detalle);
    }

    /* 
       INSERTAR
     */
    public function insertar($id_presupuesto, $id_producto, $cantidad, $precio_unitario)
    {
        return $this->modelo->insertar($id_presupuesto, $id_producto, $cantidad, $precio_unitario);
    }

    /* 
       ACTUALIZAR
    */
    public function actualizar($id_detalle, $id_producto, $cantidad, $precio_unitario)
    {
        return $this->modelo->actualizar($id_detalle, $id_producto, $cantidad, $precio_unitario);
    }

    /* 
       ELIMINAR
  */
    public function eliminar($id_detalle)
    {
        return $this->modelo->eliminar($id_detalle);
    }

    /* 
       ELIMINAR POR PRESUPUESTO (si lo necesitás)
    */
    public function eliminarPorPresupuesto($id_presupuesto)
    {
        return $this->modelo->eliminarPorPresupuesto($id_presupuesto);
    }
}
?>
