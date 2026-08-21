<?php

require_once __DIR__ . "/../config/Conexion.php";

class Usuarios {

    private $conexion;

    public function __construct() {
        $this->conexion = (new Conexion())->getConexion();
    }

    // BUSCAR POR EMAIL
    public function buscarPorEmail($email) {

        $email = trim($email);

        $sql = "SELECT * FROM usuarios 
                WHERE email = ? 
                AND estado = 'activo'";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // BUSCAR POR ID
    public function buscarPorId($id_usuario) {

        $sql = "SELECT usuarios.*, 
                       perfiles.nombre AS perfil
                FROM usuarios
                LEFT JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil
                WHERE usuarios.id_usuario = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // CREAR USUARIO
    public function crear($nombre, $apellidos, $telefono, $email, $localidad, $password_hash, $id_perfil) {

        $nombre    = strtolower(trim($nombre));
        $apellidos = strtolower(trim($apellidos));
        $email     = strtolower(trim($email));
        $localidad = trim($localidad);

        $sql = "INSERT INTO usuarios 
                (nombre, apellidos, telefono, email, localidad, password_hash, id_perfil, estado)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'activo')";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param(
            "ssssssi",
            $nombre,
            $apellidos,
            $telefono,
            $email,
            $localidad,
            $password_hash,
            $id_perfil
        );

        return $stmt->execute();
    }

    // LISTAR USUARIOS ACTIVOS
    public function listar() {

        $sql = "SELECT usuarios.*, 
                       perfiles.nombre AS perfil
                FROM usuarios
                LEFT JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil
                WHERE usuarios.estado = 'activo'";

        $resultado = $this->conexion->query($sql);

        $usuarios = [];

        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = $fila;
        }

        return $usuarios;
    }

    // LISTAR POR ESTADO
    public function listarPorEstado($estado) {

        $sql = "SELECT usuarios.*, 
                       perfiles.nombre AS perfil
                FROM usuarios
                LEFT JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil
                WHERE usuarios.estado = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $estado);
        $stmt->execute();

        $resultado = $stmt->get_result();

        $usuarios = [];

        while ($fila = $resultado->fetch_assoc()) {
            $usuarios[] = $fila;
        }

        return $usuarios;
    }

    // ACTUALIZAR USUARIO
    public function actualizar($id_usuario, $nombre, $apellidos, $telefono, $email, $localidad, $password_hash, $id_perfil) {

        $nombre    = strtolower(trim($nombre));
        $apellidos = strtolower(trim($apellidos));
        $email     = strtolower(trim($email));
        $localidad = trim($localidad);

        $sql = "UPDATE usuarios SET
                nombre        = ?,
                apellidos     = ?,
                telefono      = ?,
                email         = ?,
                localidad     = ?,
                password_hash = ?,
                id_perfil     = ?
                WHERE id_usuario = ?";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param(
            "ssssssii",
            $nombre,
            $apellidos,
            $telefono,
            $email,
            $localidad,
            $password_hash,
            $id_perfil,
            $id_usuario
        );

        return $stmt->execute();
    }

    // BAJA LOGICA
    public function eliminar($id_usuario) {

        $sql = "UPDATE usuarios 
                SET estado = 'inactivo' 
                WHERE id_usuario = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);

        return $stmt->execute();
    }

    // REACTIVAR USUARIO
    public function reactivarUsuario($id_usuario) {

        $sql = "UPDATE usuarios 
                SET estado = 'activo' 
                WHERE id_usuario = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);

        return $stmt->execute();
    }
}