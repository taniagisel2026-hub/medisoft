<?php
session_start();
require 'conexion.php';
require_once __DIR__ . '/csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: recuperar_contrasena.php');
    exit();
}

csrf_validar();

$tipo = $_POST['tipo_identificacion'] ?? '';
$documento = $_POST['numero_documento'] ?? '';
$correo = trim($_POST['correo'] ?? '');

$stmt = $conexion->prepare("
    SELECT id_usuario
    FROM usuario
    WHERE tipo_documento_usuario = ? AND documento_usuario = ? AND correo_electronico_usuario = ?
");
$stmt->bind_param("sss", $tipo, $documento, $correo);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

if (!$usuario) {
    $_SESSION['mensaje_recuperacion'] = 'No encontramos ninguna cuenta con ese documento y correo.';
    header('Location: recuperar_contrasena.php');
    exit();
}

$token = bin2hex(random_bytes(32));
$expira = date('Y-m-d H:i:s', strtotime('+30 minutes'));

$update = $conexion->prepare("UPDATE usuario SET reset_token = ?, reset_token_expira = ? WHERE id_usuario = ?");
$update->bind_param("ssi", $token, $expira, $usuario['id_usuario']);
$update->execute();

header('Location: restablecer_contrasena.php?token=' . $token);
exit();
