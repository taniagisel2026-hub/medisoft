<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol(['paciente', 'recepcionista']);
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: mis_citas.php");
    exit();
}

$idCita = filter_input(INPUT_POST, 'id_cita', FILTER_VALIDATE_INT);
$fecha = $_POST['fecha_cita'] ?? '';
$hora = $_POST['hora'] ?? '';

$fechaValida = DateTime::createFromFormat('Y-m-d', $fecha);
$hoy = new DateTime('today');

if (!$idCita || !$fechaValida || $fechaValida->format('Y-m-d') !== $fecha ||
    $fechaValida < $hoy || !preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $hora)) {
    die("Error: datos de la nueva fecha/hora inválidos.");
}
$hora .= ':00';

// Confirmar que la cita existe, está pendiente y pertenece al paciente (si aplica)
if ($_SESSION['rol'] === 'paciente') {
    $stmtCita = $conexion->prepare("SELECT id_medico FROM cita WHERE id_cita = ? AND id_paciente = ? AND estado_cita = 'pendiente'");
    $stmtCita->bind_param("ii", $idCita, $_SESSION['id']);
} else {
    $stmtCita = $conexion->prepare("SELECT id_medico FROM cita WHERE id_cita = ? AND estado_cita = 'pendiente'");
    $stmtCita->bind_param("i", $idCita);
}
$stmtCita->execute();
$cita = $stmtCita->get_result()->fetch_assoc();

if (!$cita) {
    die("Esta cita no existe, ya no está pendiente, o no te pertenece.");
}
$idMedico = $cita['id_medico'];

// La hora debe caer dentro del horario real del médico ese día
$diaSemana = (int) $fechaValida->format('N');
$stmtHorario = $conexion->prepare("SELECT 1 FROM horario_medico WHERE id_medico = ? AND dia_semana = ? AND ? >= hora_inicio AND ? < hora_fin");
$stmtHorario->bind_param("iiss", $idMedico, $diaSemana, $hora, $hora);
$stmtHorario->execute();
if ($stmtHorario->get_result()->num_rows === 0) {
    echo "<script>
            alert('❌ Esa hora no está dentro del horario del médico.');
            window.location.href='reagendar_cita.php?id_cita={$idCita}';
          </script>";
    exit();
}

// El consultorio es el que tiene asignado fijo el médico (no cambia al reagendar)
$stmtConsultorio = $conexion->prepare("SELECT id_consultorio FROM medico WHERE id_medico = ?");
$stmtConsultorio->bind_param("i", $idMedico);
$stmtConsultorio->execute();
$consultorioFila = $stmtConsultorio->get_result()->fetch_assoc();
$idConsultorio = $consultorioFila['id_consultorio'] ?? null;

try {
    $stmt = $conexion->prepare("UPDATE cita SET fecha_cita = ?, hora = ?, id_consultorio = ? WHERE id_cita = ?");
    $stmt->bind_param("ssii", $fecha, $hora, $idConsultorio, $idCita);
    $stmt->execute();
} catch (mysqli_sql_exception $e) {
    if ($conexion->errno === 1062) {
        echo "<script>
                alert('❌ Ese horario ya fue tomado por otro paciente. Elige otra hora.');
                window.location.href='reagendar_cita.php?id_cita={$idCita}';
              </script>";
        exit();
    }
    die("Error al reagendar la cita: " . $e->getMessage());
}

echo "<script>
        alert('✔ Cita reagendada con éxito.');
        window.location.href='mis_citas.php';
      </script>";
