<?php
require_once "Conexion.php";

class Usuarios
{
    private $conexion;
    private $tabla = "usuarios";

    public function __construct()
    {
        $conexionObj = new Conexion();
        $this->conexion = $conexionObj->getConexion();
    }

    /* 
       LISTAR TODOS LOS USUARIOS
     */
    public function listar(): array {
        $sql = "SELECT u.id_usuario, u.nombre, u.email, p.nombre AS perfil, u.id_perfil
                FROM usuarios u
                LEFT JOIN perfiles p ON u.id_perfil = p.id_perfil
                ORDER BY u.id_usuario DESC";
        $res = $this->conexion->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    /* 
       BUSCAR POR ID
     */
    public function buscarPorId($id_usuario) {
        $sql = "SELECT u.id_usuario, u.nombre, u.email, u.password_hash, u.id_perfil, p.nombre AS perfil
                FROM usuarios u
                LEFT JOIN perfiles p ON u.id_perfil = p.id_perfil
                WHERE u.id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    /* 
       CREAR USUARIO
     */
    public function crear($nombre, $email, $password_hash, $id_perfil) {
        $sql = "INSERT INTO usuarios (nombre, email, password_hash, id_perfil)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssi", $nombre, $email, $password_hash, $id_perfil);
        return $stmt->execute();
    }

    /* 
       ACTUALIZAR USUARIO
     */
    public function actualizar($id_usuario, $nombre, $email, $password_hash, $id_perfil) {
        $sql = "UPDATE usuarios 
                SET nombre = ?, email = ?, password_hash = ?, id_perfil = ?
                WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sssii", $nombre, $email, $password_hash, $id_perfil, $id_usuario);
        return $stmt->execute();
    }

    /* 
       ELIMINAR USUARIO
     */
    public function eliminar($id_usuario) {
        $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        return $stmt->execute();
    }

    /* 
       BUSCAR POR EMAIL
    */
    public function buscarPorEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    /* 
       VERIFICAR CREDENCIALES (LOGIN)
     */
    public function verificarCredenciales($email, $password) {
        $sql = "SELECT id_usuario, nombre, email, password_hash, id_perfil 
                FROM usuarios 
                WHERE email = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($row = $resultado->fetch_assoc()) {
            if (password_verify($password, $row["password_hash"])) {
                return [
                    "id_usuario" => $row["id_usuario"],
                    "nombre"     => $row["nombre"],
                    "email"      => $row["email"],
                    "perfil"     => $row["id_perfil"]
                ];
            }
        }
        return null;
    }
}
?>
