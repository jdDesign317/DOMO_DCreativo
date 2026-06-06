<?php

session_start();


require_once __DIR__ . "/../config/Conexion.php";

$conexionObj = new Conexion();
$conexion = $conexionObj->getConexion();

// VALIDAR METODO
if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: ../vistas/auth/login.php");
    exit;
}

// OBTENER EMAIL
$email = $_SESSION["email_recuperacion"] ?? "";

// OBTENER PASSWORD
$password = trim($_POST["password"] ?? "");

// VALIDAR PASSWORD
if (empty($password)) {

    $_SESSION["mensaje_reset"] = "
        <div class='alert alert-warning text-center'>
            Ingresá una contraseña.
        </div>
    ";

    header("Location: ../vistas/auth/reset_password.php");
    exit;
}

// HASH PASSWORD
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// ACTUALIZAR PASSWORD
$sql = "UPDATE usuarios
        SET password_hash = ?,
            codigo_recuperacion = NULL,
            codigo_expira = NULL
        WHERE email = ?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "ss",
    $password_hash,
    $email
);

$resultado = $stmt->execute();

// VALIDAR ACTUALIZACION
if ($resultado) {

    unset($_SESSION["email_recuperacion"]);

    $_SESSION["mensaje_login"] = "
        <div class='alert alert-success text-center'>
            Contraseña actualizada correctamente.
        </div>
    ";

    header("Location: ../vistas/auth/login.php");
    exit;

} else {

    $_SESSION["mensaje_reset"] = "
        <div class='alert alert-danger text-center'>
            Error al actualizar contraseña.
        </div>
    ";

    header("Location: ../vistas/auth/reset_password.php");
    exit;
}