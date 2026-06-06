<?php

session_start();

require_once __DIR__ . '/helpers/Auth.php';

$accion = $_GET['accion'] ?? 'inicio';

$logueado = isset($_SESSION["id_usuario"]);
$perfil = $_SESSION["id_perfil"] ?? null;

ob_start();

switch ($accion) {

    // INICIO
    default:

        if (!$logueado) {

          echo "
<div class='hero-domocreativo text-center p-5 rounded shadow-sm'>

    <div style='background:#fff;border:3px solid #c65d3a;border-radius:14px;padding:35px;'>

        <h1 class='fw-bolder display-4 mb-3' style='color:#c65d3a;'>
            BIENVENIDO
        </h1>

        <p class='text-muted fs-5'>
            Sistema de Gestión Creativa y Producción Personalizada
            
        </p>

        <!-- LOGO -->
        <div class='mt-4 p-4 bg-white rounded shadow-sm d-flex justify-content-center'>
            <img src='assets/img/logo1.png' style='max-width:260px;'>
        </div>

            <a href='vistas/auth/login.php' class='btn btn-domocreativo mt-3'>
            Iniciar Sesión para acceder al sistema
        </a>

    </div>

</div>";
        }

        elseif ($perfil == 1) {

            echo "
            <div class='text-center p-4'>
                <h2>Bienvenido Cliente</h2>

                <a href='?accion=productos' class='btn btn-domocreativo m-2'>Productos</a>
                <a href='?accion=carrito' class='btn btn-domocreativo m-2'>Carrito</a>

                <div class='mt-3'>
                    <a href='vistas/auth/logout.php' class='btn btn-outline-dark'>
                        Volver al inicio / cerrar sesión
                    </a>
                </div>
            </div>";
        }

        elseif ($perfil == 2) {

            echo "
            <div class='text-center p-4'>
                <h2>Panel Administrador</h2>

                <a href='?accion=usuarios' class='btn btn-domocreativo m-2'>Usuarios</a>
                <a href='?accion=productos' class='btn btn-domocreativo m-2'>Productos</a>

                <div class='mt-3'>
                    <a href='vistas/auth/logout.php' class='btn btn-outline-dark'>
                        Cerrar sesión
                    </a>
                </div>
            </div>";
        }

     elseif ($perfil == 3) {

    echo "
    <div class='text-center p-4'>
        <h2>Panel Diseñador</h2>

        <a href='?accion=servicios' class='btn btn-domocreativo m-2'>
            Pedidos personalizados
        </a>

        <a href='?accion=presupuestos' class='btn btn-domocreativo m-2'>
            Presupuestos
        </a>

        <div class='mt-3'>
            <a href='vistas/auth/logout.php' class='btn btn-outline-dark'>
                Cerrar sesión
            </a>
        </div>
    </div>
    ";
}
        break;

    // USUARIOS
    case 'usuarios':

        Auth::verificarSesion();

        require_once __DIR__ . '/controladores/UsuariosControlador.php';

        $ctrl = new UsuariosControlador();
        $usuarios = $ctrl->listar();

        require __DIR__ . '/vistas/usuarios/usuarios.php';
        break;
   
    // USUARIOS VER
    case 'usuarios_ver':

    Auth::verificarSesion();

    require_once __DIR__ . '/controladores/UsuariosControlador.php';

    $ctrl = new UsuariosControlador();
    $usuario = $ctrl->buscarPorId($_GET['id']);

    require __DIR__ . '/vistas/usuarios/usuarios_ver.php';
    break;

    // USUARIOS EDITAR
case 'usuarios_editar':

    Auth::verificarSesion();

    require_once __DIR__ . '/controladores/UsuariosControlador.php';

    $ctrl = new UsuariosControlador();

    if ($_POST) {

        $ctrl->actualizar($_POST);

        header("Location: index.php?accion=usuarios");
        exit;
    }

    $usuario = $ctrl->buscarPorId($_GET['id']);

    require __DIR__ . '/vistas/usuarios/usuarios_editar.php';
    break;
      
    // USUARIOS CREAR
case 'usuarios_crear':

    Auth::verificarSesion();

    require_once __DIR__ . '/controladores/UsuariosControlador.php';

    if ($_POST) {

        $ctrl = new UsuariosControlador();
        $ctrl->guardar($_POST);

        header("Location: index.php?accion=usuarios");
        exit;
    }

    require __DIR__ . '/vistas/usuarios/usuarios_crear.php';
    break;

    // PRODUCTOS
    case 'productos':

        Auth::verificarSesion();

        require_once __DIR__ . '/controladores/ProductosControlador.php';

        $ctrl = new ProductosControlador();
        $productos = $ctrl->listar();

        require __DIR__ . '/vistas/productos/productos.php';
        break;

    // VER PRODUC
    case 'productos_ver':

        Auth::verificarSesion();

        require_once __DIR__ . '/controladores/ProductosControlador.php';

        $ctrl = new ProductosControlador();
        $producto = $ctrl->buscarPorId($_GET['id']);

        require __DIR__ . '/vistas/productos/productos_ver.php';
        break;

    // CREAR PRODUC
    case 'productos_crear':

        Auth::verificarSesion();

        require_once __DIR__ . '/controladores/ProductosControlador.php';

        $ctrl = new ProductosControlador();

        if ($_POST) {
            $ctrl->crear($_POST);
            header("Location: index.php?accion=productos");
            exit;
        }

        require __DIR__ . '/vistas/productos/productos_crear.php';
        break;

    // EDITAR PRODUC
    case 'productos_editar':

        Auth::verificarSesion();

        require_once __DIR__ . '/controladores/ProductosControlador.php';

        $ctrl = new ProductosControlador();

        if ($_POST) {
            $ctrl->actualizar($_POST);
            header("Location: index.php?accion=productos");
            exit;
        }

        $producto = $ctrl->buscarPorId($_GET['id']);

        require __DIR__ . '/vistas/productos/productos_editar.php';
        break;

    // ELIMINAR PRODUC
    case 'productos_eliminar':

        Auth::verificarSesion();

        require_once __DIR__ . '/controladores/ProductosControlador.php';

        $ctrl = new ProductosControlador();
        $ctrl->eliminar($_GET['id']);

        header("Location: index.php?accion=productos");
        exit;

    // CARRITO VER
    case 'carrito':

        Auth::verificarSesion();

        require __DIR__ . '/vistas/carrito/carrito_ver.php';
        break;

    // CARRITO AGREGAR
    case 'carrito_agregar':

        Auth::verificarSesion();

        require_once __DIR__ . '/config/Conexion.php';
        $db = (new Conexion())->getConexion();

        $id_usuario = $_SESSION["id_usuario"];
        $id_producto = (int) ($_GET["id"] ?? 0);

        if ($id_producto <= 0) {
            header("Location: index.php?accion=productos");
            exit;
        }

        $carrito = $db->query("
            SELECT id_carrito 
            FROM carrito 
            WHERE id_usuario = $id_usuario 
            AND estado = 'activo'
            LIMIT 1
        ")->fetch_assoc();

        if (!$carrito) {
            $db->query("INSERT INTO carrito (id_usuario) VALUES ($id_usuario)");
            $id_carrito = $db->insert_id;
        } else {
            $id_carrito = $carrito["id_carrito"];
        }

        $item = $db->query("
            SELECT id_detalle 
            FROM carrito_detalle 
            WHERE id_carrito = $id_carrito 
            AND id_producto = $id_producto
            LIMIT 1
        ")->fetch_assoc();

        $precio = $db->query("
            SELECT precio 
            FROM productos 
            WHERE id_producto = $id_producto
        ")->fetch_assoc();

        $precio_unitario = $precio["precio"];

        if ($item) {

            $db->query("
                UPDATE carrito_detalle 
                SET cantidad = cantidad + 1 
                WHERE id_detalle = {$item['id_detalle']}
            ");

        } else {

            $db->query("
                INSERT INTO carrito_detalle 
                (id_carrito, id_producto, cantidad, precio_unitario)
                VALUES 
                ($id_carrito, $id_producto, 1, $precio_unitario)
            ");
        }

        header("Location: index.php?accion=carrito");
        exit;

    // CARRITO QUITAR
    case 'carrito_quitar':

        Auth::verificarSesion();

        require_once __DIR__ . '/config/Conexion.php';
        $db = (new Conexion())->getConexion();

        $id_detalle = (int) ($_GET["id"] ?? 0);

        if ($id_detalle > 0) {

            $db->query("
                DELETE FROM carrito_detalle 
                WHERE id_detalle = $id_detalle
            ");
        }

        header("Location: index.php?accion=carrito");
        exit;

    // CARRITO VACIAR
    case 'carrito_vaciar':

        Auth::verificarSesion();

        require_once __DIR__ . '/config/Conexion.php';
        $db = (new Conexion())->getConexion();

        $id_usuario = $_SESSION["id_usuario"];

        $carrito = $db->query("
            SELECT id_carrito 
            FROM carrito 
            WHERE id_usuario = $id_usuario 
            AND estado = 'activo'
            LIMIT 1
        ")->fetch_assoc();

        if ($carrito) {

            $db->query("
                DELETE FROM carrito_detalle 
                WHERE id_carrito = {$carrito['id_carrito']}
            ");
        }

        header("Location: index.php?accion=carrito");
        exit;
}

$contenido = ob_get_clean();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>DOMOCreativo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>

<body>

<div class="topbar">
    <div class="fw-bold text-white">DOMOCreativo</div>
</div>

<div class="layout">

    <div class="sidebar p-3">

        <?php if ($logueado): ?>

            <a href="?accion=productos" class="btn btn-domocreativo w-100 mb-2">Productos</a>
            <a href="?accion=carrito" class="btn btn-domocreativo w-100 mb-2">Carrito</a>

        <?php endif; ?>

    </div>

    <div class="container p-4">
        <?= $contenido ?>
    </div>

</div>

</body>
</html>