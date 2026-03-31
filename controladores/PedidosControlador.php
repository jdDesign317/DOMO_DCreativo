<?php
require_once __DIR__ . "/../modelo/Pedidos.php";

if (!defined("BASE_URL")) {
    define("BASE_URL", "/plataforma_domo/");
}

class PedidosControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new Pedido();
    }

    /* 
       LISTAR PEDIDOS
     */
    public function listar() {
        $pedidos = $this->modelo->listar();
        require_once __DIR__ . "/../vistas/pedidos_listar.php";
    }

    /* 
       FORMULARIO CREAR
    */
    public function crear() {
        require_once __DIR__ . "/../vistas/pedidos_crear.php";
    }

    /* 
       INSERTAR PEDIDO (CORREGIDO)
     */
   public function insertar($id_presupuesto, $estado, $fecha_entrega) {

    $id_presupuesto = (int)$id_presupuesto;
    $estado = trim($estado);
    $fecha_entrega = $fecha_entrega;

    $this->modelo->insertar($id_presupuesto, $estado, $fecha_entrega);

    header("Location: " . BASE_URL . "index.php?controlador=pedidos&accion=listar");
    exit;
}


    /* 
       FORMULARIO ACTUALIZAR
     */
    public function actualizarForm() {
        if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
            $id = (int) $_GET["id"];
            $pedido = $this->modelo->obtener($id);

            if ($pedido) {
                require __DIR__ . "/../vistas/pedido_actualizar.php";
                return;
            }
        }
        header("Location: " . BASE_URL . "index.php?controlador=pedidos&accion=listar");
        exit;
    }

    /* 
       ACTUALIZAR PEDIDO
     */
    public function actualizar() {
        if (isset($_POST["id"], $_POST["estado"], $_POST["fecha_entrega"])) {

            $id = (int) $_POST["id"];
            $estado = trim($_POST["estado"]);
            $fecha_entrega = $_POST["fecha_entrega"];

            if ($id > 0 && $estado !== "" && $fecha_entrega !== "") {
                $this->modelo->actualizar($id, $estado, $fecha_entrega);
            }
        }

        header("Location: " . BASE_URL . "index.php?controlador=pedidos&accion=listar");
        exit;
    }

    /* 
       ELIMINAR
    */
    public function eliminar() {
        if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
            $id = (int) $_GET["id"];
            $this->modelo->eliminar($id);
        }
        header("Location: " . BASE_URL . "index.php?controlador=pedidos&accion=listar");
        exit;
    }
}
