<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol(['paciente', 'recepcionista']);
require 'conexion.php';

$idCita = filter_input(INPUT_GET, 'id_cita', FILTER_VALIDATE_INT);

if ($_SESSION['rol'] === 'paciente') {
    $stmt = $conexion->prepare("
        SELECT c.id_cita, c.id_medico, c.fecha_cita, c.hora,
               um.nombre_usuario AS medico_nombre, um.apellido_usuario AS medico_apellido, m.especialidad_medico
        FROM cita c
        JOIN medico m ON m.id_medico = c.id_medico
        JOIN usuario um ON um.id_usuario = m.id_medico
        WHERE c.id_cita = ? AND c.id_paciente = ? AND c.estado_cita = 'pendiente'
    ");
    $stmt->bind_param("ii", $idCita, $_SESSION['id']);
} else {
    $stmt = $conexion->prepare("
        SELECT c.id_cita, c.id_medico, c.fecha_cita, c.hora,
               um.nombre_usuario AS medico_nombre, um.apellido_usuario AS medico_apellido, m.especialidad_medico
        FROM cita c
        JOIN medico m ON m.id_medico = c.id_medico
        JOIN usuario um ON um.id_usuario = m.id_medico
        WHERE c.id_cita = ? AND c.estado_cita = 'pendiente'
    ");
    $stmt->bind_param("i", $idCita);
}
$stmt->execute();
$cita = $stmt->get_result()->fetch_assoc();

if (!$cita) {
    die("Esta cita no existe, ya no está pendiente, o no te pertenece.");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reagendar Cita | MEDISOFT</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/asignar_cita.css?v=2" />
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

  <div class="form-wrapper">
    <div class="form-container">
      <h3>Reagendar Cita</h3>
      <p class="text-center text-muted small mb-3">
        Con <?= htmlspecialchars($cita['medico_nombre'] . ' ' . $cita['medico_apellido']) ?>
        (<?= htmlspecialchars($cita['especialidad_medico']) ?>)
      </p>

      <form id="reagendarForm" action="guardar_reagendo.php" method="POST">
        <input type="hidden" name="id_cita" value="<?= (int) $cita['id_cita'] ?>">

        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-calendar-alt fa-icon-left"></i>
          <input type="date" class="form-control" id="fecha" name="fecha_cita" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Hora disponible</label>
          <div id="horasDisponibles" class="d-flex flex-wrap gap-2 mb-2">
            <span class="text-muted small">Elige una fecha para ver las horas disponibles.</span>
          </div>
          <div id="horaSeleccionadaTexto" class="hora-seleccionada-texto small"></div>
          <input type="hidden" name="hora" id="hora" required />
        </div>

        <button type="submit" class="btn custom-register-btn w-100">CONFIRMAR NUEVO HORARIO</button>
      </form>

      <div class="text-center mt-3">
        <a href="mis_citas.php" class="link-info">Cancelar y volver</a>
      </div>
    </div>
  </div>

  <script>
    const ID_MEDICO = <?= (int) $cita['id_medico'] ?>;
    const ID_CITA = <?= (int) $cita['id_cita'] ?>;
  </script>
  <script src="../js/reagendar_cita.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
</body>

</html>
