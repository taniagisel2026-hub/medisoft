<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol(['paciente', 'recepcionista']);
require 'conexion.php';

$especialidades = $conexion->query("SELECT DISTINCT especialidad_medico FROM medico ORDER BY especialidad_medico");

$medicos = $conexion->query("
    SELECT m.id_medico, m.especialidad_medico, u.nombre_usuario, u.apellido_usuario
    FROM medico m
    JOIN usuario u ON u.id_usuario = m.id_medico
    ORDER BY u.nombre_usuario
")->fetch_all(MYSQLI_ASSOC);

$esRecepcionista = $_SESSION['rol'] === 'recepcionista';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ASIGNAR CITA MEDISOFT</title>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/asignar_cita.css" />
  <link rel="icon"
    href="data:image/x-icon;base64,AAABAAEAEBAAAAAAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEABAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=">
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../imagenes/logo medisoft.png" height="50" alt="Logo" loading="lazy" />
        <img src="../imagenes/letra medisoft.png" class="brand-text d-none d-sm-block" alt="Medisoft" />
      </a>

      <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>
  </nav>
  <!-- Fin navbar -->

  <!-- Asignar Cita -->
  <div class="form-wrapper">
    <div class="form-container">
      <h3>Asignar Cita</h3>
      <form id="citaForm" action="guardar_cita.php" method="POST">

        <?php if ($esRecepcionista): ?>
        <!-- Búsqueda de paciente (solo recepcionista) -->
        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-user fa-icon-left"></i>
          <input type="text" class="form-control" id="documentoPaciente" placeholder="Documento del paciente" />
          <button type="button" id="buscarPacienteBtn" class="btn btn-sm btn-outline-secondary mt-1">Buscar paciente</button>
        </div>
        <div id="pacienteEncontrado" class="mb-3 small text-success" style="display:none;"></div>
        <input type="hidden" name="id_paciente" id="id_paciente" required />
        <?php endif; ?>

        <!-- Especialidad -->
        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-stethoscope fa-icon-left"></i>
          <select class="form-select" id="especialidad" required>
            <option selected disabled value="">Selecciona la especialidad</option>
            <?php while ($fila = $especialidades->fetch_assoc()): ?>
              <option value="<?= htmlspecialchars($fila['especialidad_medico']) ?>"><?= htmlspecialchars($fila['especialidad_medico']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <!-- Médico -->
        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-user-md fa-icon-left"></i>
          <select class="form-select" id="medico" name="id_medico" required disabled>
            <option selected disabled value="">Elige primero la especialidad</option>
          </select>
        </div>

        <!-- Fecha -->
        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-calendar-alt fa-icon-left"></i>
          <input type="date" class="form-control" id="fecha" name="fecha_cita" required disabled>
        </div>

        <!-- Hora -->
        <div class="mb-3">
          <label class="form-label">Hora disponible</label>
          <div id="horasDisponibles" class="d-flex flex-wrap gap-2 mb-2">
            <span class="text-muted small">Elige médico y fecha para ver las horas disponibles.</span>
          </div>
          <input type="hidden" name="hora" id="hora" required />
        </div>

        <!-- Motivo -->
        <div class="mb-3 input-icon-wrapper">
          <i class="fas fa-notes-medical fa-icon-left"></i>
          <input type="text" class="form-control" name="motivo" placeholder="Motivo de la consulta (opcional)" />
        </div>

        <!-- Botón -->
        <button type="submit" class="btn custom-register-btn w-100">AGENDAR CITA</button>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    const MEDICOS = <?= json_encode($medicos) ?>;
    const ES_RECEPCIONISTA = <?= json_encode($esRecepcionista) ?>;
  </script>
  <script src="../js/asignar_cita.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
</body>

</html>
