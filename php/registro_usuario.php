<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('recepcionista');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Usuario | MEDISOFT</title>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/registro.css" />
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
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="btn btn-light text-primary" href="cerrar_sesion.php" id="logout">Cerrar sesión</a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Registro de usuario -->
  <div class="main-wrapper">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
          <div class="form-container">
            <h3>Registrar Paciente</h3>
            <p class="text-muted small">Registra a un paciente que llega directamente al mostrador.</p>

            <form id="usuarioform" action="procesar_registro_paciente.php" method="POST">
            <?= csrf_campo() ?>
            <!-- Tipo de identificación -->
            <div class="mb-3 input-icon-wrapper">
              <i class="fas fa-id-card fa-icon-left"></i>
              <select class="form-select" name="tipo_documento_usuario" required>
                <option selected disabled>Tipo de identificación</option>
                <option value="CC">Cédula de Ciudadanía</option>
                <option value="CE">Cédula de Extranjería</option>
                <option value="TI">Tarjeta de Identidad</option>
              </select>
            </div>

            <!-- Número de documento -->
            <div class="mb-3 input-icon-wrapper">
              <i class="fas fa-user fa-icon-left"></i>
              <input type="number" class="form-control" name="documento_usuario" placeholder="Número de documento"
                required />
            </div>

            <!-- Nombres -->
            <div class="mb-3 input-icon-wrapper">
              <i class="fas fa-user fa-icon-left"></i>
              <input type="text" class="form-control" name="nombre_usuario" placeholder="Nombres" required />
            </div>

            <!-- Apellidos -->
            <div class="mb-3 input-icon-wrapper">
              <i class="fas fa-user fa-icon-left"></i>
              <input type="text" class="form-control" name="apellido_usuario" placeholder="Apellidos" required />
            </div>

            <!-- Correo electrónico -->
            <div class="mb-3 input-icon-wrapper">
              <i class="fas fa-envelope fa-icon-left"></i>
              <input type="email" class="form-control" id="correo" name="correo_electronico_usuario"
                placeholder="Correo electrónico" required />
            </div>

            <!-- Dirección  -->
            <div class="mb-3 input-icon-wrapper">
              <i class="fas fa-location-dot fa-icon-left"></i>
              <input type="text" class="form-control" name="direccion_usuario" placeholder="Dirección" required />
            </div>

            <!-- Teléfono  -->
            <div class="mb-3 input-icon-wrapper">
              <i class="fas fa-phone fa-icon-left"></i>
              <input type="number" class="form-control" name="telefono_usuario" placeholder="Teléfono" required />
            </div>

            <!-- Fecha de nacimiento -->
            <div class="mb-3 input-icon-wrapper">
              <i class="fas fa-cake-candles fa-icon-left"></i>
              <input type="date" class="form-control" name="fecha_nacimiento" required />
            </div>

            <!-- EPS -->
            <div class="mb-3 input-icon-wrapper">
              <i class="fas fa-notes-medical fa-icon-left"></i>
              <input type="text" class="form-control" name="eps_paciente" placeholder="EPS" required />
            </div>

            <!-- Sexo -->
            <div class="mb-3 input-icon-wrapper">
              <i class="fas fa-venus-mars fa-icon-left"></i>
              <select class="form-select" name="sexo" required>
                <option selected disabled value="">Sexo</option>
                <option value="Femenino">Femenino</option>
                <option value="Masculino">Masculino</option>
                <option value="Otro">Otro</option>
              </select>
            </div>

            <!-- Contraseña -->
            <div class="mb-3 input-icon-wrapper">
              <i class="fas fa-lock fa-icon-left"></i>
              <input type="password" class="form-control" id="password" name="contrasena_usuario"
                placeholder="Contraseña" autocomplete="new-password" required />
              <i class="fas fa-eye fa-icon-right" onclick="togglePassword('password', this)"></i>
            </div>

            <!-- Confirmar Contraseña -->
            <div class="mb-3 input-icon-wrapper">
              <i class="fas fa-lock fa-icon-left"></i>
              <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                placeholder="Confirmar contraseña" autocomplete="new-password" required />
              <i class="fas fa-eye fa-icon-right" onclick="togglePassword('confirmPassword', this)"></i>
            </div>

            <!-- Botón -->
            <button type="submit" class="btn custom-register-btn w-100">REGISTRAR</button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="../js/registro.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
  <script src="../js/bfcache_guard.js"></script>
</body>

</html>