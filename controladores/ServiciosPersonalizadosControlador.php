<?php

require_once __DIR__ . "/../modelo/ServiciosPersonalizados.php";

class ServiciosPersonalizadosControlador
{
    private $modelo;

    public function __construct()
{
    $id_perfil = $_SESSION["id_perfil"] ?? null;

    // SOLO ADMIN (2) O DISEÑADOR (3)
    if ($id_perfil != 2 && $id_perfil != 3) {
        header("Location: index.php");
        exit;
    }

    $this->modelo = new ServiciosPersonalizados();
}

    // LISTAR
    public function listar()
    {
        return $this->modelo->listar();
    }

    // VER
    public function ver($id)
    {
        return $this->modelo->buscarPorId($id);
    }

    // CREAR
    public function crear(
        $id_usuario,
        $id_producto,
        $color,
        $texto_personalizado,
        $archivo_diseno
    )
    {
        return $this->modelo->crear(
            $id_usuario,
            $id_producto,
            $color,
            $texto_personalizado,
            $archivo_diseno
        );
    }

    // EDITAR
    public function editar(
        $id_servicio_personalizado,
        $id_producto,
        $color,
        $texto_personalizado,
        $archivo_diseno,
        $estado
    )
    {
        return $this->modelo->actualizar(
            $id_servicio_personalizado,
            $id_producto,
            $color,
            $texto_personalizado,
            $archivo_diseno,
            $estado
        );
    }

    // ELIMINAR
    public function eliminar($id)
    {
        return $this->modelo->eliminar($id);
    }
}