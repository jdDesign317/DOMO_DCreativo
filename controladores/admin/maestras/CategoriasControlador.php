<?php

require_once __DIR__ . '/../../../modelo/admin/maestras/Categorias.php';

class CategoriasControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new Categorias();
    }

    // LISTAR SOLO ACTIVAS
    public function listar() {
        return $this->modelo->listar();
    }

    // LISTAR TODAS (para panel admin)
    public function listarTodas() {
        return $this->modelo->listarTodas();
    }

    // BUSCAR POR ID
    public function buscarPorId($id_categoria) {

        if (empty($id_categoria)) {
            return false;
        }

        return $this->modelo->buscarPorId($id_categoria);
    }

    // CREAR
    public function crear($nombre) {

        $nombre = strtolower(trim($nombre));

        if (empty($nombre)) return "vacio";

        if ($this->modelo->existeNombre($nombre)) return "duplicado";

        return $this->modelo->crear($nombre);
    }

    // ACTUALIZAR
    public function actualizar($id_categoria, $nombre) {

        $nombre = strtolower(trim($nombre));

        if (empty($id_categoria) || empty($nombre)) return "vacio";

        if ($this->modelo->existeNombreParaOtro($nombre, $id_categoria)) return "duplicado";

        return $this->modelo->actualizar($id_categoria, $nombre);
    }

    // VALIDAR NOMBRE DUPLICADO (crear)
    public function existeNombre($nombre) {
        return $this->modelo->existeNombre($nombre);
    }

    // VALIDAR NOMBRE DUPLICADO EN OTRO (editar)
    public function existeNombreParaOtro($nombre, $id_categoria) {
        return $this->modelo->existeNombreParaOtro($nombre, $id_categoria);
    }

    // BAJA LOGICA
    public function desactivar($id_categoria) {

        if (empty($id_categoria)) {
            return false;
        }

        return $this->modelo->desactivar($id_categoria);
    }

    // REACTIVAR
    public function reactivar($id_categoria) {

        if (empty($id_categoria)) {
            return false;
        }

        return $this->modelo->reactivar($id_categoria);
    }
}
