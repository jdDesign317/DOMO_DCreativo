<?php

require_once __DIR__ . "/../modelo/Presupuestos.php";

class PresupuestosControlador
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new Presupuestos();
    }

    // LISTAR
    public function listar()
    {
        return $this->modelo->listar();
    }

    // VER
    public function ver($id_presupuesto)
    {
        return $this->modelo->buscarPorId($id_presupuesto);
    }

    // CREAR
    public function crear($id_usuario, $id_tipo_evento, $descripcion, $total_estimado)
    {
        return $this->modelo->crear(
            $id_usuario,
            $id_tipo_evento,
            $descripcion,
            $total_estimado
        );
    }

    // EDITAR
    public function editar($id_presupuesto, $descripcion, $total_estimado, $estado)
    {
        return $this->modelo->actualizar(
            $id_presupuesto,
            $descripcion,
            $total_estimado,
            $estado
        );
    }

    // ELIMINAR
    public function eliminar($id_presupuesto)
    {
        return $this->modelo->eliminar($id_presupuesto);
    }
}
