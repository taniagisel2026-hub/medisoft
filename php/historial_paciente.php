<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol(['medico', 'recepcionista']);
require 'conexion.php';

$idPaciente = filter_input(INPUT_GET, 'id_paciente', FILTER_VALIDATE_INT);

$stmtPaciente = $conexion->prepare("
    SELECT u.nombre_usuario, u.apellido_usuario, u.documento_usuario, p.fecha_nacimiento, p.eps_paciente, p.sexo
    FROM usuario u
    JOIN paciente p ON p.id_paciente = u.id_usuario
    WHERE u.id_usuario = ?
");
$stmtPaciente->bind_param("i", $idPaciente);
$stmtPaciente->execute();
$paciente = $stmtPaciente->get_result()->fetch_assoc();

if (!$paciente) {
    die("Paciente no encontrado.");
}

$stmt = $conexion->prepare("
    SELECT c.fecha_cita, c.hora, c.estado_cita, c.motivo, c.diagnostico, c.notas,
           um.nombre_usuario AS medico_nombre, um.apellido_usuario AS medico_apellido, m.especialidad_medico,
           cons.numero AS consultorio_numero, cons.ubicacion AS consultorio_ubicacion,
           GROUP_CONCAT(DISTINCT CONCAT(med.nombre_medicamento, ' (x', cm.cantidad, ')') SEPARATOR ', ') AS medicamentos_recetados,
           GROUP_CONCAT(DISTINCT trat.nombre_tratamiento SEPARATOR ', ') AS tratamientos_aplicados
    FROM cita c
    JOIN medico m ON m.id_medico = c.id_medico
    JOIN usuario um ON um.id_usuario = m.id_medico
    LEFT JOIN consultorio cons ON cons.id_consultorio = c.id_consultorio
    LEFT JOIN cita_medicamento cm ON cm.id_cita = c.id_cita
    LEFT JOIN medicamento med ON med.id_medicamento = cm.id_medicamento
    LEFT JOIN cita_tratamiento ct ON ct.id_cita = c.id_cita
    LEFT JOIN tratamiento trat ON trat.id_tratamiento = ct.id_tratamiento
    WHERE c.id_paciente = ?
    GROUP BY c.id_cita
    ORDER BY c.fecha_cita DESC, c.hora DESC
");
$stmt->bind_param("i", $idPaciente);
$stmt->execute();
$citas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$badges = [
    'pendiente' => 'bg-warning text-dark',
    'cancelada' => 'bg-danger',
    'atendida'  => 'bg-success',
];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Historial Clínico | MEDISOFT</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/dashboard.css?v=10" />
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../imagenes/logo medisoft.png" height="50" alt="Logo" loading="lazy" />
        <img src="../imagenes/letra medisoft.png" class="brand-text d-none d-sm-block" alt="Medisoft" />
      </a>
    </div>
  </nav>

  <div class="container mt-4 mb-5">
    <h3 class="text-center mb-1">Historial Clínico</h3>
    <p class="text-center text-muted mb-4">
      <?= htmlspecialchars($paciente['nombre_usuario'] . ' ' . $paciente['apellido_usuario']) ?>
      — Documento: <?= htmlspecialchars($paciente['documento_usuario']) ?>
      — <?= htmlspecialchars(ucfirst($paciente['sexo'])) ?>
      — Nacimiento: <?= htmlspecialchars($paciente['fecha_nacimiento']) ?>
      — EPS: <?= htmlspecialchars($paciente['eps_paciente']) ?>
    </p>

    <?php if (empty($citas)): ?>
      <p class="text-center text-muted">Este paciente no tiene citas registradas.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Hora</th>
              <th>Médico</th>
              <th>Especialidad</th>
              <th>Consultorio</th>
              <th>Motivo</th>
              <th>Diagnóstico</th>
              <th>Medicamentos</th>
              <th>Tratamientos</th>
              <th>Notas</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($citas as $c): ?>
              <tr>
                <td><?= htmlspecialchars($c['fecha_cita']) ?></td>
                <td><?= substr(htmlspecialchars($c['hora']), 0, 5) ?></td>
                <td><?= htmlspecialchars($c['medico_nombre'] . ' ' . $c['medico_apellido']) ?></td>
                <td><?= htmlspecialchars($c['especialidad_medico']) ?></td>
                <td><?= $c['consultorio_numero'] ? htmlspecialchars($c['consultorio_numero'] . ' - ' . $c['consultorio_ubicacion']) : 'Por asignar' ?></td>
                <td><?= htmlspecialchars($c['motivo'] ?? '—') ?></td>
                <td><?= htmlspecialchars($c['diagnostico'] ?? '—') ?></td>
                <td><?= htmlspecialchars($c['medicamentos_recetados'] ?? '—') ?></td>
                <td><?= htmlspecialchars($c['tratamientos_aplicados'] ?? '—') ?></td>
                <td><?= htmlspecialchars($c['notas'] ?? '—') ?></td>
                <td><span class="badge <?= $badges[$c['estado_cita']] ?>"><?= ucfirst($c['estado_cita']) ?></span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

    <div class="text-center mt-3">
      <a href="mis_citas.php" class="link-info">Volver</a>
    </div>
  </div>

  <footer class="footer">
    © 2025 MEDISOFT | Sistema de Gestión Médica
  </footer>
  <script src="../js/bfcache_guard.js"></script>
</body>

</html>
