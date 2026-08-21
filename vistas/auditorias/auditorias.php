<h3 class="text-domocreativo">Registro de auditoría</h3>

<div class="mb-3">
    <a href="index.php" class="btn btn-secondary">
        Volver
    </a>
</div>

<table class="table table-bordered table-hover">

    <tr>
        <th>Id</th>
        <th>Usuario</th>
        <th>Acción</th>
        <th>Tabla afectada</th>
        <th>Registro</th>
        <th>Descripción</th>
        <th>Antes</th>
        <th>Después</th>
        <th>Fecha</th>
    </tr>

    <?php if (count($auditorias) > 0): ?>

        <?php foreach ($auditorias as $a): ?>

            <tr>

                <td><?= $a["id_auditoria"] ?></td>

                <td><?= htmlspecialchars($a["nombre"] . " " . $a["apellidos"]) ?></td>

                <td>
                    <?php
                        $badge = "secondary";
                        if ($a["accion"] === "INSERT") $badge = "success";
                        if ($a["accion"] === "UPDATE") $badge = "warning";
                        if ($a["accion"] === "DELETE") $badge = "danger";
                    ?>
                    <span class="badge bg-<?= $badge ?>"><?= $a["accion"] ?></span>
                </td>

                <td><?= htmlspecialchars($a["tabla_afectada"]) ?></td>

                <td>#<?= $a["registro_id"] ?></td>

                <td><?= htmlspecialchars($a["descripcion"]) ?></td>

                <td>
                    <small class="text-muted">
                        <?= $a["datos_anteriores"] ? htmlspecialchars($a["datos_anteriores"]) : "—" ?>
                    </small>
                </td>

                <td>
                    <small class="text-muted">
                        <?= $a["datos_nuevos"] ? htmlspecialchars($a["datos_nuevos"]) : "—" ?>
                    </small>
                </td>

                <td><?= $a["fecha"] ?></td>

            </tr>

        <?php endforeach; ?>

    <?php else: ?>

        <tr>
            <td colspan="9" class="text-center">
                Todavía no hay registros de auditoría.
            </td>
        </tr>

    <?php endif; ?>

</table>