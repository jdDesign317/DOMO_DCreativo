<?php
require_once __DIR__ . "/../modelo/Productos.php";



class ProductosControlador {
    private $modelo;

    public function __construct() {
        // Nombre correcto de la clase del modelo
        $this->modelo = new Producto();
    }

    public function listar(): array {
        return $this->modelo->listar();
    }

    public function insertar(string $nombre, string $descripcion, float $precio): bool {
        return $this->modelo->insertar($nombre, $descripcion, $precio);
    }

    public function obtener(int $id): ?array {
        return $this->modelo->obtener($id);
    }

    public function actualizar(int $id, string $nombre, string $descripcion, float $precio): bool {
        return $this->modelo->actualizar($id, $nombre, $descripcion, $precio);
    }

    public function eliminar(int $id): bool {
        return $this->modelo->eliminar($id);
    }
}
