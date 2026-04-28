
   <?php

class Carrito {
    private $conexion;

    

    public function __construct($conexion = null) {
        if ($conexion) {
            $this->conexion = $conexion;
        } else {
            require_once __DIR__ . "/conexion.php";
            $conn = new Conexion();
            $this->conexion = $conn->getConexion(); // MySQLi
        }
    }

    //  Crear carrito
    public function crearCarrito($id_usuario) {
        $stmt = $this->conexion->prepare("INSERT INTO carrito (id_usuario, estado) VALUES (?, 'activo')");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        return $this->conexion->insert_id;
    }

    //  Obtener carrito activo
    public function obtenerCarritoActivo($id_usuario) {
        $stmt = $this->conexion->prepare("SELECT id_carrito FROM carrito WHERE id_usuario = ? AND estado = 'activo' LIMIT 1");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            return $row["id_carrito"];
        }

        return null;
    }

    //  Buscar carrito por ID
    public function buscarPorId($id_carrito) {
        $stmt = $this->conexion->prepare("SELECT * FROM carrito WHERE id_carrito = ?");
        $stmt->bind_param("i", $id_carrito);
        $stmt->execute();

        $res = $stmt->get_result();
        return $res->fetch_assoc() ?: null;
    }

    //  Listar todos
    public function listarTodos() {
    $res = $this->conexion->query("
        SELECT * 
        FROM carrito 
        WHERE estado = 'activo' 
        ORDER BY id_carrito DESC
    ");
    return $res->fetch_all(MYSQLI_ASSOC);
}

    //  Agregar producto
    public function agregarProducto($id_carrito, $id_producto, $cantidad = 1) {
        $stmt = $this->conexion->prepare("
            INSERT INTO carrito_detalle (id_carrito, id_producto, cantidad)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)
        ");

        $stmt->bind_param("iii", $id_carrito, $id_producto, $cantidad);
        return $stmt->execute();
    }

    //  Listar detalles
    public function listarDetalles($id_carrito) {
        $stmt = $this->conexion->prepare("
            SELECT d.id_detalle, d.id_producto, d.cantidad,
                   p.nombre, p.descripcion, p.precio
            FROM carrito_detalle d
            JOIN productos p ON d.id_producto = p.id_producto
            WHERE d.id_carrito = ?
        ");

        $stmt->bind_param("i", $id_carrito);
        $stmt->execute();

        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    //  Actualizar cantidad
    public function actualizarCantidad($id_detalle, $cantidad) {
        $stmt = $this->conexion->prepare("
            UPDATE carrito_detalle 
            SET cantidad = ? 
            WHERE id_detalle = ?
        ");

        $stmt->bind_param("ii", $cantidad, $id_detalle);
        return $stmt->execute();
    }

    // Quitar producto
    public function quitar($id_detalle) {
        $stmt = $this->conexion->prepare("DELETE FROM carrito_detalle WHERE id_detalle = ?");
        $stmt->bind_param("i", $id_detalle);
        return $stmt->execute();
    }

    //  Vaciar carrito
    public function vaciar($id_carrito) {
        $stmt = $this->conexion->prepare("DELETE FROM carrito_detalle WHERE id_carrito = ?");
        $stmt->bind_param("i", $id_carrito);
        return $stmt->execute();
    }

    //  Cerrar carrito
    public function cerrar($id_carrito) {
        $stmt = $this->conexion->prepare("UPDATE carrito SET estado = 'cerrado' WHERE id_carrito = ?");
        $stmt->bind_param("i", $id_carrito);
        return $stmt->execute();
    }
}