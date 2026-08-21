<?php

require_once __DIR__ . "/../modelo/Productos.php";
require_once __DIR__ . "/../modelo/Auditoria.php";
require_once __DIR__ . "/../helpers/Auth.php";

class ProductosControlador {

    private $modelo;
    private $auditoria;

    public function __construct() {
        $this->modelo = new Productos();
        $this->auditoria = new Auditoria();
    }

    // LISTAR (solo activos)
    public function listar() {
        return $this->modelo->listar();
    }

    // LISTAR TODOS (para el admin, incluye inactivos)
    public function listarTodas() {
        return $this->modelo->listarTodas();
    }

    // BUSCAR POR ID
    public function buscarPorId($id) {
        return $this->modelo->buscarPorId($id);
    }

    // CREAR
    public function crear($data) {

        $id_producto = $this->modelo->crear(
            $data["nombre"],
            $data["descripcion"],
            $data["precio"]
        );

        $this->auditoria->registrar(
            Auth::usuario(),
            "INSERT",
            "productos",
            $id_producto,
            "Producto creado: " . $data["nombre"],
            null,
            [
                "nombre" => $data["nombre"],
                "descripcion" => $data["descripcion"],
                "precio" => $data["precio"]
            ]
        );

        return $id_producto;
    }

    // ACTUALIZAR
    public function actualizar($data) {

        // GUARDAMOS EL "ANTES" PARA LA AUDITORÍA
        $producto_anterior = $this->modelo->buscarPorId($data["id_producto"]);

        $resultado = $this->modelo->actualizar(
            $data["id_producto"],
            $data["nombre"],
            $data["descripcion"],
            $data["precio"]
        );

        $this->auditoria->registrar(
            Auth::usuario(),
            "UPDATE",
            "productos",
            $data["id_producto"],
            "Producto editado: " . $data["nombre"],
            [
                "nombre" => $producto_anterior["nombre"],
                "descripcion" => $producto_anterior["descripcion"],
                "precio" => $producto_anterior["precio"]
            ],
            [
                "nombre" => $data["nombre"],
                "descripcion" => $data["descripcion"],
                "precio" => $data["precio"]
            ]
        );

        return $resultado;
    }

    // BAJA LOGICA
    public function desactivar($id) {

        $producto = $this->modelo->buscarPorId($id);
        $resultado = $this->modelo->desactivar($id);

        $this->auditoria->registrar(
            Auth::usuario(),
            "DELETE",
            "productos",
            $id,
            "Producto dado de baja: " . ($producto["nombre"] ?? ""),
            ["activo" => 1],
            ["activo" => 0]
        );

        return $resultado;
    }

    // REACTIVAR
    public function reactivar($id) {

        $producto = $this->modelo->buscarPorId($id);
        $resultado = $this->modelo->reactivar($id);

        $this->auditoria->registrar(
            Auth::usuario(),
            "UPDATE",
            "productos",
            $id,
            "Producto reactivado: " . ($producto["nombre"] ?? ""),
            ["activo" => 0],
            ["activo" => 1]
        );

        return $resultado;
    }
}