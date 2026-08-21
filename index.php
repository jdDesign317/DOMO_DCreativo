<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
                <a href='?accion=carrito' class='btn btn-domocreativo m-2'>Carrito</a>
                <a href='?accion=servicios_personalizados' class='btn btn-domocreativo m-2'>Servicios</a>
                <a href='?accion=presupuestos' class='btn btn-domocreativo m-2'>Presupuestos</a>

                <hr>
                <p class='fw-bold fs-5 mb-2'>Tablas maestras</p>
                <a href='?accion=categorias' class='btn btn-domocreativo m-2'>Categorías</a>
                <a href='?accion=metodos_pago' class='btn btn-domocreativo m-2'>Métodos de pago</a>
                <a href='?accion=tipos_evento' class='btn btn-domocreativo m-2'>Tipos de evento</a>
                <a href='?accion=tipos_servicio' class='btn btn-domocreativo m-2'>Tipos de servicio</a>
                <a href='?accion=unidades' class='btn btn-domocreativo m-2'>Unidades</a>

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

                <a href='?accion=servicios_personalizados' class='btn btn-domocreativo m-2'>
                    Servicios personalizados
                </a>

                <a href='?accion=presupuestos' class='btn btn-domocreativo m-2'>
                    Presupuestos
                </a>

                <div class='mt-3'>
                    <a href='vistas/auth/logout.php' class='btn btn-outline-dark'>
                        Cerrar sesión
                    </a>
                </div>
            </div>";
        }

        break;

    // USUARIOS
 

    case 'usuarios':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/UsuariosControlador.php';

        $ctrl = new UsuariosControlador();
        $usuarios = $ctrl->listar();

        require __DIR__ . '/vistas/usuarios/usuarios.php';
        break;

    case 'usuarios_ver':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/UsuariosControlador.php';

        $ctrl = new UsuariosControlador();
        $usuario = $ctrl->buscarPorId($_GET['id']);

        require __DIR__ . '/vistas/usuarios/usuarios_ver.php';
        break;

    case 'usuarios_crear':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/UsuariosControlador.php';

        // NOTA: el guardado (POST) lo maneja la propia vista usuarios_crear.php
        require __DIR__ . '/vistas/usuarios/usuarios_crear.php';
        break;

    case 'usuarios_editar':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/UsuariosControlador.php';

        // NOTA: el guardado (POST) lo maneja la propia vista usuarios_editar.php
        require __DIR__ . '/vistas/usuarios/usuarios_editar.php';
        break;

    case 'usuarios_eliminar':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/UsuariosControlador.php';

        $ctrl = new UsuariosControlador();
        $ctrl->eliminar($_GET['id']);

        header("Location: index.php?accion=usuarios");
        exit;
        break;

     // AUDITORIAS

    case 'auditorias':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/AuditoriasControlador.php';

        $ctrl = new AuditoriasControlador();
        $auditorias = $ctrl->listar();

        require __DIR__ . '/vistas/auditorias/auditorias.php';
        break;

    // PRODUCTOS
   
    case 'productos':

        Auth::verificarSesion();

        require_once __DIR__ . '/controladores/ProductosControlador.php';

        $ctrl = new ProductosControlador();

        if ($perfil == 1) {
            $productos = $ctrl->listar();
            require __DIR__ . '/vistas/productos/productos_cliente.php';
        } else {
            $productos = $ctrl->listarTodas();
            require __DIR__ . '/vistas/productos/productos.php';
        }
        break;

    case 'productos_ver':

        Auth::verificarSesion();

        require_once __DIR__ . '/controladores/ProductosControlador.php';

        $ctrl = new ProductosControlador();
        $producto = $ctrl->buscarPorId($_GET['id']);

        require __DIR__ . '/vistas/productos/productos_ver.php';
        break;

    case 'productos_crear':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/ProductosControlador.php';

        $ctrl = new ProductosControlador();

        if ($_POST) {
            $ctrl->crear($_POST);
            header("Location: index.php?accion=productos");
            exit;
        }

        require __DIR__ . '/vistas/productos/productos_crear.php';
        break;

    case 'productos_editar':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

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

    case 'productos_eliminar':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/ProductosControlador.php';

        $ctrl = new ProductosControlador();
        $ctrl->desactivar($_GET['id']);

        header("Location: index.php?accion=productos");
        exit;
        break;

    case 'productos_reactivar':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/ProductosControlador.php';

        $ctrl = new ProductosControlador();
        $ctrl->reactivar($_GET['id']);

        header("Location: index.php?accion=productos");
        exit;
        break;

  
    // TABLAS MAESTRAS 

    // CATEGORIAS
    case 'categorias':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/CategoriasControlador.php';
        $ctrl = new CategoriasControlador();
        $categorias = $ctrl->listarTodas();

        require __DIR__ . '/vistas/admin/maestras/categorias/categorias.php';
        break;

    case 'categorias_ver':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/CategoriasControlador.php';
        $ctrl = new CategoriasControlador();
        $categoria = $ctrl->buscarPorId($_GET['id']);

        require __DIR__ . '/vistas/admin/maestras/categorias/categorias_ver.php';
        break;

    case 'categorias_crear':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/CategoriasControlador.php';

        require __DIR__ . '/vistas/admin/maestras/categorias/categorias_crear.php';
        break;

    case 'categorias_editar':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/CategoriasControlador.php';

        require __DIR__ . '/vistas/admin/maestras/categorias/categorias_editar.php';
        break;

    case 'categorias_eliminar':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/CategoriasControlador.php';
        $ctrl = new CategoriasControlador();
        $ctrl->desactivar($_GET['id']);

        header("Location: index.php?accion=categorias");
        exit;
        break;

    // METODOS DE PAGO
    case 'metodos_pago':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/MetodosPagoControlador.php';
        $ctrl = new MetodosPagoControlador();
        $metodos_pago = $ctrl->listarTodos();

        require __DIR__ . '/vistas/admin/maestras/metodos_pago/metodos_pago.php';
        break;

    case 'metodos_pago_ver':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/MetodosPagoControlador.php';
        $ctrl = new MetodosPagoControlador();
        $metodo_pago = $ctrl->buscarPorId($_GET['id']);

        require __DIR__ . '/vistas/admin/maestras/metodos_pago/metodos_pago_ver.php';
        break;

    case 'metodos_pago_crear':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/MetodosPagoControlador.php';

        require __DIR__ . '/vistas/admin/maestras/metodos_pago/metodos_pago_crear.php';
        break;

    case 'metodos_pago_editar':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/MetodosPagoControlador.php';

        require __DIR__ . '/vistas/admin/maestras/metodos_pago/metodos_pago_editar.php';
        break;

    case 'metodos_pago_eliminar':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/MetodosPagoControlador.php';
        $ctrl = new MetodosPagoControlador();
        $ctrl->desactivar($_GET['id']);

        header("Location: index.php?accion=metodos_pago");
        exit;
        break;

    // TIPOS DE EVENTO
    case 'tipos_evento':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/TiposEventoControlador.php';
        $ctrl = new TiposEventoControlador();
        $tipos_evento = $ctrl->listarTodos();

        require __DIR__ . '/vistas/admin/maestras/tipos_evento/tipos_evento.php';
        break;

    case 'tipos_evento_ver':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/TiposEventoControlador.php';
        $ctrl = new TiposEventoControlador();
        $tipo_evento = $ctrl->buscarPorId($_GET['id']);

        require __DIR__ . '/vistas/admin/maestras/tipos_evento/tipos_evento_ver.php';
        break;

    case 'tipos_evento_crear':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/TiposEventoControlador.php';

        require __DIR__ . '/vistas/admin/maestras/tipos_evento/tipos_evento_crear.php';
        break;

    case 'tipos_evento_editar':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/TiposEventoControlador.php';

        require __DIR__ . '/vistas/admin/maestras/tipos_evento/tipos_evento_editar.php';
        break;

    case 'tipos_evento_eliminar':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/TiposEventoControlador.php';
        $ctrl = new TiposEventoControlador();
        $ctrl->desactivar($_GET['id']);

        header("Location: index.php?accion=tipos_evento");
        exit;
        break;

    // TIPOS DE SERVICIO
    case 'tipos_servicio':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/TiposServicioControlador.php';
        $ctrl = new TiposServicioControlador();
        $tipos_servicio = $ctrl->listarTodos();

        require __DIR__ . '/vistas/admin/maestras/tipos_servicio/tipos_servicio.php';
        break;

    case 'tipos_servicio_ver':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/TiposServicioControlador.php';
        $ctrl = new TiposServicioControlador();
        $tipo_servicio = $ctrl->buscarPorId($_GET['id']);

        require __DIR__ . '/vistas/admin/maestras/tipos_servicio/tipos_servicio_ver.php';
        break;

    case 'tipos_servicio_crear':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/TiposServicioControlador.php';

        require __DIR__ . '/vistas/admin/maestras/tipos_servicio/tipos_servicio_crear.php';
        break;

    case 'tipos_servicio_editar':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/TiposServicioControlador.php';

        require __DIR__ . '/vistas/admin/maestras/tipos_servicio/tipos_servicio_editar.php';
        break;

    case 'tipos_servicio_eliminar':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/TiposServicioControlador.php';
        $ctrl = new TiposServicioControlador();
        $ctrl->desactivar($_GET['id']);

        header("Location: index.php?accion=tipos_servicio");
        exit;
        break;

    // UNIDADES
    case 'unidades':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/UnidadesControlador.php';
        $ctrl = new UnidadesControlador();
        $unidades = $ctrl->listar();

        require __DIR__ . '/vistas/admin/maestras/unidades/unidades.php';
        break;

    case 'unidades_ver':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/UnidadesControlador.php';
        $ctrl = new UnidadesControlador();
        $unidad = $ctrl->buscarPorId($_GET['id']);

        require __DIR__ . '/vistas/admin/maestras/unidades/unidades_ver.php';
        break;

    case 'unidades_crear':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/UnidadesControlador.php';

        require __DIR__ . '/vistas/admin/maestras/unidades/unidades_crear.php';
        break;

    case 'unidades_editar':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/UnidadesControlador.php';

        require __DIR__ . '/vistas/admin/maestras/unidades/unidades_editar.php';
        break;

    case 'unidades_eliminar':

        Auth::verificarSesion();
        if ($perfil != 2) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/admin/maestras/UnidadesControlador.php';
        $ctrl = new UnidadesControlador();
        $ctrl->desactivar($_GET['id']);

        header("Location: index.php?accion=unidades");
        exit;
        break;

    
    // CARRITO
    

    case 'carrito':
        Auth::verificarSesion();
        require __DIR__ . '/vistas/carrito/carrito_ver.php';
        break;

    case 'carrito_agregar':
        Auth::verificarSesion();
        require __DIR__ . '/vistas/carrito/carrito_agregar.php';
        break;

    case 'carrito_quitar':
        Auth::verificarSesion();
        require __DIR__ . '/vistas/carrito/carrito_quitar.php';
        break;

    case 'carrito_vaciar':
        Auth::verificarSesion();
        require __DIR__ . '/vistas/carrito/carrito_vaciar.php';
        break;

    
    // SERVICIOS PERSONALIZADOS 
    
    case 'servicios':
    case 'servicios_personalizados':

        Auth::verificarSesion();
        if ($perfil != 2 && $perfil != 3) { header("Location: index.php"); exit; }

        require __DIR__ . '/vistas/servicios_personalizados/servicios.php';
        break;

    case 'servicios_ver':

        Auth::verificarSesion();
        if ($perfil != 2 && $perfil != 3) { header("Location: index.php"); exit; }

        require __DIR__ . '/vistas/servicios_personalizados/servicios_ver.php';
        break;

    case 'servicios_crear':

        Auth::verificarSesion();
        if ($perfil != 2 && $perfil != 3) { header("Location: index.php"); exit; }

        require __DIR__ . '/vistas/servicios_personalizados/servicios_crear.php';
        break;

    case 'servicios_editar':

        Auth::verificarSesion();
        if ($perfil != 2 && $perfil != 3) { header("Location: index.php"); exit; }

        require __DIR__ . '/vistas/servicios_personalizados/servicios_editar.php';
        break;

    case 'servicios_eliminar':

        Auth::verificarSesion();
        if ($perfil != 2 && $perfil != 3) { header("Location: index.php"); exit; }

        require_once __DIR__ . '/controladores/ServiciosPersonalizadosControlador.php';

        $ctrl = new ServiciosPersonalizadosControlador();
        $ctrl->eliminar($_GET['id']);

        header("Location: index.php?accion=servicios_personalizados");
        exit;
        break;

    
    // PRESUPUESTOS 
   

    case 'presupuestos':

        Auth::verificarSesion();
        if ($perfil != 2 && $perfil != 3) { header("Location: index.php"); exit; }

        require __DIR__ . '/vistas/presupuestos/presupuestos.php';
        break;

    case 'presupuestos_ver':

        Auth::verificarSesion();
        if ($perfil != 2 && $perfil != 3) { header("Location: index.php"); exit; }

        require __DIR__ . '/vistas/presupuestos/presupuestos_ver.php';
        break;

    case 'presupuestos_crear':

        Auth::verificarSesion();
        if ($perfil != 2 && $perfil != 3) { header("Location: index.php"); exit; }

        require __DIR__ . '/vistas/presupuestos/presupuestos_crear.php';
        break;

    case 'presupuestos_editar':

        Auth::verificarSesion();
        if ($perfil != 2 && $perfil != 3) { header("Location: index.php"); exit; }

        require __DIR__ . '/vistas/presupuestos/presupuestos_editar.php';
        break;

}

$contenido = ob_get_clean();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>DOMOCreativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

            <?php if ($perfil == 1): // CLIENTE ?>

                <a href="?accion=productos" class="btn btn-domocreativo w-100 mb-2">Productos</a>
                <a href="?accion=carrito" class="btn btn-domocreativo w-100 mb-2">Carrito</a>

            <?php elseif ($perfil == 2): // ADMIN ?>

                <a href="?accion=usuarios" class="btn btn-domocreativo w-100 mb-2">Usuarios</a>
                <a href="?accion=productos" class="btn btn-domocreativo w-100 mb-2">Productos</a>
                <a href="?accion=carrito" class="btn btn-domocreativo w-100 mb-2">Carrito</a>
                <a href="?accion=servicios_personalizados" class="btn btn-domocreativo w-100 mb-2">Servicios</a>
                <a href="?accion=presupuestos" class="btn btn-domocreativo w-100 mb-2">Presupuestos</a>

                <hr class="border-light my-2">
                <small class="text-white-50 d-block mb-1 ps-1">Tablas maestras</small>
                <a href="?accion=categorias" class="btn btn-domocreativo w-100 mb-2">Categorías</a>
                <a href="?accion=metodos_pago" class="btn btn-domocreativo w-100 mb-2">Métodos de pago</a>
                <a href="?accion=tipos_evento" class="btn btn-domocreativo w-100 mb-2">Tipos de evento</a>
                <a href="?accion=tipos_servicio" class="btn btn-domocreativo w-100 mb-2">Tipos de servicio</a>
                <a href="?accion=unidades" class="btn btn-domocreativo w-100 mb-2">Unidades</a>
                
                <hr class="border-light my-2">
                <a href="?accion=auditorias" class="btn btn-domocreativo w-100 mb-2">Auditoría</a>

            <?php elseif ($perfil == 3): // DISEÑADOR ?>

                <a href="?accion=servicios_personalizados" class="btn btn-domocreativo w-100 mb-2">Servicios Personalizados</a>
                <a href="?accion=presupuestos" class="btn btn-domocreativo w-100 mb-2">Presupuestos</a>

            <?php endif; ?>

            <hr class="border-light my-2">
            <a href="vistas/auth/logout.php" class="btn btn-outline-light w-100 mb-2">Cerrar sesión</a>

        <?php endif; ?>

    </div>

    <div class="container p-4">
        <?= $contenido ?>
    </div>

</div>

</body>
</html>
