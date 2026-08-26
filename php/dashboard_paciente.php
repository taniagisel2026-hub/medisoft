<?php
// Evitar que la página quede en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

session_start();

// Validar sesión
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'paciente') {
    header("Location: ../index.html");
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard | MEDISOFT</title>

  <!-- MDBootstrap y FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet">

  <!-- CSS personalizado -->
  <link rel="stylesheet" href="../css/dashboard.css?v=5" />
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../imagenes/logo medisoft.png" height="50" alt="Logo" loading="lazy" />
        <img src="../imagenes/letra medisoft.png" class="brand-text d-none d-sm-block" alt="Medisoft" />
      </a>

      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="btn btn-light text-primary" href="#" id="logout">Cerrar sesión</a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Perfil del usuario -->
  <div class="perfil-container mt-4">
    <div class="perfil-usuario">
      
      <!-- Inicial del usuario -->
      <div class="inicial" id="inicialUsuario">
        <?= strtoupper(substr($_SESSION['nombre'], 0, 1)) ?>
      </div>

      <div class="nombre-usuario">
        <p class="nombre" id="nombreUsuario"><?= htmlspecialchars($_SESSION['nombre']) ?></p>
        <p class="rol" id="rolUsuario"><?= ucfirst($_SESSION['rol']) ?></p>
      </div>

    </div>
  </div>

  <!-- Contenido dinámico -->
  <div class="container mt-5 mb-5">
    <h3 class="text-center">Panel de Usuario</h3>
    <div class="row g-4 mt-3" id="panelOpciones"></div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    © 2025 MEDISOFT | Sistema de Gestión Médica
  </footer>

  <!-- Scripts -->
   <script>
window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
        window.location.reload();
    }
});
</script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
  <script src="../js/dashboard_paciente.js"></script>
</body>
</html>
