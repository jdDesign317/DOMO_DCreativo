<?php

require_once __DIR__ . "/../modelo/Productos.php";

class ProductosControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new Productos();
    }

    // LISTAR
    public function listar() {
        return $this->modelo->listar();
    }

    // BUSCAR POR ID
    public function buscarPorId($id) {
        return $this->modelo->buscarPorId($id);
    }

    // CREAR
    public function crear($data) {

        return $this->modelo->crear(
            $data["nombre"],
            $data["descripcion"],
            $data["precio"]
        );
    }

    // ACTUALIZAR
    public function actualizar($data) {

        return $this->modelo->actualizar(
            $data["id_producto"],
            $data["nombre"],
            $data["descripcion"],
            $data["precio"]
        );
    }

    // ELIMINAR
    public function eliminar($id) {
        return $this->modelo->eliminar($id);
    }
}