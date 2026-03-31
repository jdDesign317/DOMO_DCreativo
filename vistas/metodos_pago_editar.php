<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Método de Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<div class="container mt-5 pt-4">
    <h2 class="text-danger fw-bold mb-4">Editar Método de Pago</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (!isset($metodo)): ?>
                <div class="alert alert-danger">Error: No se encontró el método de pago.</div>
            <?php else: ?>
                <form action="index.php?controlador=metodos_pago&accion=actualizar" method="POST" class="row g-3">
                    <!-- Campo oculto con el ID -->
                    <input type="hidden" name="id" value="<?= $metodo["id_metodo_pago"] ?>">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nombre</label>
                        <input type="text" name="nombre" class="form-control" 
                               value="<?= htmlspecialchars($metodo["nombre"]) ?>" required>
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="bi bi-save me-1"></i> Guardar Cambios
                        </button>
                        <a href="index.php?controlador=metodos_pago&accion=listar" class="btn btn-secondary ms-2">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
