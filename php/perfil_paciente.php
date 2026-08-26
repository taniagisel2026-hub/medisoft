<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('paciente');
require 'conexion.php';

$stmt = $conexion->prepare("
    SELECT u.tipo_documento_usuario, u.documento_usuario, u.nombre_usuario, u.apellido_usuario,
           u.correo_electronico_usuario, u.direccion_usuario, u.telefono_usuario,
           p.fecha_nacimiento, p.eps_paciente, p.sexo
    FROM usuario u
    LEFT JOIN paciente p ON p.id_paciente = u.id_usuario
    WHERE u.id_usuario = ?
");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$perfil = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mi Perfil | MEDISOFT</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/dashboard.css" />
  <link rel="stylesheet" href="../css/perfil_paciente.css" />
</head>

<body>
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
          <a class="btn btn-light text-primary" href="cerrar_sesion.php">Cerrar sesión</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container mt-4 mb-5 perfil-page">

    <!-- Encabezado -->
    <div class="perfil-header">
      <div class="avatar"><?= strtoupper(substr($perfil['nombre_usuario'], 0, 1)) ?></div>
      <div>
        <h2><?= htmlspecialchars($perfil['nombre_usuario'] . ' ' . $perfil['apellido_usuario']) ?></h2>
        <span class="rol-badge"><i class="fas fa-user"></i> Paciente</span>
      </div>
    </div>

    <?php if (isset($_GET['guardado'])): ?>
      <div class="alert alert-success text-center">Tus datos se actualizaron correctamente.</div>
    <?php endif; ?>

    <!-- Datos personales -->
    <div class="perfil-card">
      <h5><i class="fas fa-address-card"></i> Datos Personales</h5>

      <div class="perfil-item">
        <i class="fas fa-id-card"></i>
        <div>
          <span class="label">Documento</span>
          <span class="value"><?= htmlspecialchars($perfil['tipo_documento_usuario'] . ' ' . $perfil['documento_usuario']) ?></span>
        </div>
      </div>

      <div class="perfil-item">
        <i class="fas fa-cake-candles"></i>
        <div>
          <span class="label">Fecha de nacimiento</span>
          <span class="value"><?= htmlspecialchars($perfil['fecha_nacimiento'] ?? 'No registrada') ?></span>
        </div>
      </div>

      <div class="perfil-item">
        <i class="fas fa-venus-mars"></i>
        <div>
          <span class="label">Sexo</span>
          <span class="value"><?= htmlspecialchars($perfil['sexo'] ?? 'No registrado') ?></span>
        </div>
      </div>

      <p class="text-muted small mt-3 mb-2">Estos datos no se pueden editar aquí. Los siguientes sí:</p>

      <form action="guardar_perfil_paciente.php" method="POST">
      <?= csrf_campo() ?>
        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-envelope fa-icon-left"></i>
          <input type="email" class="form-control" name="correo_electronico_usuario" required
            value="<?= htmlspecialchars($perfil['correo_electronico_usuario']) ?>" placeholder="Correo" />
        </div>

        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-location-dot fa-icon-left"></i>
          <input type="text" class="form-control" name="direccion_usuario" required
            value="<?= htmlspecialchars($perfil['direccion_usuario']) ?>" placeholder="Dirección" />
        </div>

        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-phone fa-icon-left"></i>
          <input type="text" class="form-control" name="telefono_usuario" required
            value="<?= htmlspecialchars($perfil['telefono_usuario']) ?>" placeholder="Teléfono" />
        </div>

        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-shield-heart fa-icon-left"></i>
          <input type="text" class="form-control" name="eps_paciente" required
            value="<?= htmlspecialchars($perfil['eps_paciente'] ?? '') ?>" placeholder="EPS" />
        </div>

        <button type="submit" class="btn custom-register-btn">Guardar cambios</button>
      </form>
    </div>

  </div>

  <footer class="footer">
    © 2025 MEDISOFT | Sistema de Gestión Médica
  </footer>
  <script src="../js/bfcache_guard.js"></script>
</body>

</html>
