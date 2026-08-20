<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('admin');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Consultorio | MEDISOFT</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
  <link href="../css/registrar_consultorio.css" rel="stylesheet" />
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
    <div class="container-fluid">
      <!-- Logo y texto -->
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../imagenes/logo medisoft.png" height="50" alt="Logo" loading="lazy" />
        <img src="../imagenes/letra medisoft.png" class="brand-text d-none d-sm-block" alt="Medisoft" />
      </a>

      <!-- Botón hamburguesa para móvil -->
      <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>

      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="btn btn-light text-primary" href="cerrar_sesion.php" id="logout">Cerrar sesión</a>
        </li>
      </ul>
    </div>
  </nav>
  <!-- Fin navbar -->

  <!-- Registrar consultorio -->
  <div class="form-wrapper">
    <div class="form-container">
      <h3>Registrar Consultorio</h3>
      <form id="consultorioForm" action="guardar_consultorio.php" method="POST">
        <!-- Número de consultorio -->
        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-house-medical fa-icon-left"></i>
          <input type="number" class="form-control" name="numero" id="numero_consultorio"
            placeholder="Número de consultorio" required />
        </div>

        <!-- Ubicación -->
        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-map-marker-alt fa-icon-left"></i>
          <select class="form-select" name="ubicacion" id="ubicacion" autocomplete="off" required>
            <option selected disabled value="">Ubicación</option>
            <option value="Piso 1">Piso 1</option>
            <option value="Piso 2">Piso 2</option>
            <option value="Piso 3">Piso 3</option>
          </select>
        </div>

        <!-- Botón -->
        <button type="submit" class="btn custom-register-btn w-100">REGISTRAR</button>
      </form>

    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
</body>

</html>