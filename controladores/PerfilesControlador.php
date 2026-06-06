<?php

require_once __DIR__ . "/../modelo/Perfil.php";

class PerfilesControlador
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new Perfil();
    }

    // LISTAR PERFILES
    public function listar()
    {
        return $this->modelo->listar();
    }

    // CREAR PERFIL
    public function insertar($nombre)
    {
        $nombre = strtolower(trim($nombre));

        if (empty($nombre)) {
            return false;
        }

        return $this->modelo->insertar($nombre);
    }

    // BUSCAR PERFIL POR ID
    public function obtener($id_perfil)
    {
        if (empty($id_perfil)) {
            return false;
        }

        return $this->modelo->obtener($id_perfil);
    }

    // ACTUALIZAR PERFIL
    public function actualizar($id_perfil, $nombre)
    {
        $nombre = strtolower(trim($nombre));

        if (
            empty($id_perfil) ||
            empty($nombre)
        ) {
            return false;
        }

        return $this->modelo->actualizar(
            $id_perfil,
            $nombre
        );
    }

    // ELIMINAR PERFIL
    public function eliminar($id_perfil)
    {
        if (empty($id_perfil)) {
            return false;
        }

        return $this->modelo->eliminar($id_perfil);
    }
}