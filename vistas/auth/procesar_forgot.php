<?php

session_start();

require_once __DIR__ . "/../../controladores/UsuariosControlador.php";

$usuariosControlador = new UsuariosControlador();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");

    // VALIDAR EMAIL
    if (empty($email)) {

        $_SESSION["mensaje"] = "El email es obligatorio";
        header("Location: ../vistas/auth/forgot.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $_SESSION["mensaje"] = "Email inválido";
        header("Location: ../vistas/auth/forgot.php");
        exit;
    }

    // BUSCAR USUARIO
    $usuario = $usuariosControlador->buscarPorEmail($email);

    // SI EXISTE
    if ($usuario) {

        // GENERAR CÓDIGO
        $codigo = rand(100000, 999999);

        // GUARDAR EN BASE DE DATOS
        $usuariosControlador->guardarCodigoRecuperacion($email, $codigo);

        // REDIRIGIR A VERIFICACIÓN
        header("Location: ../vistas/auth/verificar_codigo.php?email=" . urlencode($email));
        exit;
    }

    // MISMO MENSAJE (SEGURIDAD)
    $_SESSION["mensaje"] = "Si el email existe, recibirás instrucciones";

    header("Location: ../vistas/auth/forgot.php");
    exit;
}