<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('admin');
require 'conexion.php';

$idUsuario = filter_input(INPUT_GET, 'id_usuario', FILTER_VALIDATE_INT);

$stmt = $conexion->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

if (!$usuario) {
    die("Usuario no encontrado.");
}

$medico = null;
$diasActivos = [];
$horaInicio = '08:00';
$horaFin = '17:00';

$consultorios = [];

if ($usuario['rol_usuario'] === 'medico') {
    $consultorios = $conexion->query("SELECT id_consultorio, numero, ubicacion FROM consultorio WHERE disponibilidad = 1 ORDER BY numero")->fetch_all(MYSQLI_ASSOC);

    $stmtMed = $conexion->prepare("SELECT especialidad_medico, numero_tarjeta_profesional, id_consultorio FROM medico WHERE id_medico = ?");
    $stmtMed->bind_param("i", $idUsuario);
    $stmtMed->execute();
    $medico = $stmtMed->get_result()->fetch_assoc();

    $stmtHor = $conexion->prepare("SELECT dia_semana, hora_inicio, hora_fin FROM horario_medico WHERE id_medico = ?");
    $stmtHor->bind_param("i", $idUsuario);
    $stmtHor->execute();
    $horarios = $stmtHor->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach ($horarios as $h) {
        $diasActivos[] = (int) $h['dia_semana'];
    }
    if (!empty($horarios)) {
        $horaInicio = substr($horarios[0]['hora_inicio'], 0, 5);
        $horaFin = substr($horarios[0]['hora_fin'], 0, 5);
    }
}

$dias = [1 => 'Lun', 2 => 'Mar', 3 => 'Mié', 4 => 'Jue', 5 => 'Vie', 6 => 'Sáb', 7 => 'Dom'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Usuario | MEDISOFT</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/registrar_empleado.css" />
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

  <div class="main-wrapper">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
          <div class="form-container">
            <h3>Editar Usuario</h3>
            <p class="text-center text-muted small mb-3">
              Rol: <strong><?= htmlspecialchars(ucfirst($usuario['rol_usuario'])) ?></strong>
              (el rol no se puede cambiar desde aquí)
            </p>

            <form action="guardar_edicion_usuario.php" method="POST">
              <input type="hidden" name="id_usuario" value="<?= (int) $usuario['id_usuario'] ?>">

              <div class="mb-3 input-icon-wrapper">
                <i class="fas fa-id-card fa-icon-left"></i>
                <select name="tipo_documento_usuario" class="form-select" required>
                  <option value="CC" <?= $usuario['tipo_documento_usuario'] === 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                  <option value="CE" <?= $usuario['tipo_documento_usuario'] === 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                  <option value="TI" <?= $usuario['tipo_documento_usuario'] === 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                </select>
              </div>

              <div class="mb-3 input-icon-wrapper">
                <i class="fas fa-user fa-icon-left"></i>
                <input type="number" name="documento_usuario" class="form-control" required
                  value="<?= htmlspecialchars($usuario['documento_usuario']) ?>" placeholder="Número de documento" />
              </div>

              <div class="row gx-2">
                <div class="col-6 mb-3 input-icon-wrapper">
                  <i class="fas fa-user fa-icon-left"></i>
                  <input type="text" name="nombre_usuario" class="form-control" required
                    value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>" placeholder="Nombres" />
                </div>
                <div class="col-6 mb-3 input-icon-wrapper">
                  <i class="fas fa-user fa-icon-left"></i>
                  <input type="text" name="apellido_usuario" class="form-control" required
                    value="<?= htmlspecialchars($usuario['apellido_usuario']) ?>" placeholder="Apellidos" />
                </div>
              </div>

              <div class="mb-3 input-icon-wrapper">
                <i class="fas fa-location-dot fa-icon-left"></i>
                <input type="text" name="direccion_usuario" class="form-control" required
                  value="<?= htmlspecialchars($usuario['direccion_usuario']) ?>" placeholder="Dirección" />
              </div>

              <div class="mb-3 input-icon-wrapper">
                <i class="fas fa-mobile-alt fa-icon-left"></i>
                <input type="text" name="telefono_usuario" class="form-control" required
                  value="<?= htmlspecialchars($usuario['telefono_usuario']) ?>" placeholder="Teléfono" />
              </div>

              <div class="mb-3 input-icon-wrapper">
                <i class="fas fa-envelope fa-icon-left"></i>
                <input type="email" name="correo_electronico_usuario" class="form-control" required
                  value="<?= htmlspecialchars($usuario['correo_electronico_usuario']) ?>" placeholder="Correo" />
              </div>

              <?php if ($usuario['rol_usuario'] === 'medico'): ?>
                <hr>
                <div class="mb-3 input-icon-wrapper">
                  <i class="fas fa-stethoscope fa-icon-left"></i>
                  <input type="text" name="especialidad" class="form-control" required
                    value="<?= htmlspecialchars($medico['especialidad_medico'] ?? '') ?>" placeholder="Especialidad médica" />
                </div>

                <div class="mb-3 input-icon-wrapper">
                  <i class="fas fa-id-badge fa-icon-left"></i>
                  <input type="number" name="tarjeta_profesional" class="form-control" required
                    value="<?= htmlspecialchars($medico['numero_tarjeta_profesional'] ?? '') ?>" placeholder="Número de tarjeta profesional" />
                </div>

                <div class="mb-3 input-icon-wrapper">
                  <i class="fas fa-door-open fa-icon-left"></i>
                  <select name="id_consultorio" class="form-select" required>
                    <option value="" disabled <?= empty($medico['id_consultorio']) ? 'selected' : '' ?>>Consultorio asignado</option>
                    <?php foreach ($consultorios as $c): ?>
                      <option value="<?= (int) $c['id_consultorio'] ?>" <?= (int) ($medico['id_consultorio'] ?? 0) === (int) $c['id_consultorio'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['numero'] . ' - ' . $c['ubicacion']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <label class="form-label d-block">Horario de atención</label>
                <div class="mb-2 d-flex flex-wrap gap-3">
                  <?php foreach ($dias as $num => $label): ?>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="dias[]" value="<?= $num ?>" id="dia<?= $num ?>"
                        <?= in_array($num, $diasActivos) ? 'checked' : '' ?>>
                      <label class="form-check-label" for="dia<?= $num ?>"><?= $label ?></label>
                    </div>
                  <?php endforeach; ?>
                </div>
                <div class="row gx-2">
                  <div class="col-6 mb-3 input-icon-wrapper">
                    <i class="fas fa-clock fa-icon-left"></i>
                    <input type="time" name="hora_inicio" class="form-control" value="<?= htmlspecialchars($horaInicio) ?>">
                  </div>
                  <div class="col-6 mb-3 input-icon-wrapper">
                    <i class="fas fa-clock fa-icon-left"></i>
                    <input type="time" name="hora_fin" class="form-control" value="<?= htmlspecialchars($horaFin) ?>">
                  </div>
                </div>
              <?php endif; ?>

              <button type="submit" class="btn custom-register-btn w-100">GUARDAR CAMBIOS</button>
            </form>

            <div class="text-center mt-3">
              <a href="gestionar_usuarios.php" class="link-info">Volver</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
</body>

</html>
