<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol(['paciente', 'recepcionista']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ASIGNAR CITA MEDISOFT</title>


  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/asignar_cita.css" />
  <link rel="icon"
    href="data:image/x-icon;base64,AAABAAEAEBAAAAAAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEABAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=">


</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../imagenes/logo medisoft.png" height="50" alt="Logo" loading="lazy" />
        <img src="../imagenes/letra medisoft.png" class="brand-text d-none d-sm-block" alt="Medisoft" />
      </a>

      <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>
  </nav>
  <!-- Fin navbar -->

  <!-- Asignar Cita -->
  <div class="form-wrapper">
    <div class="form-container">
      <h3>Asignar Cita</h3>
      <form>
        <!-- Especialidad -->
        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-stethoscope fa-icon-left"></i>
          <select class="form-select" id="especialidad" name="especialidad">
            <option selected disabled>Selecciona la especialidad</option>
            <option>Medicina general</option>
            <option>Cardiología</option>
            <option>Dermatología</option>
            <option>Oftalmología</option>
          </select>
        </div>

        <!-- Fecha -->
        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-calendar-alt fa-icon-left"></i>
          <input type="text" class="form-control" id="fecha" name="fecha" placeholder="dd/mm/aaaa"
            onfocus="(this.type='date')" />
        </div>

        <!-- Toggle -->
        <div class="form-check form-switch mb-3">
          <input class="form-check-input" type="checkbox" id="citaCercana" name="citaCercana">
          <label class="form-check-label" for="citaCercana"><strong>Cita más cercana</strong></label>
        </div>

        <!-- Sede -->
        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-map-marker-alt fa-icon-left"></i>
          <select class="form-select" id="sede" name="sede">
            <option selected disabled>Selecciona una sede</option>
            <option>Sede Norte</option>
            <option>Sede Sur</option>
          </select>
        </div>

        <!-- Botón -->
        <button type="submit" class="btn custom-register-btn w-100">BUSCAR</button>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="../js/asignar_cita.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
</body>

</html>