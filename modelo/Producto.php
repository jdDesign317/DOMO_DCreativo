<?php
class Producto {
    private mysqli $conexion;

    public function __construct(mysqli $conexion) {
        $this->conexion = $conexion;
    }
    /* 
       LISTAR PRODUCTOS
     */
    public function listar(): array {
        $sql = "SELECT * FROM productos ORDER BY id_producto DESC";
        $res = $this->conexion->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    /* 
       INSERTAR NUEVO PRODUCTO
     */
    public function insertar(string $nombre, string $descripcion, float $precio): bool {
        $sql = "INSERT INTO productos (nombre, descripcion, precio) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            die("Error en prepare: " . $this->conexion->error);
        }
        $stmt->bind_param("ssd", $nombre, $descripcion, $precio);
        return $stmt->execute();
    }

    /* 
       OBTENER PRODUCTO POR ID
     */
    public function obtener(int $id): ?array {
        $sql = "SELECT * FROM productos WHERE id_producto = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            die("Error en prepare: " . $this->conexion->error);
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res ? $res->fetch_assoc() : null;
    }

    /* 
       ACTUALIZAR PRODUCTO
     */
    public function actualizar(int $id, string $nombre, string $descripcion, float $precio): bool {
        $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ? WHERE id_producto = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            die("Error en prepare: " . $this->conexion->error);
        }
        $stmt->bind_param("ssdi", $nombre, $descripcion, $precio, $id);
        return $stmt->execute();
    }

    /* 
       ELIMINAR PRODUCTO
     */
    public function eliminar(int $id): bool {
        $sql = "DELETE FROM productos WHERE id_producto = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            die("Error en prepare: " . $this->conexion->error);
        }
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
