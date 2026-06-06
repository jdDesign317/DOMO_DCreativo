<?php

session_start();


require_once __DIR__ . "/../config/Conexion.php";

$conexionObj = new Conexion();
$conexion = $conexionObj->getConexion();

// VALIDAR POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: ../vistas/auth/forgot.php");
    exit;
}

// OBTENER DATOS
$email = trim($_POST["email"] ?? "");
$codigo = trim($_POST["codigo"] ?? "");

// VALIDAR CAMPOS
if (empty($email) || empty($codigo)) {

    $_SESSION["mensaje_codigo"] = "
        <div class='alert alert-warning text-center'>
            Completa todos los campos.
        </div>
    ";

    header("Location: ../vistas/auth/verificar_codigo.php?email=" . urlencode($email));
    exit;
}

// BUSCAR CODIGO
$sql = "SELECT *
        FROM usuarios
        WHERE email = ?
        AND codigo_recuperacion = ?
        AND codigo_expira > NOW()";

$stmt = $conexion->prepare($sql);

$stmt->bind_param("ss", $email, $codigo);

$stmt->execute();

$resultado = $stmt->get_result();

// VALIDAR CODIGO
if ($resultado->num_rows > 0) {

    $_SESSION["email_recuperacion"] = $email;

    header("Location: ../vistas/auth/reset_password.php");
    exit;

} else {

    $_SESSION["mensaje_codigo"] = "
        <div class='alert alert-danger text-center'>
            Código incorrecto o vencido.
        </div>
    ";

    header("Location: ../vistas/auth/verificar_codigo.php?email=" . urlencode($email));
    exit;
}