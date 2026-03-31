<?php
require_once "../controladores/DetallePresupuestosControlador.php";

header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input["id_presupuesto"])) {
    echo json_encode(["ok"=>false, "error"=>"Datos no válidos"]);
    exit;
}

$id_presupuesto = $input["id_presupuesto"];
$items = $input["items"];

$ctrl = new DetallePresupuestosControlador();

// eliminar todo lo anterior y volver a insertar
$ctrl->eliminarPorPresupuesto($id_presupuesto);

foreach ($items as $i) {
    if (!$i["id_producto"]) continue;
    $ctrl->insertar(
        $id_presupuesto,
        $i["id_producto"],
        $i["cantidad"],
        $i["precio_unitario"]
    );
}

echo json_encode(["ok"=>true]);
