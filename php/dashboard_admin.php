<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login.html");
    exit();
}

if ($_SESSION['rol'] !== 'admin') {
    echo "<script>alert('❌ No tienes permisos para acceder aquí'); window.location.href='../index.html';</script>";
    exit();
}

require 'conexion.php';

$citasHoy = $conexion->query("SELECT COUNT(*) AS n FROM cita WHERE fecha_cita = CURDATE() AND estado_cita <> 'cancelada'")->fetch_assoc()['n'];
$citasPendientes = $conexion->query("SELECT COUNT(*) AS n FROM cita WHERE estado_cita = 'pendiente'")->fetch_assoc()['n'];
$totalMedicos = $conexion->query("SELECT COUNT(*) AS n FROM medico")->fetch_assoc()['n'];
$totalPacientes = $conexion->query("SELECT COUNT(*) AS n FROM paciente")->fetch_assoc()['n'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Administrador | MEDISOFT</title>

  <!-- MDBootstrap y FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet">

  <!-- CSS personalizado -->
  <link rel="stylesheet" href="../css/dashboard.css?v=10" />
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
        <li class="nav-item me-2">
          <a class="btn btn-outline-light" href="perfil.php">Mi Perfil</a>
        </li>
        <li class="nav-item me-2">
          <a class="btn btn-outline-light" href="cambiar_contrasena.php">Cambiar contraseña</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-light text-primary" href="#" id="logout">Cerrar sesión</a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Perfil -->
  <div class="perfil-container mt-4">
    <div class="perfil-usuario">
      <div class="inicial" id="inicialUsuario"><?= strtoupper(substr($_SESSION['nombre'], 0, 1)) ?></div>
      <div class="nombre-usuario">
        <p class="nombre" id="nombreUsuario"><?= htmlspecialchars($_SESSION['nombre']) ?></p>
        <p class="rol" id="rolUsuario"><?= ucfirst($_SESSION['rol']) ?></p>
      </div>
    </div>
  </div>

  <!-- Estadísticas -->
  <div class="container mt-5">
    <div class="row g-3">
      <div class="col-6 col-lg-3">
        <div class="stat-card">
          <i class="fas fa-calendar-day"></i>
          <div class="stat-numero"><?= (int) $citasHoy ?></div>
          <div class="stat-label">Citas hoy</div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="stat-card">
          <i class="fas fa-hourglass-half"></i>
          <div class="stat-numero"><?= (int) $citasPendientes ?></div>
          <div class="stat-label">Citas pendientes</div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="stat-card">
          <i class="fas fa-user-md"></i>
          <div class="stat-numero"><?= (int) $totalMedicos ?></div>
          <div class="stat-label">Médicos activos</div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="stat-card">
          <i class="fas fa-users"></i>
          <div class="stat-numero"><?= (int) $totalPacientes ?></div>
          <div class="stat-label">Pacientes registrados</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Panel -->
  <div class="container-fluid px-4 mt-5 mb-5">
    <h3>Panel del Administrador</h3>
    <div class="row g-4 mt-3 cards-5" id="panelOpciones"></div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    © 2025 MEDISOFT | Sistema de Gestión Médica
  </footer>

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
  <script src="../js/dashboard_admin.js?v=4"></script>
  <script src="../js/bfcache_guard.js"></script>
</body>
</html>
