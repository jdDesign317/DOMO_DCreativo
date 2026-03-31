<?php
require_once __DIR__ . "/../modelo/CarritoDetalle.php";

class CarritoDetalleControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new CarritoDetalle();
    }

    // Listar detalles de un carrito
    public function listarPorCarrito($id_carrito) {
        return $this->modelo->listarPorCarrito((int)$id_carrito);
    }

    // Guardar detalles (desde AJAX)
    public function guardar() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            echo json_encode(["error" => "Datos inválidos"]);
            return;
        }

        $id_carrito = (int)$data["id_carrito"];
        foreach ($data["detalles"] as $detalle) {
            $id_detalle  = !empty($detalle["id_detalle"]) ? (int)$detalle["id_detalle"] : null;
            $id_producto = (int)$detalle["id_producto"];
            $cantidad    = (int)$detalle["cantidad"];

            $this->modelo->guardar($id_carrito, $id_producto, $cantidad, $id_detalle);
        }

        echo json_encode(["success" => true]);
    }

    // Eliminar detalle
    public function eliminar($id_detalle) {
        return $this->modelo->eliminar((int)$id_detalle);
    }
}

// Si se llama directamente vía AJAX
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ctrl = new CarritoDetalleControlador();
    $ctrl->guardar();
}
