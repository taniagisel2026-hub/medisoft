<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol(['paciente', 'recepcionista']);
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: asignar_cita.php");
    exit();
}

$idMedico = filter_input(INPUT_POST, 'id_medico', FILTER_VALIDATE_INT);
$fecha = $_POST['fecha_cita'] ?? '';
$hora = $_POST['hora'] ?? '';
$motivo = trim($_POST['motivo'] ?? '') ?: null;

if ($_SESSION['rol'] === 'paciente') {
    $idPaciente = $_SESSION['id'];
} else {
    $idPaciente = filter_input(INPUT_POST, 'id_paciente', FILTER_VALIDATE_INT);
}

$fechaValida = DateTime::createFromFormat('Y-m-d', $fecha);
$hoy = new DateTime('today');

if (!$idMedico || !$idPaciente || !$fechaValida || $fechaValida->format('Y-m-d') !== $fecha ||
    $fechaValida < $hoy || !preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $hora)) {
    die("Error: datos de la cita inválidos.");
}
$hora .= ':00';

// El paciente debe existir en la tabla paciente (perfil completo)
$stmtPaciente = $conexion->prepare("SELECT id_paciente FROM paciente WHERE id_paciente = ?");
$stmtPaciente->bind_param("i", $idPaciente);
$stmtPaciente->execute();
if ($stmtPaciente->get_result()->num_rows === 0) {
    echo "<script>
            alert('❌ Ese paciente no tiene un perfil completo todavía.');
            window.location.href='asignar_cita.php';
          </script>";
    exit();
}

// La hora debe caer dentro del horario real del médico ese día
$diaSemana = (int) $fechaValida->format('N');
$stmtHorario = $conexion->prepare("SELECT 1 FROM horario_medico WHERE id_medico = ? AND dia_semana = ? AND ? >= hora_inicio AND ? < hora_fin");
$stmtHorario->bind_param("iiss", $idMedico, $diaSemana, $hora, $hora);
$stmtHorario->execute();
if ($stmtHorario->get_result()->num_rows === 0) {
    echo "<script>
            alert('❌ Esa hora no está dentro del horario del médico.');
            window.location.href='asignar_cita.php';
          </script>";
    exit();
}

try {
    $stmt = $conexion->prepare("INSERT INTO cita (id_paciente, id_medico, fecha_cita, hora, estado_cita, motivo) VALUES (?, ?, ?, ?, 'pendiente', ?)");
    $stmt->bind_param("iisss", $idPaciente, $idMedico, $fecha, $hora, $motivo);
    $stmt->execute();
} catch (mysqli_sql_exception $e) {
    if ($conexion->errno === 1062) { // duplicado por el UNIQUE KEY (id_medico, fecha_cita, hora)
        echo "<script>
                alert('❌ Ese horario ya fue tomado por otro paciente. Elige otra hora.');
                window.location.href='asignar_cita.php';
              </script>";
        exit();
    }
    die("Error al agendar la cita: " . $e->getMessage());
}

echo "<script>
        alert('✔ Cita agendada con éxito.');
        window.location.href='mis_citas.php';
      </script>";
