<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('paciente');
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: perfil_paciente.php');
    exit();
}

csrf_validar();

$correo = trim($_POST['correo_electronico_usuario'] ?? '');
$direccion = trim($_POST['direccion_usuario'] ?? '');
$telefono = trim($_POST['telefono_usuario'] ?? '');
$eps = trim($_POST['eps_paciente'] ?? '');

if (!$correo || !$direccion || !$telefono || !$eps) {
    die("Error: faltan campos obligatorios.");
}

$conexion->begin_transaction();
try {
    $stmt = $conexion->prepare("UPDATE usuario SET correo_electronico_usuario = ?, direccion_usuario = ?, telefono_usuario = ? WHERE id_usuario = ?");
    $stmt->bind_param("sssi", $correo, $direccion, $telefono, $_SESSION['id']);
    $stmt->execute();

    $stmtP = $conexion->prepare("UPDATE paciente SET eps_paciente = ? WHERE id_paciente = ?");
    $stmtP->bind_param("si", $eps, $_SESSION['id']);
    $stmtP->execute();

    $conexion->commit();
} catch (mysqli_sql_exception $e) {
    $conexion->rollback();
    die("Error al actualizar el perfil: " . $e->getMessage());
}

header('Location: perfil_paciente.php?guardado=1');
exit();
