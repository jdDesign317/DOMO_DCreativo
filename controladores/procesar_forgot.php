<?php

session_start();


require_once __DIR__ . "/../config/Conexion.php";

require_once __DIR__ . "/../librerias/PHPMailer/src/PHPMailer.php";
require_once __DIR__ . "/../librerias/PHPMailer/src/SMTP.php";
require_once __DIR__ . "/../librerias/PHPMailer/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// CONEXION
$conexionObj = new Conexion();
$conexion = $conexionObj->getConexion();

// VALIDAR POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: ../vistas/auth/forgot.php");
    exit;
}

// OBTENER EMAIL
$email = trim($_POST["email"] ?? "");

// VALIDAR EMAIL
if (empty($email)) {

    $_SESSION["mensaje"] = "
        <div class='alert alert-warning text-center'>
            Ingresá un correo electrónico.
        </div>
    ";

    header("Location: ../vistas/auth/forgot.php");
    exit;
}

// BUSCAR USUARIO
$sql = "SELECT * FROM usuarios WHERE email = ?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param("s", $email);

$stmt->execute();

$resultado = $stmt->get_result();

// VALIDAR EXISTENCIA
if ($resultado->num_rows === 0) {

    $_SESSION["mensaje"] = "
        <div class='alert alert-danger text-center'>
            El correo no existe.
        </div>
    ";

    header("Location: ../vistas/auth/forgot.php");
    exit;
}

// GENERAR CODIGO
$codigo = rand(100000, 999999);

// GUARDAR CODIGO
$sqlUpdate = "UPDATE usuarios
              SET codigo_recuperacion = ?,
                  codigo_expira = DATE_ADD(NOW(), INTERVAL 15 MINUTE)
              WHERE email = ?";

$stmtUpdate = $conexion->prepare($sqlUpdate);

$stmtUpdate->bind_param("ss", $codigo, $email);

$stmtUpdate->execute();

// ENVIAR CORREO
$mail = new PHPMailer(true);

try {

    $mail->isSMTP();

    $mail->Host = "smtp.gmail.com";

    $mail->SMTPAuth = true;

    // TU GMAIL
    $mail->Username = "janniidavals@gmail.com";

    // APP PASSWORD
    $mail->Password = "xgpruvyoowfvhcnq";

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    $mail->Port = 587;

    // DEBUG
    // $mail->SMTPDebug = 2;

    $mail->setFrom(
        "janniidavals@gmail.com",
        "DOMOCreativo"
    );

    $mail->addAddress($email);

    $mail->isHTML(true);

    $mail->Subject = "Codigo de recuperacion";

    $mail->Body = "
        <h2>DOMOCreativo</h2>

        <p>Tu codigo de recuperacion es:</p>

        <h1>$codigo</h1>

        <p>Este codigo vence en 15 minutos.</p>
    ";

    $mail->send();

    // REDIRECCION
    header(
        "Location: ../vistas/auth/verificar_codigo.php?email=" .
        urlencode($email)
    );

    exit;

} catch (Exception $e) {

    $_SESSION["mensaje"] = "
        <div class='alert alert-danger text-center'>
            Error al enviar el correo.<br>
            {$mail->ErrorInfo}
        </div>
    ";

    header("Location: ../vistas/auth/forgot.php");

    exit;
}