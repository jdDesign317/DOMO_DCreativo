<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center" style="height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow-lg border-0 rounded-4 p-4">
                
                <h3 class="text-center mb-2">Recuperar contraseña</h3>
                <p class="text-center text-muted mb-4">
                    Ingresá tu correo para recibir el código 📩
                </p>

                <?= $mensaje ?? '' ?>

                 <form method="POST" action="procesar_forgot.php">   
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Correo electrónico</label>
                        <input type="email" name="email" class="form-control" placeholder="ejemplo@correo.com" required>
                    </div>

                    <!-- BOTÓN EN ROJO (como tu login) -->
                    <button type="submit" class="btn btn-danger w-100 mt-2">
                        Enviar código
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="login.php" class="text-danger fw-semibold text-decoration-none">
                        ← Volver al inicio
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>

</body>
</html>