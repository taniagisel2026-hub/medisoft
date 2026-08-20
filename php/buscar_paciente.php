<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('recepcionista');
require 'conexion.php';

header('Content-Type: application/json; charset=utf-8');

$documento = $_GET['documento'] ?? '';

if (!$documento) {
    echo json_encode(['encontrado' => false]);
    exit();
}

$stmt = $conexion->prepare("
    SELECT u.id_usuario, u.nombre_usuario, u.apellido_usuario
    FROM usuario u
    INNER JOIN paciente p ON p.id_paciente = u.id_usuario
    WHERE u.documento_usuario = ? AND u.rol_usuario = 'paciente'
");
$stmt->bind_param("s", $documento);
$stmt->execute();
$fila = $stmt->get_result()->fetch_assoc();

if ($fila) {
    echo json_encode([
        'encontrado' => true,
        'id_paciente' => $fila['id_usuario'],
        'nombre' => $fila['nombre_usuario'],
        'apellido' => $fila['apellido_usuario'],
    ]);
} else {
    echo json_encode(['encontrado' => false]);
}
