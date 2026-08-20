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
          <a class="btn btn-light text-primary" href="#" id="logout">Cerrar sesión</a>
        </li>
      </ul>
    </div>
  </nav>
  <!-- Fin navbar -->

  <!-- Registrar consultorio -->
  <div class="form-wrapper">
    <div class="form-container">
      <h3>Registrar Consultorio</h3>
      <form id="consultorioForm">
        <!-- Número de consultorio -->
        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-house-medical fa-icon-left"></i>
          <input type="number" class="form-control" name="numero_consultorio" id="numero_consultorio"
            placeholder="Número de consultorio" required />
        </div>

        <!-- Ubicación -->
        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-map-marker-alt fa-icon-left"></i>
          <select class="form-select" name="ubicacion" id="ubicacion" autocomplete="off" required>
            <option selected disabled>Ubicación</option>
            <option value="1">1 piso</option>
            <option value="2">2 piso</option>
            <option value="3">3 piso</option>
          </select>
        </div>

        <!-- Especialidad médica -->
        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-stethoscope fa-icon-left"></i>
          <select class="form-select" name="especialidad" id="especialidad" autocomplete="off" required>
            <option selected disabled>Especialidad médica</option>
            <option value="medicina_general">Medicina general</option>
            <option value="cardiologia">Cardiología</option>
            <option value="dermatologia">Dermatología</option>
            <option value="oftalmologia">Oftalmología</option>
          </select>
        </div>

        <!-- Botón -->
        <button type="submit" class="btn custom-register-btn w-100">REGISTRAR</button>
      </form>

    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
  <script src="../js/registrar_consultorio.js"></script>
</body>

</html>