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

require 'conexion.php';

$stmtProxima = $conexion->prepare("
    SELECT c.fecha_cita, c.hora, um.nombre_usuario AS medico_nombre, um.apellido_usuario AS medico_apellido,
           m.especialidad_medico, cons.numero AS consultorio_numero, cons.ubicacion AS consultorio_ubicacion
    FROM cita c
    JOIN medico m ON m.id_medico = c.id_medico
    JOIN usuario um ON um.id_usuario = m.id_medico
    LEFT JOIN consultorio cons ON cons.id_consultorio = c.id_consultorio
    WHERE c.id_paciente = ? AND c.estado_cita = 'pendiente'
      AND (c.fecha_cita > CURDATE() OR (c.fecha_cita = CURDATE() AND c.hora >= CURTIME()))
    ORDER BY c.fecha_cita ASC, c.hora ASC
    LIMIT 1
");
$stmtProxima->bind_param("i", $_SESSION['id']);
$stmtProxima->execute();
$proximaCita = $stmtProxima->get_result()->fetch_assoc();
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
          <a class="btn btn-outline-light" href="cambiar_contrasena.php">Cambiar contraseña</a>
        </li>
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

  <?php if ($proximaCita): ?>
    <div class="container mt-4">
      <div class="proxima-cita-banner">
        <i class="fas fa-calendar-check"></i>
        <div>
          <strong>Tu próxima cita:</strong>
          <?= htmlspecialchars(date('d/m/Y', strtotime($proximaCita['fecha_cita']))) ?>
          a las <?= substr(htmlspecialchars($proximaCita['hora']), 0, 5) ?>
          con Dr(a). <?= htmlspecialchars($proximaCita['medico_nombre'] . ' ' . $proximaCita['medico_apellido']) ?>
          (<?= htmlspecialchars($proximaCita['especialidad_medico']) ?>)
          <?php if ($proximaCita['consultorio_numero']): ?>
            — Consultorio <?= htmlspecialchars($proximaCita['consultorio_numero'] . ' - ' . $proximaCita['consultorio_ubicacion']) ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>

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
  <script src="../js/dashboard_paciente.js?v=2"></script>
</body>
</html>
