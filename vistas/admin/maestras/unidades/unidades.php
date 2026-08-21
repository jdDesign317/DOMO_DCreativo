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

require_once __DIR__ . "/../../../../controladores/admin/maestras/UnidadesControlador.php";

$ctrl = new UnidadesControlador();

// BAJA LOGICA
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"])) {

    $id = intval($_POST["id"]);

    if ($_POST["accion"] === "desactivar") {
        $ctrl->desactivar($id);
    }

    if ($_POST["accion"] === "reactivar") {
        $ctrl->reactivar($id);
    }

    header("Location: index.php?accion=unidades");
    exit;
}

// BUSCADOR
$buscar = isset($_GET["buscar"]) ? trim($_GET["buscar"]) : "";

// ORDENAMIENTO
$columnas_permitidas = ["id_unidad", "nombre", "activo"];
$orden = isset($_GET["orden"]) && in_array($_GET["orden"], $columnas_permitidas)
         ? $_GET["orden"]
         : "id_unidad";

$direccion  = isset($_GET["dir"]) && $_GET["dir"] === "desc" ? "desc" : "asc";
$dir_toggle = $direccion === "asc" ? "desc" : "asc";

// TRAER TODAS, FILTRAR Y ORDENAR EN PHP
$unidades = $ctrl->listarTodas();

// FILTRO POR BUSCADOR
if ($buscar !== "") {
    $unidades = array_filter($unidades, function($u) use ($buscar) {
        return stripos($u["nombre"], $buscar) !== false;
    });
    $unidades = array_values($unidades);
}

usort($unidades, function($a, $b) use ($orden, $direccion) {
    $cmp = strcmp((string)$a[$orden], (string)$b[$orden]);
    return $direccion === "asc" ? $cmp : -$cmp;
});

// PAGINACION
$por_pagina      = 2;
$total           = count($unidades);
$total_paginas   = max(1, ceil($total / $por_pagina));
$pagina_actual   = isset($_GET["pagina"]) ? max(1, intval($_GET["pagina"])) : 1;
$inicio          = ($pagina_actual - 1) * $por_pagina;
$unidades_pagina = array_slice($unidades, $inicio, $por_pagina);

$url_base = "index.php?accion=unidades&orden={$orden}&dir={$direccion}&buscar=" . urlencode($buscar);
?>

<h4 class="text-domocreativo mb-3">Unidades</h4>

<div class="mb-3">
    <a href="index.php" class="btn btn-secondary">Volver</a>
    <a href="index.php?accion=unidades_crear" class="btn btn-domocreativo">
        Nueva unidad
    </a>
</div>

<!-- BUSCADOR -->
<form method="GET" action="index.php" class="mb-3">
    <input type="hidden" name="accion" value="unidades">
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
            <a href="index.php?accion=unidades" class="btn btn-outline-secondary">✕ Limpiar</a>
        <?php endif; ?>
    </div>
</form>

<?php if ($total === 0) : ?>
    <div class="alert alert-info">
        <?= $buscar !== "" ? "No se encontraron unidades para \"" . htmlspecialchars($buscar) . "\"." : "No hay unidades registradas." ?>
    </div>
<?php else : ?>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>
                <a href="index.php?accion=unidades&orden=id_unidad&dir=<?= $orden === 'id_unidad' ? $dir_toggle : 'asc' ?>&pagina=1&buscar=<?= urlencode($buscar) ?>"
                   class="text-decoration-none text-dark">
                    ID
                    <?php if ($orden === "id_unidad") echo $direccion === "asc" ? "▲" : "▼"; ?>
                </a>
            </th>
            <th>
                <a href="index.php?accion=unidades&orden=nombre&dir=<?= $orden === 'nombre' ? $dir_toggle : 'asc' ?>&pagina=1&buscar=<?= urlencode($buscar) ?>"
                   class="text-decoration-none text-dark">
                    Nombre
                    <?php if ($orden === "nombre") echo $direccion === "asc" ? "▲" : "▼"; ?>
                </a>
            </th>
            <th>
                <a href="index.php?accion=unidades&orden=activo&dir=<?= $orden === 'activo' ? $dir_toggle : 'asc' ?>&pagina=1&buscar=<?= urlencode($buscar) ?>"
                   class="text-decoration-none text-dark">
                    Estado
                    <?php if ($orden === "activo") echo $direccion === "asc" ? "▲" : "▼"; ?>
                </a>
            </th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($unidades_pagina as $unidad) : ?>
        <tr>
            <td><?= $unidad["id_unidad"] ?></td>
            <td><?= htmlspecialchars($unidad["nombre"]) ?></td>
            <td>
                <?php if ($unidad["activo"] == 1) : ?>
                    <span class="badge bg-success">Activa</span>
                <?php else : ?>
                    <span class="badge bg-secondary">Inactiva</span>
                <?php endif; ?>
            </td>
            <td>
                <a href="index.php?accion=unidades_ver&id=<?= $unidad["id_unidad"] ?>"
                   class="btn btn-sm btn-info text-white">Ver</a>

                <a href="index.php?accion=unidades_editar&id=<?= $unidad["id_unidad"] ?>"
                   class="btn btn-sm btn-warning">Editar</a>

                <?php if ($unidad["activo"] == 1) : ?>
                    <form action="index.php?accion=unidades" method="POST"
                          onsubmit="return confirm('¿Desactivar esta unidad?')"
                          style="display:inline;">
                        <input type="hidden" name="accion" value="desactivar">
                        <input type="hidden" name="id"     value="<?= $unidad["id_unidad"] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Desactivar</button>
                    </form>
                <?php else : ?>
                    <form action="index.php?accion=unidades" method="POST"
                          onsubmit="return confirm('¿Reactivar esta unidad?')"
                          style="display:inline;">
                        <input type="hidden" name="accion" value="reactivar">
                        <input type="hidden" name="id"     value="<?= $unidad["id_unidad"] ?>">
                        <button type="submit" class="btn btn-sm btn-success">Reactivar</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

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
