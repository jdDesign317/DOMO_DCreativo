<?php

require_once "Conexion.php";

class Pedido {
    private $conexion;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->getConexion();
    }

 
public function listar(): array {
    $sql = "SELECT 
                pedidos.id_pedido,
                pedidos.id_presupuesto,
                pedidos.estado,
                pedidos.fecha_pedido,
                pedidos.fecha_entrega,
                usuarios.nombre AS usuario,
                presupuestos.total_estimado
            FROM pedidos
            JOIN presupuestos 
                ON pedidos.id_presupuesto = presupuestos.id_presupuesto
            JOIN usuarios 
                ON presupuestos.id_usuario = usuarios.id_usuario
            ORDER BY pedidos.id_pedido DESC";

    $resultado = $this->conexion->query($sql);

    return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
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