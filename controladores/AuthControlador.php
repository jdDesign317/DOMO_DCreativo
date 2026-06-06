<?php

session_start();

require_once __DIR__ . "/../modelo/usuarios.php";

class AuthControlador {

    private $modelo;

    // CONSTRUCTOR
    public function __construct() {
        $this->modelo = new Usuarios();
    }

    // LOGIN
    public function login($email, $password) {

        $email = strtolower(trim($email));
        $password = trim($password);

        if (empty($email) || empty($password)) {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $usuario = $this->modelo->verificarCredenciales($email, $password);

        if ($usuario) {

            $_SESSION["id_usuario"] = $usuario["id_usuario"];
            $_SESSION["nombre"] = $usuario["nombre"];
            $_SESSION["email"] = $usuario["email"];
            $_SESSION["perfil"] = $usuario["perfil"];

            return $usuario;
        }

        return false;
    }

    // RECUPERAR CONTRASEÑA (SIMULADO)
    public function forgot($email) {

        $email = strtolower(trim($email));

        if (empty($email)) {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $usuario = $this->modelo->buscarPorEmail($email);

        if (!$usuario) {
            return false;
        }

        $_SESSION["email_recuperacion"] = $email;

        return true;
    }

    // LOGOUT
    public function logout() {

        session_destroy();

        header("Location: ../vistas/auth/login.php");
        exit;
    }
}