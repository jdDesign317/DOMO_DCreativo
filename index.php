<?php
session_start();

// Activar errores para depuración
error_reporting(E_ALL);
ini_set("display_errors", "On");

// Router MVC
$controlador = $_GET['controlador'] ?? null;
$accion      = $_GET['accion'] ?? null;

if ($controlador && $accion) {

    // Ejemplo: metodos_pago -> MetodosPagoControlador
    $clase   = str_replace('_', '', ucwords($controlador, '_')) . "Controlador";
    $archivo = __DIR__ . "/controladores/" . $clase . ".php";

    if (file_exists($archivo)) {
        require_once $archivo;

        if (class_exists($clase)) {
            $obj = new $clase();

            if (method_exists($obj, $accion)) {

                // Manejo genérico de acciones con parámetros
                if ($_SERVER["REQUEST_METHOD"] === "POST") {

                    // Pasar todos los datos del formulario como argumentos
                    $params = array_values($_POST);
                    $obj->$accion(...$params);
                } elseif (!empty($_GET)) {

                    // Pasar parámetros de la URL
                    $params = array_values($_GET);
          
                    array_shift($params); // controlador
                    array_shift($params); // accion
                    $obj->$accion(...$params);
                } else {

                    // Acción sin parámetros
                    $obj->$accion();
                }
                exit;
            } else {
                echo "<div class='alert alert-warning m-3'>Acción '$accion' no encontrada en $clase.</div>";
            }
        } else {
            echo "<div class='alert alert-danger m-3'>Clase '$clase' no definida.</div>";
        }
    } else {
        echo "<div class='alert alert-danger m-3'>Controlador '$controlador' no encontrado.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domo Creativo | Inicio</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ICONOS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- FUENTE POPPINS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- CSS PERSONAL -->
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <!-- Tarjeta de bienvenida -->
    <div class="welcome-card text-center mt-5">
        <h1><i class="bi bi-brush-fill"></i> Domo Creativo</h1>
        <p>Bienvenido al Sistema de Gestión Creativa y Producción Personalizada: DOMO Diseño Creativo</p>

        <!-- Botones -->
        <a href="vistas/login.php" class="btn btn-login">
            <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
        </a>
        <a href="vistas/registro.php" class="btn btn-register">
            <i class="bi bi-person-plus me-1"></i> Crear Cuenta
        </a>
    </div>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
