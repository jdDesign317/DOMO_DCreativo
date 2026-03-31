<?php
class CarritoDetalle {
    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("localhost", "root", "", "domo_creativo");
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
        $this->conexion->set_charset("utf8mb4");
    }

    // Listar detalles de un carrito
    public function listarPorCarrito(int $id_carrito): array {
        $sql = "SELECT d.id_detalle, d.id_carrito, d.id_producto, d.cantidad,
                       p.nombre, p.descripcion, p.precio
                FROM carrito_detalle d
                JOIN productos p ON d.id_producto = p.id_producto
                WHERE d.id_carrito = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_carrito);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    // Guardar detalle (insertar o actualizar)
    public function guardar(int $id_carrito, int $id_producto, int $cantidad, ?int $id_detalle = null): bool {
        if ($id_detalle) {
            $sql = "UPDATE carrito_detalle 
                    SET id_producto = ?, cantidad = ?
                    WHERE id_detalle = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("iii", $id_producto, $cantidad, $id_detalle);
        } else {
            $sql = "INSERT INTO carrito_detalle (id_carrito, id_producto, cantidad)
                    VALUES (?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("iii", $id_carrito, $id_producto, $cantidad);
        }
        return $stmt->execute();
    }

    // Eliminar detalle
    public function eliminar(int $id_detalle): bool {
        $stmt = $this->conexion->prepare("DELETE FROM carrito_detalle WHERE id_detalle = ?");
        $stmt->bind_param("i", $id_detalle);
        return $stmt->execute();
    }
}
