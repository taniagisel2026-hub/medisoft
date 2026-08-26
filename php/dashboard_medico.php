<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}

if ($_SESSION['rol'] !== 'medico') {
    echo "<script>alert('❌ No tienes permisos para acceder aquí'); window.location.href='index.html';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Médico | MEDISOFT</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/dashboard.css?v=5">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../imagenes/logo medisoft.png" height="50" alt="Logo">
        <img src="../imagenes/letra medisoft.png" class="brand-text d-none d-sm-block" alt="Medisoft">
      </a>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="btn btn-light text-primary" href="#" id="logout">Cerrar sesión</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="perfil-container mt-4">
    <div class="perfil-usuario">
      <div class="inicial" id="inicialUsuario"><?= strtoupper(substr($_SESSION['nombre'], 0, 1)) ?></div>
      <div class="nombre-usuario">
        <p class="nombre" id="nombreUsuario"><?= htmlspecialchars($_SESSION['nombre']) ?></p>
        <p class="rol" id="rolUsuario"><?= ucfirst($_SESSION['rol']) ?></p>
      </div>
    </div>
  </div>

  <div class="container mt-5 mb-5">
    <h3>Panel del Médico</h3>
    <div class="row g-4 mt-3" id="panelOpciones"></div>
  </div>

  <footer class="footer">
    © 2025 MEDISOFT | Sistema de Gestión Médica
  </footer>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
  <script src="../js/dashboard_medico.js"></script>
</body>
</html>
