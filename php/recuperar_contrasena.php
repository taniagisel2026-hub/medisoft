<?php
session_start();
require_once __DIR__ . '/csrf.php';
$mensaje = $_SESSION['mensaje_recuperacion'] ?? null;
unset($_SESSION['mensaje_recuperacion']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Recuperar contraseña | MEDISOFT</title>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/login.css" />
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="../index.html">
        <img src="../imagenes/logo medisoft.png" height="50" alt="Logo" loading="lazy" />
        <img src="../imagenes/letra medisoft.png" class="brand-text d-none d-sm-block" alt="Medisoft" />
      </a>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row vh-100">
      <div class="col-md-6 d-none d-md-block">
        <img src="../imagenes/PANEL MEDISOFT.png" alt="Login image" class="img-fluid"
          style="width: 100%; height: calc(100vh - 56px); object-fit: cover;">
      </div>

      <div class="col-md-6 d-flex align-items-center justify-content-center bg-light">
        <div class="form-container bg-white p-4 shadow rounded mx-auto" style="max-width: 420px; width: 100%;">

          <h3 class="text-center mb-3">Recuperar contraseña</h3>
          <p class="text-muted text-center small mb-4">
            Ingresa tu documento y el correo con el que te registraste para poder crear una nueva contraseña.
          </p>

          <?php if ($mensaje): ?>
            <div class="alert alert-danger small"><?= htmlspecialchars($mensaje) ?></div>
          <?php endif; ?>

          <form action="enviar_recuperacion.php" method="POST">
            <?= csrf_campo() ?>
            <div class="mb-3 input-icon-wrapper">
              <i class="fas fa-id-card fa-icon-left"></i>
              <select class="form-select" name="tipo_identificacion" required>
                <option selected disabled value="">Tipo de identificación</option>
                <option value="CC">Cédula de Ciudadanía</option>
                <option value="CE">Cédula de Extranjería</option>
                <option value="TI">Tarjeta de Identidad</option>
              </select>
            </div>

            <div class="mb-3 input-icon-wrapper">
              <i class="fas fa-user fa-icon-left"></i>
              <input type="number" class="form-control" name="numero_documento" placeholder="Número de documento" required />
            </div>

            <div class="mb-3 input-icon-wrapper">
              <i class="fas fa-envelope fa-icon-left"></i>
              <input type="email" class="form-control" name="correo" placeholder="Correo registrado" required />
            </div>

            <button type="submit" class="btn custom-register-btn w-100">CONTINUAR</button>
          </form>

          <p class="text-center mt-3">
            <a href="../login.html" class="link-info">Volver a iniciar sesión</a>
          </p>

        </div>
      </div>
    </div>
  </div>
</body>

</html>
