<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('admin');
require 'conexion.php';

$idMedicamento = filter_input(INPUT_POST, 'id_medicamento', FILTER_VALIDATE_INT);
$cantidad = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);

if (!$idMedicamento || $cantidad === false || $cantidad === null) {
    die("Error: datos inválidos.");
}

$stmt = $conexion->prepare("UPDATE medicamento SET cantidad_disponible = GREATEST(0, cantidad_disponible + ?) WHERE id_medicamento = ?");
$stmt->bind_param("ii", $cantidad, $idMedicamento);
$stmt->execute();

header("Location: registrar_medicamento.php");
exit();
