<?php

require_once __DIR__ . "/../../controladores/ServiciosPersonalizadosControlador.php";
require_once __DIR__ . "/../../config/Conexion.php";

$controlador = new ServiciosPersonalizadosControlador();

// TRAER PRODUCTOS PARA EL SELECT
$conexion = (new Conexion())->getConexion();
$productos = $conexion->query(
    "SELECT id_producto, nombre FROM productos WHERE activo = 1"
)->fetch_all(MYSQLI_ASSOC);

$mensaje = "";

if ($_POST) {

    $id_usuario          = intval($_POST["id_usuario"]);
    $id_producto          = intval($_POST["id_producto"]);
    $color                = trim($_POST["color"]);
    $texto_personalizado  = trim($_POST["texto_personalizado"]);
    $archivo_diseno       = trim($_POST["archivo_diseno"]);

    if ($id_usuario <= 0 || $id_producto <= 0) {

        $mensaje = "Completá el usuario y el producto.";

    } else {

        $controlador->crear(
            $id_usuario,
            $id_producto,
            $color,
            $texto_personalizado,
            $archivo_diseno
        );

        header("Location: index.php?accion=servicios_personalizados");
        exit;
    }
}

?>

<h3>Nuevo Servicio Personalizado</h3>

<?php if ($mensaje): ?>
    <div class="alert alert-warning"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<form method="POST">

    <div class="mb-3">

        <label class="form-label">
            ID Usuario (cliente)
        </label>

        <input
            type="number"
            name="id_usuario"
            class="form-control"
            required>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Producto
        </label>

        <select name="id_producto" class="form-control" required>
            <option value="">Seleccionar...</option>
            <?php foreach ($productos as $producto): ?>
                <option value="<?= $producto['id_producto'] ?>">
                    <?= htmlspecialchars($producto['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Color
        </label>

        <input
            type="text"
            name="color"
            class="form-control"
            required>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Texto Personalizado
        </label>

        <textarea
            name="texto_personalizado"
            class="form-control"
            rows="3"></textarea>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Archivo Diseño
        </label>

        <input
            type="text"
            name="archivo_diseno"
            class="form-control">

    </div>

    <button type="submit" class="btn btn-domocreativo">
        Guardar
    </button>

    <a href="index.php?accion=servicios_personalizados"
       class="btn btn-secondary">
        Volver
    </a>

</form>
