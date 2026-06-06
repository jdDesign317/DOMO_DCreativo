<?php

require_once "config/conexion.php";

// CONEXION A LA BASE
$conexion = new Conexion();
$db = $conexion->getConexion();

// DATOS DEL ADMIN
$nombre = "Admin";
$email = "admin@mail.com";
$password = password_hash("123456", PASSWORD_DEFAULT);
$id_perfil = 2;

// CONSULTA SQL
$sql = "INSERT INTO usuarios (nombre, email, password_hash, id_perfil)
        VALUES (?, ?, ?, ?)";

// PREPARAR CONSULTA
$stmt = $db->prepare($sql);

// ENVIAR DATOS
$stmt->bind_param("sssi", $nombre, $email, $password, $id_perfil);

// EJECUTAR
$stmt->execute();

echo "Admin creado OK";