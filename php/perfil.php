<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol(['medico', 'recepcionista', 'admin']);
require 'conexion.php';

$stmt = $conexion->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

$medico = null;
$horarios = [];
$dias = [1 => 'Lun', 2 => 'Mar', 3 => 'Mié', 4 => 'Jue', 5 => 'Vie', 6 => 'Sáb', 7 => 'Dom'];

if ($usuario['rol_usuario'] === 'medico') {
    $stmtMed = $conexion->prepare("
        SELECT m.especialidad_medico, m.numero_tarjeta_profesional, c.numero AS consultorio_numero, c.ubicacion AS consultorio_ubicacion
        FROM medico m
        LEFT JOIN consultorio c ON c.id_consultorio = m.id_consultorio
        WHERE m.id_medico = ?
    ");
    $stmtMed->bind_param("i", $_SESSION['id']);
    $stmtMed->execute();
    $medico = $stmtMed->get_result()->fetch_assoc();

    $stmtHor = $conexion->prepare("SELECT dia_semana, hora_inicio, hora_fin FROM horario_medico WHERE id_medico = ? ORDER BY dia_semana");
    $stmtHor->bind_param("i", $_SESSION['id']);
    $stmtHor->execute();
    $horarios = $stmtHor->get_result()->fetch_all(MYSQLI_ASSOC);
}

$rolLabel = ['admin' => 'Administrador', 'medico' => 'Médico', 'recepcionista' => 'Recepcionista'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mi Perfil | MEDISOFT</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/dashboard.css?v=10" />
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
        <li class="nav-item">
          <a class="btn btn-light text-primary" href="cerrar_sesion.php">Cerrar sesión</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container mt-4 mb-5 perfil-page">

    <div class="perfil-header">
      <div class="avatar"><?= strtoupper(substr($usuario['nombre_usuario'], 0, 1)) ?></div>
      <div>
        <h2><?= htmlspecialchars($usuario['nombre_usuario'] . ' ' . $usuario['apellido_usuario']) ?></h2>
        <span class="rol-badge"><i class="fas fa-user"></i> <?= htmlspecialchars($rolLabel[$usuario['rol_usuario']] ?? $usuario['rol_usuario']) ?></span>
      </div>
    </div>

    <?php if (isset($_GET['guardado'])): ?>
      <div class="alert alert-success text-center">Tus datos se actualizaron correctamente.</div>
    <?php endif; ?>

    <div class="perfil-card">
      <h5><i class="fas fa-address-card"></i> Datos Personales</h5>

      <div class="perfil-item">
        <i class="fas fa-id-card"></i>
        <div>
          <span class="label">Documento</span>
          <span class="value"><?= htmlspecialchars($usuario['tipo_documento_usuario'] . ' ' . $usuario['documento_usuario']) ?></span>
        </div>
      </div>

      <?php if ($medico): ?>
        <div class="perfil-item">
          <i class="fas fa-stethoscope"></i>
          <div>
            <span class="label">Especialidad</span>
            <span class="value"><?= htmlspecialchars($medico['especialidad_medico']) ?></span>
          </div>
        </div>
        <div class="perfil-item">
          <i class="fas fa-id-badge"></i>
          <div>
            <span class="label">Tarjeta profesional</span>
            <span class="value"><?= htmlspecialchars($medico['numero_tarjeta_profesional']) ?></span>
          </div>
        </div>
        <div class="perfil-item">
          <i class="fas fa-door-open"></i>
          <div>
            <span class="label">Consultorio</span>
            <span class="value"><?= $medico['consultorio_numero'] ? htmlspecialchars($medico['consultorio_numero'] . ' - ' . $medico['consultorio_ubicacion']) : 'Sin asignar' ?></span>
          </div>
        </div>
        <div class="perfil-item">
          <i class="fas fa-clock"></i>
          <div>
            <span class="label">Horario</span>
            <span class="value">
              <?php if (empty($horarios)): ?>
                Sin horario asignado
              <?php else: ?>
                <?php foreach ($horarios as $h): ?>
                  <?= $dias[(int) $h['dia_semana']] ?> <?= substr($h['hora_inicio'], 0, 5) ?>–<?= substr($h['hora_fin'], 0, 5) ?><br>
                <?php endforeach; ?>
              <?php endif; ?>
            </span>
          </div>
        </div>
        <p class="text-muted small mt-2">Estos datos profesionales solo puede cambiarlos un administrador desde "Gestionar Usuarios".</p>
        <hr>
      <?php endif; ?>

      <p class="text-muted small mb-2">Puedes actualizar tu correo, dirección y teléfono:</p>

      <form action="guardar_perfil.php" method="POST">
        <?= csrf_campo() ?>
        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-envelope fa-icon-left"></i>
          <input type="email" class="form-control" name="correo_electronico_usuario" required
            value="<?= htmlspecialchars($usuario['correo_electronico_usuario']) ?>" placeholder="Correo" />
        </div>

        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-location-dot fa-icon-left"></i>
          <input type="text" class="form-control" name="direccion_usuario" required
            value="<?= htmlspecialchars($usuario['direccion_usuario']) ?>" placeholder="Dirección" />
        </div>

        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-phone fa-icon-left"></i>
          <input type="text" class="form-control" name="telefono_usuario" required
            value="<?= htmlspecialchars($usuario['telefono_usuario']) ?>" placeholder="Teléfono" />
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
