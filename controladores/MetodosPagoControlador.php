<?php
require_once __DIR__ . "/../modelo/MetodoPago.php";

class MetodosPagoControlador {

    private $modelo;

    public function __construct() {
        $this->modelo = new MetodoPago();
    }

    public function listar() {
        $metodos = $this->modelo->listar();
        require_once __DIR__ . "/../vistas/metodos_pago.php";
    }

    public function insertar() {
        if (!empty($_POST["nombre"])) {
            $this->modelo->insertar($_POST["nombre"]);
        }
        header("Location: index.php?controlador=metodos_pago&accion=listar");
        exit;
    }

    public function editar() {
        if (isset($_GET["id"])) {
            $id = (int) $_GET["id"];
            $metodo = $this->modelo->obtener($id);
            require_once __DIR__ . "/../vistas/metodos_pago_editar.php";
        } else {
            header("Location: index.php?controlador=metodos_pago&accion=listar");
            exit;
        }
    }

    public function actualizar() {
        if (!empty($_POST["id"]) && !empty($_POST["nombre"])) {
            $id = (int) $_POST["id"];
            $nombre = $_POST["nombre"];
            $this->modelo->actualizar($id, $nombre);
        }
        header("Location: index.php?controlador=metodos_pago&accion=listar");
        exit;
    }

  public function eliminar() {
    if (isset($_GET["id"])) {
        $id = (int) $_GET["id"];
        $this->modelo->eliminar($id);
    }
    header("Location: index.php?controlador=metodos_pago&accion=listar");
    exit;
}


}
