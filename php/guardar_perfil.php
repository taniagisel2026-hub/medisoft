<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol(['medico', 'recepcionista', 'admin']);
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: perfil.php');
    exit();
}

csrf_validar();

$correo = trim($_POST['correo_electronico_usuario'] ?? '');
$direccion = trim($_POST['direccion_usuario'] ?? '');
$telefono = trim($_POST['telefono_usuario'] ?? '');

if (!$correo || !$direccion || !$telefono) {
    die("Error: faltan campos obligatorios.");
}

try {
    $stmt = $conexion->prepare("UPDATE usuario SET correo_electronico_usuario = ?, direccion_usuario = ?, telefono_usuario = ? WHERE id_usuario = ?");
    $stmt->bind_param("sssi", $correo, $direccion, $telefono, $_SESSION['id']);
    $stmt->execute();
} catch (mysqli_sql_exception $e) {
    if ($conexion->errno === 1062) {
        die("Error: ese correo ya está en uso por otra cuenta.");
    }
    die("Error al actualizar el perfil: " . $e->getMessage());
}

header('Location: perfil.php?guardado=1');
exit();
