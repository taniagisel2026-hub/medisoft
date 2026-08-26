<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol(['paciente', 'recepcionista']);
require 'conexion.php';

header('Content-Type: application/json; charset=utf-8');

$idMedico = filter_input(INPUT_GET, 'id_medico', FILTER_VALIDATE_INT);
$fecha = $_GET['fecha'] ?? '';
$excluirCita = filter_input(INPUT_GET, 'excluir_cita', FILTER_VALIDATE_INT) ?: 0;

$fechaValida = DateTime::createFromFormat('Y-m-d', $fecha);
$hoy = new DateTime('today');

if (!$idMedico || !$fechaValida || $fechaValida->format('Y-m-d') !== $fecha || $fechaValida < $hoy) {
    http_response_code(400);
    echo json_encode(['error' => 'Médico o fecha inválidos.']);
    exit();
}

$diaSemana = (int) $fechaValida->format('N'); // 1=Lunes .. 7=Domingo

$stmtHorario = $conexion->prepare("SELECT hora_inicio, hora_fin, duracion_slot_min FROM horario_medico WHERE id_medico = ? AND dia_semana = ?");
$stmtHorario->bind_param("ii", $idMedico, $diaSemana);
$stmtHorario->execute();
$horarios = $stmtHorario->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtHorario->close();

$stmtOcupadas = $conexion->prepare("SELECT hora FROM cita WHERE id_medico = ? AND fecha_cita = ? AND estado_cita <> 'cancelada' AND id_cita <> ?");
$stmtOcupadas->bind_param("isi", $idMedico, $fecha, $excluirCita);
$stmtOcupadas->execute();
$ocupadas = array_column($stmtOcupadas->get_result()->fetch_all(MYSQLI_ASSOC), 'hora');
$stmtOcupadas->close();

$esHoy = $fechaValida->format('Y-m-d') === $hoy->format('Y-m-d');
$ahora = new DateTime();

$disponibles = [];
foreach ($horarios as $bloque) {
    $inicio = new DateTime($bloque['hora_inicio']);
    $fin = new DateTime($bloque['hora_fin']);
    $duracion = (int) $bloque['duracion_slot_min'];

    for ($slot = clone $inicio; $slot < $fin; $slot->modify("+{$duracion} minutes")) {
        $horaSlot = $slot->format('H:i:s');

        if (in_array($horaSlot, $ocupadas)) {
            continue;
        }
        if ($esHoy && $slot < $ahora) {
            continue;
        }
        $disponibles[] = $slot->format('H:i');
    }
}

echo json_encode(['disponibles' => $disponibles]);
