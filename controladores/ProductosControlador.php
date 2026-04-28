<?php
// Aseguramos que la ruta sea exacta
require_once __DIR__ . "/../modelo/conexion.php";
require_once __DIR__ . "/../modelo/Productos.php";

class ProductosControlador {
    // CORRECCIÓN: Agregamos 'Producto' antes de '$modelo' para quitar la marca naranja
    private Producto $modelo; 

    public function __construct() {
        $conexionObj = new Conexion();
        $conexion = $conexionObj->getConexion();

        // Ahora el editor entiende perfectamente esta asignación
        $this->modelo = new Producto($conexion);
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
