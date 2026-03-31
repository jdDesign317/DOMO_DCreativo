<?php
require_once "../controladores/MensajesControlador.php";

$mensajesController = new MensajesControlador();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_usuario = intval($_POST["id_usuario"]);
    $remitente  = trim($_POST["remitente"]);
    $mensaje    = trim($_POST["mensaje"]);
    $fecha      = date("Y-m-d H:i:s");

    $ok = $mensajesController->insertar($id_usuario, $remitente, $mensaje, $fecha);
    echo json_encode(["success" => $ok]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $mensajes = $mensajesController->listar();
    echo json_encode($mensajes);
    exit;
}
