<?php

require_once "../modelo/conexion.php";

require_once __DIR__ . "/../librerias/PHPMailer/src/PHPMailer.php";
require_once __DIR__ . "/../librerias/PHPMailer/src/SMTP.php";
require_once __DIR__ . "/../librerias/PHPMailer/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conexionObj = new Conexion();
$conexion = $conexionObj->getConexion();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);

    // Verificar si el usuario existe
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        // Generar código de 6 dígitos
        $codigo = rand(100000, 999999);

        // Guardar código y expiración (15 minutos)
        $sql_update = "UPDATE usuarios 
                       SET codigo_recuperacion = ?, 
                           codigo_expira = DATE_ADD(NOW(), INTERVAL 15 MINUTE)
                       WHERE email = ?";

        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param("ss", $codigo, $email);
        $stmt_update->execute();

        // Enviar correo
        $mail = new PHPMailer(true);

        $mail->SMTPDebug = 0;
        $mail->Timeout = 10;

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            // 🔑 TU CUENTA GMAIL (la que envía)
            $mail->Username = 'janniidavals@gmail.com';
            $mail->Password = 'swjyfhvxsmxy rlxu'; // reemplazar por contraseña de aplicación

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('jannidavals@gmail.com', 'Recuperación de contraseña');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Código de recuperación';
            $mail->Body = "<h3>Tu código de recuperación es: <b>$codigo</b></h3>";

            // Enviar
            if (!$mail->send()) {
                echo "Error al enviar: " . $mail->ErrorInfo;
                exit;
            }

            // Si todo salió bien, redirigir
            header("Location: verificar_codigo.php?email=" . urlencode($email));
            exit;

        } catch (Exception $e) {
            echo "Error al enviar: " . $mail->ErrorInfo;
        }

    } else {
        echo "<div class='alert alert-danger text-center'>El correo no existe.</div>";
    }
}