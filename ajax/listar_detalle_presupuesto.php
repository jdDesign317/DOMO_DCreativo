<?php
require_once "../controladores/DetallePresupuestosControlador.php";

header("Content-Type: application/json");

if (!isset($_GET["id"])) {
    echo json_encode([]);
    exit;
}

$ctrl = new DetallePresupuestosControlador();
$data = $ctrl->listarPorPresupuesto($_GET["id"]);

echo json_encode($data);
