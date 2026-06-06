<?php

require_once __DIR__ . "/../modelo/usuarios.php";

class UsuariosControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new Usuarios();
    }

    // LISTAR
    public function listar() {
        return $this->modelo->listar();
    }

    // CREAR USUARIO
    public function crear($nombre, $apellidos, $telefono, $email, $password, $id_perfil) {

        $nombre = strtolower(trim($nombre));
        $apellidos = strtolower(trim($apellidos));
        $email = strtolower(trim($email));

        // VALIDACIONES
        if (
            empty($nombre) ||
            empty($email) ||
            empty($password) ||
            empty($id_perfil)
        ) {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if (strlen($password) < 6) {
            return false;
        }

        if ($this->modelo->buscarPorEmail($email)) {
            return false;
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        return $this->modelo->crear(
            $nombre,
            $apellidos,
            $telefono,
            $email,
            $password_hash,
            $id_perfil
        );
    }

    // LOGIN
    public function verificarCredenciales($email, $password) {

        $email = strtolower(trim($email));

        if (empty($email) || empty($password)) {
            return false;
        }

        return $this->modelo->login($email, $password);
    }

    // BUSCAR POR EMAIL
    public function buscarPorEmail($email) {
        return $this->modelo->buscarPorEmail($email);
    }

    // BUSCAR POR ID
    public function buscarPorId($id_usuario) {

        if (empty($id_usuario)) {
            return false;
        }

        return $this->modelo->buscarPorId($id_usuario);
    }

    // ACTUALIZAR USUARIO
    public function actualizar($id_usuario, $nombre, $apellidos, $telefono, $email, $password, $id_perfil) {

        $nombre = strtolower(trim($nombre));
        $apellidos = strtolower(trim($apellidos));
        $email = strtolower(trim($email));

        if (
            empty($id_usuario) ||
            empty($nombre) ||
            empty($apellidos) ||
            empty($email) ||
            empty($id_perfil)
        ) {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // PASSWORD
        if (empty($password)) {

            
            $usuario = $this->modelo->buscarPorId($id_usuario);

            if (!$usuario) {
                return false;
            }

            $password_hash = $usuario["password_hash"];

        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
        }

        return $this->modelo->actualizar(
            $id_usuario,
            $nombre,
            $apellidos,
            $telefono,
            $email,
            $password_hash,
            $id_perfil
        );
    }

    // ELIMINAR (BAJA LÓGICA)
    public function eliminar($id_usuario) {

        if (empty($id_usuario)) {
            return false;
        }

        return $this->modelo->eliminar($id_usuario);
    }

    // REACTIVAR USUARIO
    public function reactivar() {

        if (!isset($_GET["id"]) || empty($_GET["id"])) {
            return false;
        }

        $id_usuario = $_GET["id"];

        $this->modelo->reactivarUsuario($id_usuario);

        header("Location: ../vistas/usuarios/usuarios_listar.php");
        exit;
    }
}