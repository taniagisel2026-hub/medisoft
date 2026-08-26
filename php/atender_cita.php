<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('medico');
require 'conexion.php';

$idCita = filter_input(INPUT_GET, 'id_cita', FILTER_VALIDATE_INT);

$stmt = $conexion->prepare("
    SELECT c.id_cita, c.fecha_cita, c.hora, c.motivo,
           up.nombre_usuario, up.apellido_usuario
    FROM cita c
    JOIN usuario up ON up.id_usuario = c.id_paciente
    WHERE c.id_cita = ? AND c.id_medico = ? AND c.estado_cita = 'pendiente'
");
$stmt->bind_param("ii", $idCita, $_SESSION['id']);
$stmt->execute();
$cita = $stmt->get_result()->fetch_assoc();

if (!$cita) {
    die("Esta cita no existe, ya fue atendida/cancelada, o no te pertenece.");
}

$medicamentos = $conexion->query("SELECT id_medicamento, nombre_medicamento, dosis, cantidad_disponible FROM medicamento WHERE cantidad_disponible > 0 ORDER BY nombre_medicamento")->fetch_all(MYSQLI_ASSOC);
$tratamientos = $conexion->query("SELECT id_tratamiento, nombre_tratamiento, descripcion_tratamiento FROM tratamiento ORDER BY nombre_tratamiento")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Atender Cita | MEDISOFT</title>
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

  <div class="container mt-4 mb-5" style="max-width: 600px;">
    <h3 class="text-center mb-4">Atender Cita</h3>

    <div class="card p-4 mb-4" style="min-height: auto;">
      <p class="mb-1"><strong>Paciente:</strong> <?= htmlspecialchars($cita['nombre_usuario'] . ' ' . $cita['apellido_usuario']) ?></p>
      <p class="mb-1"><strong>Fecha:</strong> <?= htmlspecialchars($cita['fecha_cita']) ?> — <?= substr(htmlspecialchars($cita['hora']), 0, 5) ?></p>
      <p class="mb-0"><strong>Motivo:</strong> <?= htmlspecialchars($cita['motivo'] ?? '—') ?></p>
    </div>

    <form action="guardar_atencion.php" method="POST">
    <?= csrf_campo() ?>
      <input type="hidden" name="id_cita" value="<?= (int) $cita['id_cita'] ?>">

      <div class="mb-3">
        <label class="form-label">Diagnóstico</label>
        <textarea class="form-control" name="diagnostico" rows="3" required></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Notas adicionales (opcional)</label>
        <textarea class="form-control" name="notas" rows="3"></textarea>
      </div>

      <?php if (!empty($tratamientos)): ?>
        <div class="mb-3">
          <label class="form-label">Tratamientos aplicados (opcional)</label>
          <div class="border rounded p-3">
            <?php foreach ($tratamientos as $t): ?>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="tratamientos[]"
                  value="<?= (int) $t['id_tratamiento'] ?>" id="trat<?= (int) $t['id_tratamiento'] ?>">
                <label class="form-check-label" for="trat<?= (int) $t['id_tratamiento'] ?>">
                  <?= htmlspecialchars($t['nombre_tratamiento']) ?>
                  <span class="text-muted small">— <?= htmlspecialchars($t['descripcion_tratamiento']) ?></span>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <?php if (!empty($medicamentos)): ?>
        <div class="mb-3">
          <label class="form-label">Medicamentos recetados (opcional)</label>
          <div class="border rounded p-3">
            <?php foreach ($medicamentos as $m): ?>
              <div class="row align-items-center mb-2">
                <div class="col-7">
                  <div class="form-check">
                    <input class="form-check-input med-check" type="checkbox" name="medicamentos[]"
                      value="<?= (int) $m['id_medicamento'] ?>" id="med<?= (int) $m['id_medicamento'] ?>">
                    <label class="form-check-label" for="med<?= (int) $m['id_medicamento'] ?>">
                      <?= htmlspecialchars($m['nombre_medicamento']) ?> (<?= htmlspecialchars($m['dosis']) ?>)
                    </label>
                  </div>
                </div>
                <div class="col-5">
                  <input type="number" class="form-control form-control-sm"
                    name="cantidad_<?= (int) $m['id_medicamento'] ?>" min="1" max="<?= (int) $m['cantidad_disponible'] ?>"
                    placeholder="Cantidad (stock: <?= (int) $m['cantidad_disponible'] ?>)" disabled>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <button type="submit" class="btn custom-register-btn w-100">MARCAR COMO ATENDIDA</button>
    </form>

    <script>
      document.querySelectorAll(".med-check").forEach(chk => {
        chk.addEventListener("change", function () {
          const cantidadInput = this.closest(".row").querySelector("input[type=number]");
          cantidadInput.disabled = !this.checked;
          if (this.checked) {
            cantidadInput.value = 1;
            cantidadInput.required = true;
          } else {
            cantidadInput.value = "";
            cantidadInput.required = false;
          }
        });
      });
    </script>

    <div class="text-center mt-3">
      <a href="mis_citas.php" class="link-info">Volver a mis citas</a>
    </div>
  </div>
  <script src="../js/bfcache_guard.js"></script>
</body>

</html>
