<?php

class Pedido {
    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("localhost", "root", "", "domo_creativo");

        if ($this->conexion->connect_error) {
            throw new Exception("Error de conexión: " . $this->conexion->connect_error);
        }

        $this->conexion->set_charset("utf8mb4");
    }

    public function listar(): array {
        $sql = "SELECT 
                    p.id_pedido,
                    p.id_presupuesto,
                    p.estado,
                    p.fecha_pedido,
                    p.fecha_entrega,
                    u.nombre AS usuario,
                    pr.total_estimado
                FROM pedidos p
                JOIN presupuestos pr ON p.id_presupuesto = pr.id_presupuesto
                JOIN usuarios u ON pr.id_usuario = u.id_usuario
                ORDER BY p.id_pedido DESC";

        $res = $this->conexion->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function insertar(int $id_presupuesto, string $estado, string $fecha_entrega): bool {
        $sql = "INSERT INTO pedidos (id_presupuesto, estado, fecha_entrega, fecha_pedido)
                VALUES (?, ?, ?, NOW())";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("iss", $id_presupuesto, $estado, $fecha_entrega);

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function obtener(int $id): ?array {
        $stmt = $this->conexion->prepare("SELECT * FROM pedidos WHERE id_pedido = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $res = $stmt->get_result();
        $pedido = $res ? $res->fetch_assoc() : null;

        $stmt->close();
        return $pedido;
    }

    public function actualizar(int $id, string $estado, string $fecha_entrega): bool {
        $sql = "UPDATE pedidos SET estado = ?, fecha_entrega = ? WHERE id_pedido = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssi", $estado, $fecha_entrega, $id);

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function eliminar(int $id): bool {
        $stmt = $this->conexion->prepare("DELETE FROM pedidos WHERE id_pedido = ?");
        $stmt->bind_param("i", $id);

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function __destruct() {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}
