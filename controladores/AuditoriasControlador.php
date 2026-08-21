<?php

require_once __DIR__ . "/../modelo/Auditoria.php";

class AuditoriasControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new Auditoria();
    }

    // LISTAR 
    public function listar() {
        return $this->modelo->listar();
    }
}