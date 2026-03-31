<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Métodos de Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<div class="container mt-5 pt-4">
    <h2 class="text-danger fw-bold mb-3">Métodos de Pago</h2>

    <!-- FORMULARIO DE CREACIÓN -->
   
        <form action="../index.php?controlador=metodos_pago&accion=insertar" method="POST">

        <div class="col-md-6">
            <label class="form-label fw-semibold">Descripción</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="col-md-12">
            <button class="btn btn-danger">
                <i class="bi bi-plus-circle me-1"></i> Agregar
            </button>
        </div>
    </form>

    <!-- TABLA DE MÉTODOS DE PAGO -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($metodos)): ?>
                    <?php foreach ($metodos as $m): ?>
                        <tr>
                            <td><?= $m["id_metodo_pago"] ?></td>
                            <td><?= htmlspecialchars($m["nombre"]) ?></td>
                            <td class="text-center">
                                <a href="index.php?controlador=metodos_pago&accion=editar&id=<?= $m['id_metodo_pago'] ?>"
                                 class="btn btn-sm btn-outline-primary me-1">
                                   <i class="bi bi-pencil-square"></i>
                                </a>
                               <a href="../index.php?controlador=metodos_pago&accion=eliminar&id=<?= $m['id_metodo_pago'] ?>"

                                   onclick="return confirm('¿Eliminar método de pago?')"
                                   class="btn btn-sm btn-outline-danger">
                                   <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-3">
                            No hay métodos de pago registrados.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
