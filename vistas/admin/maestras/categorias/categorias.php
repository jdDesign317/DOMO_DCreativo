<?php
// VALIDAR SESION
if (!isset($_SESSION["id_usuario"])) {
    header("Location: vistas/auth/login.php");
    exit;
}

// SOLO ADMIN
if ($_SESSION["id_perfil"] != 2) {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . "/../../../../controladores/admin/maestras/CategoriasControlador.php";

$ctrl = new CategoriasControlador();

// BAJA LOGICA
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"])) {

    $id = intval($_POST["id"]);

    if ($_POST["accion"] === "desactivar") {
        $ctrl->desactivar($id);
    }

    if ($_POST["accion"] === "reactivar") {
        $ctrl->reactivar($id);
    }

    header("Location: index.php?accion=categorias");
    exit;
}

// BUSCADOR
$buscar = isset($_GET["buscar"]) ? trim($_GET["buscar"]) : "";

// ORDENAMIENTO
$columnas_permitidas = ["id_categoria", "nombre", "activo"];
$orden = isset($_GET["orden"]) && in_array($_GET["orden"], $columnas_permitidas)
         ? $_GET["orden"]
         : "id_categoria";

$direccion  = isset($_GET["dir"]) && $_GET["dir"] === "desc" ? "desc" : "asc";
$dir_toggle = $direccion === "asc" ? "desc" : "asc";

// TRAER TODOS, FILTRAR Y ORDENAR EN PHP
$categorias = $ctrl->listarTodas();

// FILTRO POR BUSCADOR
if ($buscar !== "") {
    $categorias = array_filter($categorias, function($cat) use ($buscar) {
        return stripos($cat["nombre"], $buscar) !== false;
    });
    $categorias = array_values($categorias);
}

usort($categorias, function($a, $b) use ($orden, $direccion) {
    $val_a = $a[$orden];
    $val_b = $b[$orden];
    $cmp   = strcmp((string)$val_a, (string)$val_b);
    return $direccion === "asc" ? $cmp : -$cmp;
});

// PAGINACION
$por_pagina        = 2;
$total             = count($categorias);
$total_paginas     = max(1, ceil($total / $por_pagina));
$pagina_actual     = isset($_GET["pagina"]) ? max(1, intval($_GET["pagina"])) : 1;
$inicio            = ($pagina_actual - 1) * $por_pagina;
$categorias_pagina = array_slice($categorias, $inicio, $por_pagina);

$url_base = "index.php?accion=categorias&orden={$orden}&dir={$direccion}&buscar=" . urlencode($buscar);
?>

<h4 class="text-domocreativo mb-3">Categorías</h4>

<div class="mb-3">

    <a href="index.php" class="btn btn-secondary">Volver</a>

    <a href="index.php?accion=categorias_crear" class="btn btn-domocreativo">
        Nueva categoría
    </a>

</div>

<!-- BUSCADOR -->
<form method="GET" action="index.php" class="mb-3">
    <input type="hidden" name="accion" value="categorias">
    <input type="hidden" name="orden"  value="<?= htmlspecialchars($orden) ?>">
    <input type="hidden" name="dir"    value="<?= htmlspecialchars($direccion) ?>">
    <div class="input-group" style="max-width:400px;">
        <input type="text"
               name="buscar"
               class="form-control"
               placeholder="Buscar por nombre..."
               value="<?= htmlspecialchars($buscar) ?>">
        <button type="submit" class="btn btn-domocreativo">Buscar</button>
        <?php if ($buscar !== "") : ?>
            <a href="index.php?accion=categorias" class="btn btn-outline-secondary">✕ Limpiar</a>
        <?php endif; ?>
    </div>
</form>

<?php if ($total === 0) : ?>
    <div class="alert alert-info">
        <?= $buscar !== "" ? "No se encontraron categorías para \"" . htmlspecialchars($buscar) . "\"." : "No hay categorías registradas." ?>
    </div>
<?php else : ?>

<table class="table table-bordered table-hover">

    <thead>
        <tr>

            <th>
                <a href="index.php?accion=categorias&orden=id_categoria&dir=<?= $orden === 'id_categoria' ? $dir_toggle : 'asc' ?>&pagina=1&buscar=<?= urlencode($buscar) ?>"
                   class="text-decoration-none text-dark">
                    ID
                    <?php if ($orden === "id_categoria") echo $direccion === "asc" ? "▲" : "▼"; ?>
                </a>
            </th>

            <th>
                <a href="index.php?accion=categorias&orden=nombre&dir=<?= $orden === 'nombre' ? $dir_toggle : 'asc' ?>&pagina=1&buscar=<?= urlencode($buscar) ?>"
                   class="text-decoration-none text-dark">
                    Nombre
                    <?php if ($orden === "nombre") echo $direccion === "asc" ? "▲" : "▼"; ?>
                </a>
            </th>

            <th>
                <a href="index.php?accion=categorias&orden=activo&dir=<?= $orden === 'activo' ? $dir_toggle : 'asc' ?>&pagina=1&buscar=<?= urlencode($buscar) ?>"
                   class="text-decoration-none text-dark">
                    Estado
                    <?php if ($orden === "activo") echo $direccion === "asc" ? "▲" : "▼"; ?>
                </a>
            </th>

            <th>Acciones</th>

        </tr>
    </thead>

    <tbody>

    <?php foreach ($categorias_pagina as $cat) : ?>

        <tr>

            <td><?= $cat["id_categoria"] ?></td>

            <td><?= htmlspecialchars($cat["nombre"]) ?></td>

            <td>
                <?php if ($cat["activo"] == 1) : ?>
                    <span class="badge bg-success">Activa</span>
                <?php else : ?>
                    <span class="badge bg-secondary">Inactiva</span>
                <?php endif; ?>
            </td>

            <td>

                <a href="index.php?accion=categorias_ver&id=<?= $cat["id_categoria"] ?>"
                   class="btn btn-sm btn-info text-white">
                    Ver
                </a>

                <a href="index.php?accion=categorias_editar&id=<?= $cat["id_categoria"] ?>"
                   class="btn btn-sm btn-warning">
                    Editar
                </a>

                <?php if ($cat["activo"] == 1) : ?>

                    <form action="index.php?accion=categorias" method="POST"
                          onsubmit="return confirm('¿Desactivar esta categoría?')"
                          style="display:inline;">
                        <input type="hidden" name="accion" value="desactivar">
                        <input type="hidden" name="id"     value="<?= $cat["id_categoria"] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Desactivar</button>
                    </form>

                <?php else : ?>

                    <form action="index.php?accion=categorias" method="POST"
                          onsubmit="return confirm('¿Reactivar esta categoría?')"
                          style="display:inline;">
                        <input type="hidden" name="accion" value="reactivar">
                        <input type="hidden" name="id"     value="<?= $cat["id_categoria"] ?>">
                        <button type="submit" class="btn btn-sm btn-success">Reactivar</button>
                    </form>

                <?php endif; ?>

            </td>

        </tr>

    <?php endforeach; ?>

    </tbody>
</table>

<!-- PAGINACION -->
<nav>
    <ul class="pagination">

        <li class="page-item <?= $pagina_actual == 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= $url_base ?>&pagina=1">« Primera</a>
        </li>

        <li class="page-item <?= $pagina_actual == 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= $url_base ?>&pagina=<?= $pagina_actual - 1 ?>">‹ Anterior</a>
        </li>

        <?php for ($i = 1; $i <= $total_paginas; $i++) : ?>
            <li class="page-item <?= $i == $pagina_actual ? 'active' : '' ?>">
                <a class="page-link" href="<?= $url_base ?>&pagina=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <li class="page-item <?= $pagina_actual == $total_paginas ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= $url_base ?>&pagina=<?= $pagina_actual + 1 ?>">Siguiente ›</a>
        </li>

        <li class="page-item <?= $pagina_actual == $total_paginas ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= $url_base ?>&pagina=<?= $total_paginas ?>">Última »</a>
        </li>

    </ul>
</nav>

<p class="text-muted small">
    Mostrando <?= $inicio + 1 ?>–<?= min($inicio + $por_pagina, $total) ?> de <?= $total ?> registros.
    Página <?= $pagina_actual ?> de <?= $total_paginas ?>.
    <?php if ($buscar !== "") echo " | Búsqueda: \"" . htmlspecialchars($buscar) . "\""; ?>
</p>

<?php endif; ?>
