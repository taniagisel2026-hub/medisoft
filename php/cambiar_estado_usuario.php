<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('admin');
require 'conexion.php';
csrf_validar();

$idUsuario = filter_input(INPUT_POST, 'id_usuario', FILTER_VALIDATE_INT);

if (!$idUsuario || $idUsuario === (int) $_SESSION['id']) {
    header("Location: gestionar_usuarios.php");
    exit();
}

$stmt = $conexion->prepare("UPDATE usuario SET activo = NOT activo WHERE id_usuario = ?");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();

header("Location: gestionar_usuarios.php");
exit();
