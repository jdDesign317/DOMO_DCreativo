<?php
class Carrito {
    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("localhost", "root", "", "domo_creativo");
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
        $this->conexion->set_charset("utf8mb4");
    }

    // Obtener carrito activo de un usuario, o crear uno nuevo si no existe
    public function obtenerCarritoActivo(int $id_usuario): ?int {
        $sql = "SELECT id_carrito FROM carrito WHERE id_usuario = ? AND estado = 'activo' LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            return $row["id_carrito"];
        }
        $stmt = $this->conexion->prepare("INSERT INTO carrito (id_usuario, estado) VALUES (?, 'activo')");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Buscar carrito por ID
    public function buscarPorId(int $id_carrito): ?array {
        $sql = "SELECT * FROM carrito WHERE id_carrito = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_carrito);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc() ?: null;
    }

    // Listar todos los carritos
    public function listarTodos(): array {
        $sql = "SELECT * FROM carrito ORDER BY fecha DESC";
        $res = $this->conexion->query($sql);
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    // Agregar producto al carrito
    public function agregarProducto(int $id_carrito, int $id_producto, int $cantidad = 1): bool {
        $sql = "INSERT INTO carrito_detalle (id_carrito, id_producto, cantidad)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("iii", $id_carrito, $id_producto, $cantidad);
        return $stmt->execute();
    }

    // Listar detalles de un carrito
    public function listarDetalles(int $id_carrito): array {
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

    // Actualizar cantidad de un producto en el carrito
    public function actualizarCantidad(int $id_detalle, int $cantidad): bool {
        $sql = "UPDATE carrito_detalle SET cantidad = ? WHERE id_detalle = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $cantidad, $id_detalle);
        return $stmt->execute();
    }

    // Quitar un producto del carrito
    public function quitar(int $id_detalle): bool {
        $stmt = $this->conexion->prepare("DELETE FROM carrito_detalle WHERE id_detalle = ?");
        $stmt->bind_param("i", $id_detalle);
        return $stmt->execute();
    }

    // Vaciar carrito completo
    public function vaciar(int $id_carrito): bool {
        $stmt = $this->conexion->prepare("DELETE FROM carrito_detalle WHERE id_carrito = ?");
        $stmt->bind_param("i", $id_carrito);
        return $stmt->execute();
    }

    // Cerrar carrito
    public function cerrar(int $id_carrito): bool {
        $stmt = $this->conexion->prepare("UPDATE carrito SET estado = 'cerrado' WHERE id_carrito = ?");
        $stmt->bind_param("i", $id_carrito);
        return $stmt->execute();
    }
}
