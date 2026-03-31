<?php
require_once __DIR__ . "/../modelo/Usuarios.php";

class UsuariosControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new Usuarios();
    }

    /* 
       LISTAR TODOS LOS USUARIOS
    */
    public function listar() {
        return $this->modelo->listar();
    }

    /* 
       CREAR USUARIO
   */
    public function crear($nombre, $email, $password_hash, $id_perfil) {
        return $this->modelo->crear($nombre, $email, $password_hash, $id_perfil);
    }

    /* 
       ACTUALIZAR USUARIO
    */
    public function actualizar($id_usuario, $nombre, $email, $password_hash, $id_perfil) {
        return $this->modelo->actualizar($id_usuario, $nombre, $email, $password_hash, $id_perfil);
    }

    /* 
       ELIMINAR USUARIO
     */
    public function eliminar($id) {
        return $this->modelo->eliminar($id);
    }

    /* 
       VERIFICAR CREDENCIALES (LOGIN)
     */
    public function verificarCredenciales($email, $password) {
        // Usamos directamente el método del modelo
        return $this->modelo->verificarCredenciales($email, $password);
    }

    /* 
       VER USUARIO POR ID
 */
    public function ver($id) {
        return $this->modelo->buscarPorId($id);
    }
}
