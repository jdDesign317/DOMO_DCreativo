<?php

class Conexion {
    private string $host = "localhost";
    private string $usuario = "root";
    private string $password = "";
    private string $base_datos = "domo_creativo26";

    private mysqli $conexion;

    public function __construct() {
        $this->conexion = new mysqli(
            $this->host,
            $this->usuario,
            $this->password,
            $this->base_datos
        );

        // Verificar conexión
        if ($this->conexion->connect_error) {
            throw new Exception("Error de conexión: " . $this->conexion->connect_error);
        }

        // Configurar charset
        $this->conexion->set_charset("utf8mb4");
    }

    /**
     * Retorna la conexión activa a la base de datos
     */
    public function getConexion(): mysqli {
        return $this->conexion;
    }
}