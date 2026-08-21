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
    public function crear($nombre, $apellidos, $telefono, $email, $localidad, $password, $id_perfil) {

        $nombre    = strtolower(trim($nombre));
        $apellidos = strtolower(trim($apellidos));
        $email     = strtolower(trim($email));
        $localidad = trim($localidad); // VARCHAR, no FK

        if (empty($nombre) || empty($email) || empty($password) || empty($id_perfil)) {
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
            $localidad,
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

        // BUSCAR USUARIO POR EMAIL
        $usuario = $this->modelo->buscarPorEmail($email);

        if (!$usuario) {
            return false;
        }

        // VERIFICAR PASSWORD AQUI EN EL CONTROLADOR
        if (!password_verify($password, $usuario["password_hash"])) {
            return false;
        }

        return $usuario;
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
    public function actualizar($id_usuario, $nombre, $apellidos, $telefono, $email, $localidad, $password, $id_perfil) {

        $nombre    = strtolower(trim($nombre));
        $apellidos = strtolower(trim($apellidos));
        $email     = strtolower(trim($email));
        $localidad = trim($localidad); // VARCHAR, no FK

        if (empty($id_usuario) || empty($nombre) || empty($apellidos) || empty($email) || empty($id_perfil)) {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if (empty($password)) {
            $usuario = $this->modelo->buscarPorId($id_usuario);
            if (!$usuario) return false;
            $password_hash = $usuario["password_hash"];
        } else {
            if (strlen($password) < 6) {
                return false;
            }
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
        }

        return $this->modelo->actualizar(
            $id_usuario,
            $nombre,
            $apellidos,
            $telefono,
            $email,
            $localidad,
            $password_hash,
            $id_perfil
        );
    }

    // ELIMINAR (BAJA LOGICA)
    public function eliminar($id_usuario) {

        if (empty($id_usuario)) {
            return false;
        }

        return $this->modelo->eliminar($id_usuario);
    }

    // REACTIVAR — recibe el id como parametro, sin tocar $_GET ni hacer redirect
    public function reactivar($id_usuario) {

        if (empty($id_usuario)) {
            return false;
        }

        return $this->modelo->reactivarUsuario($id_usuario);
    }
}