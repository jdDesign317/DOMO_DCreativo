<?php

class Auth {

    // VERIFICAR SI HAY SESIÓN ACTIVA
    public static function verificarSesion() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // SI NO EXISTE SESIÓN
        if (!isset($_SESSION["usuario"])) {

           
            header("Location: /DOMOCreativo/vistas/auth/login.php");
            exit;
        }
    }

    // OBTENER USUARIO ACTUAL
    public static function usuario() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION["usuario"] ?? null;
    }

    // VALIDAR PERFIL
    public static function validarPerfil($perfilPermitido) {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // SI NO HAY SESIÓN
        if (!isset($_SESSION["usuario"])) {

            header("Location: ../auth/login.php");
            exit;
        }

        // SI EL PERFIL NO COINCIDE
        if ($_SESSION["usuario"]["perfil"] !== $perfilPermitido) {

            header("Location: ../dashboard.php");
            exit;
        }
    }

    // CERRAR SESIÓN
    public static function logout() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();

        header("Location: ../auth/login.php");
        exit;
    }
}