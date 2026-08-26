<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol(['paciente', 'recepcionista']);
require 'conexion.php';
csrf_validar();

$idCita = filter_input(INPUT_POST, 'id_cita', FILTER_VALIDATE_INT);

if (!$idCita) {
    header("Location: mis_citas.php");
    exit();
}

if ($_SESSION['rol'] === 'paciente') {
    $stmt = $conexion->prepare("UPDATE cita SET estado_cita = 'cancelada' WHERE id_cita = ? AND id_paciente = ? AND estado_cita = 'pendiente'");
    $stmt->bind_param("ii", $idCita, $_SESSION['id']);
} else {
    $stmt = $conexion->prepare("UPDATE cita SET estado_cita = 'cancelada' WHERE id_cita = ? AND estado_cita = 'pendiente'");
    $stmt->bind_param("i", $idCita);
}
$stmt->execute();

header("Location: mis_citas.php");
exit();
