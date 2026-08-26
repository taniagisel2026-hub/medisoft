<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol(['paciente', 'recepcionista', 'medico']);
require 'conexion.php';

$rol = $_SESSION['rol'];

if ($rol === 'paciente') {
    $stmt = $conexion->prepare("
        SELECT c.id_cita, c.fecha_cita, c.hora, c.estado_cita, c.motivo,
               um.nombre_usuario AS medico_nombre, um.apellido_usuario AS medico_apellido, m.especialidad_medico
        FROM cita c
        JOIN medico m ON m.id_medico = c.id_medico
        JOIN usuario um ON um.id_usuario = m.id_medico
        WHERE c.id_paciente = ?
        ORDER BY c.fecha_cita DESC, c.hora DESC
    ");
    $stmt->bind_param("i", $_SESSION['id']);
} elseif ($rol === 'medico') {
    $stmt = $conexion->prepare("
        SELECT c.id_cita, c.fecha_cita, c.hora, c.estado_cita, c.motivo,
               up.nombre_usuario AS paciente_nombre, up.apellido_usuario AS paciente_apellido,
               up.documento_usuario AS paciente_documento
        FROM cita c
        JOIN usuario up ON up.id_usuario = c.id_paciente
        WHERE c.id_medico = ?
        ORDER BY c.fecha_cita DESC, c.hora DESC
    ");
    $stmt->bind_param("i", $_SESSION['id']);
} else { // recepcionista: ve todas
    $stmt = $conexion->prepare("
        SELECT c.id_cita, c.fecha_cita, c.hora, c.estado_cita, c.motivo,
               up.nombre_usuario AS paciente_nombre, up.apellido_usuario AS paciente_apellido,
               up.documento_usuario AS paciente_documento,
               um.nombre_usuario AS medico_nombre, um.apellido_usuario AS medico_apellido, m.especialidad_medico
        FROM cita c
        JOIN usuario up ON up.id_usuario = c.id_paciente
        JOIN medico m ON m.id_medico = c.id_medico
        JOIN usuario um ON um.id_usuario = m.id_medico
        ORDER BY c.fecha_cita DESC, c.hora DESC
    ");
}

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
  <title>Mis Citas | MEDISOFT</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/dashboard.css" />
  <style>
    .input-icon-wrapper { position: relative; }
    .input-icon-wrapper i.fa-icon-left {
      position: absolute; top: 50%; left: 14px; transform: translateY(-50%);
      color: #0a4c84; pointer-events: none;
    }
    .input-icon-wrapper input { padding-left: 2.4rem; }
  </style>
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

  <div class="container mt-4 mb-5">
    <h3 class="text-center mb-4"><?= $rol === 'paciente' ? 'Mis Citas' : 'Citas Agendadas' ?></h3>

    <?php if ($rol !== 'paciente' && !empty($citas)): ?>
      <div class="row justify-content-center mb-3">
        <div class="col-12 col-md-6 input-icon-wrapper">
          <i class="fas fa-search fa-icon-left"></i>
          <input type="text" id="buscarCitaPaciente" class="form-control" placeholder="Buscar por nombre o documento del paciente..." />
        </div>
      </div>
    <?php endif; ?>

    <?php if (empty($citas)): ?>
      <p class="text-center text-muted">No hay citas registradas todavía.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-striped align-middle" id="tablaCitas">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Hora</th>
              <?php if ($rol !== 'paciente'): ?><th>Paciente</th><th>Documento</th><?php endif; ?>
              <?php if ($rol !== 'medico'): ?><th>Médico</th><th>Especialidad</th><?php endif; ?>
              <th>Motivo</th>
              <th>Estado</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($citas as $c): ?>
              <tr<?php if ($rol !== 'paciente'): ?> data-paciente="<?= htmlspecialchars(mb_strtolower($c['paciente_nombre'] . ' ' . $c['paciente_apellido'] . ' ' . $c['paciente_documento'])) ?>"<?php endif; ?>>
                <td><?= htmlspecialchars($c['fecha_cita']) ?></td>
                <td><?= substr(htmlspecialchars($c['hora']), 0, 5) ?></td>
                <?php if ($rol !== 'paciente'): ?>
                  <td><?= htmlspecialchars($c['paciente_nombre'] . ' ' . $c['paciente_apellido']) ?></td>
                  <td><?= htmlspecialchars($c['paciente_documento']) ?></td>
                <?php endif; ?>
                <?php if ($rol !== 'medico'): ?>
                  <td><?= htmlspecialchars($c['medico_nombre'] . ' ' . $c['medico_apellido']) ?></td>
                  <td><?= htmlspecialchars($c['especialidad_medico']) ?></td>
                <?php endif; ?>
                <td><?= htmlspecialchars($c['motivo'] ?? '—') ?></td>
                <td><span class="badge <?= $badges[$c['estado_cita']] ?>"><?= ucfirst($c['estado_cita']) ?></span></td>
                <td>
                  <?php if ($c['estado_cita'] === 'pendiente' && $rol !== 'medico'): ?>
                    <form action="cancelar_cita.php" method="POST" onsubmit="return confirm('¿Cancelar esta cita?');">
                      <input type="hidden" name="id_cita" value="<?= (int) $c['id_cita'] ?>">
                      <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar</button>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <p id="sinResultadosBusqueda" class="text-center text-muted" style="display:none;">Ningún paciente coincide con la búsqueda.</p>
      </div>
    <?php endif; ?>

    <?php if ($rol !== 'medico'): ?>
      <div class="text-center mt-3">
        <a href="asignar_cita.php" class="btn custom-register-btn">Agendar nueva cita</a>
      </div>
    <?php endif; ?>
  </div>

  <footer class="footer">
    © 2025 MEDISOFT | Sistema de Gestión Médica
  </footer>

  <script>
    const buscador = document.getElementById("buscarCitaPaciente");
    if (buscador) {
      buscador.addEventListener("input", function () {
        const texto = this.value.trim().toLowerCase();
        const filas = document.querySelectorAll("#tablaCitas tbody tr");
        let visibles = 0;
        filas.forEach(fila => {
          const coincide = fila.dataset.paciente.includes(texto);
          fila.style.display = coincide ? "" : "none";
          if (coincide) visibles++;
        });
        document.getElementById("sinResultadosBusqueda").style.display = visibles === 0 ? "block" : "none";
      });
    }
  </script>
</body>

</html>
