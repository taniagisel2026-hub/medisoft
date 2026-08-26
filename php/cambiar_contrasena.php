<?php
include 'verificar_sesion.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cambiar Contraseña | MEDISOFT</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/login.css" />
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../imagenes/logo medisoft.png" height="50" alt="Logo" loading="lazy" />
        <img src="../imagenes/letra medisoft.png" class="brand-text d-none d-sm-block" alt="Medisoft" />
      </a>
    </div>
  </nav>

  <div class="d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 56px);">
    <div class="form-container bg-white p-4 shadow rounded mx-auto" style="max-width: 420px; width: 100%;">
      <h3 class="text-center mb-4">Cambiar contraseña</h3>

      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger small"><?= htmlspecialchars($_GET['error']) ?></div>
      <?php endif; ?>

      <form action="guardar_cambio_contrasena.php" method="POST">
        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-lock fa-icon-left"></i>
          <input type="password" class="form-control" name="contrasena_actual" placeholder="Contraseña actual" required />
        </div>

        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-lock fa-icon-left"></i>
          <input type="password" class="form-control" name="nueva_contrasena" placeholder="Nueva contraseña" required minlength="6" />
        </div>

        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-lock fa-icon-left"></i>
          <input type="password" class="form-control" name="confirmar_contrasena" placeholder="Confirmar nueva contraseña" required minlength="6" />
        </div>

        <button type="submit" class="btn custom-register-btn w-100">GUARDAR CAMBIOS</button>
      </form>

      <p class="text-center mt-3">
        <a href="javascript:history.back()" class="link-info">Volver</a>
      </p>
    </div>
  </div>
</body>

</html>
