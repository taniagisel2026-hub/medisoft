<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('medico');
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: mis_citas.php");
    exit();
}

$idCita = filter_input(INPUT_POST, 'id_cita', FILTER_VALIDATE_INT);
$diagnostico = trim($_POST['diagnostico'] ?? '');
$notas = trim($_POST['notas'] ?? '') ?: null;
$medicamentosSeleccionados = array_map('intval', $_POST['medicamentos'] ?? []);
$tratamientosSeleccionados = array_map('intval', $_POST['tratamientos'] ?? []);

if (!$idCita || !$diagnostico) {
    die("Error: el diagnóstico es obligatorio.");
}

// Armar y validar las cantidades recetadas antes de tocar la base de datos
$recetas = [];
foreach ($medicamentosSeleccionados as $idMedicamento) {
    $cantidad = filter_input(INPUT_POST, "cantidad_{$idMedicamento}", FILTER_VALIDATE_INT);
    if (!$cantidad || $cantidad < 1) {
        die("Error: cantidad inválida para uno de los medicamentos seleccionados.");
    }
    $recetas[$idMedicamento] = $cantidad;
}

$conexion->begin_transaction();
try {
    $stmt = $conexion->prepare("
        UPDATE cita
        SET estado_cita = 'atendida', diagnostico = ?, notas = ?
        WHERE id_cita = ? AND id_medico = ? AND estado_cita = 'pendiente'
    ");
    $stmt->bind_param("ssii", $diagnostico, $notas, $idCita, $_SESSION['id']);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception("No se pudo actualizar la cita (ya no existe, no es tuya, o ya fue atendida/cancelada).");
    }

    foreach ($recetas as $idMedicamento => $cantidad) {
        $stmtStock = $conexion->prepare("UPDATE medicamento SET cantidad_disponible = cantidad_disponible - ? WHERE id_medicamento = ? AND cantidad_disponible >= ?");
        $stmtStock->bind_param("iii", $cantidad, $idMedicamento, $cantidad);
        $stmtStock->execute();

        if ($stmtStock->affected_rows === 0) {
            throw new Exception("No hay suficiente stock de uno de los medicamentos seleccionados.");
        }

        $stmtReceta = $conexion->prepare("INSERT INTO cita_medicamento (id_cita, id_medicamento, cantidad) VALUES (?, ?, ?)");
        $stmtReceta->bind_param("iii", $idCita, $idMedicamento, $cantidad);
        $stmtReceta->execute();
    }

    foreach ($tratamientosSeleccionados as $idTratamiento) {
        $stmtTrat = $conexion->prepare("INSERT INTO cita_tratamiento (id_cita, id_tratamiento) VALUES (?, ?)");
        $stmtTrat->bind_param("ii", $idCita, $idTratamiento);
        $stmtTrat->execute();
    }

    $conexion->commit();
} catch (Exception $e) {
    $conexion->rollback();
    die("Error al atender la cita: " . $e->getMessage());
}

header("Location: mis_citas.php");
exit();
