<?php
include 'verificar_sesion.php';
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cambiar_contrasena.php');
    exit();
}

csrf_validar();

$actual = $_POST['contrasena_actual'] ?? '';
$nueva = $_POST['nueva_contrasena'] ?? '';
$confirmar = $_POST['confirmar_contrasena'] ?? '';

$stmt = $conexion->prepare("SELECT contrasena_usuario FROM usuario WHERE id_usuario = ?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

if (!$usuario || !password_verify($actual, $usuario['contrasena_usuario'])) {
    header('Location: cambiar_contrasena.php?error=' . urlencode('La contraseña actual no es correcta.'));
    exit();
}

if (strlen($nueva) < 6) {
    header('Location: cambiar_contrasena.php?error=' . urlencode('La nueva contraseña debe tener al menos 6 caracteres.'));
    exit();
}

if ($nueva !== $confirmar) {
    header('Location: cambiar_contrasena.php?error=' . urlencode('Las contraseñas no coinciden.'));
    exit();
}

$hash = password_hash($nueva, PASSWORD_DEFAULT);
$update = $conexion->prepare("UPDATE usuario SET contrasena_usuario = ? WHERE id_usuario = ?");
$update->bind_param("si", $hash, $_SESSION['id']);
$update->execute();

$dashboards = [
    'admin' => 'dashboard_admin.php',
    'medico' => 'dashboard_medico.php',
    'paciente' => 'dashboard_paciente.php',
    'recepcionista' => 'dashboard_recep.php',
];
$destino = $dashboards[$_SESSION['rol']] ?? '../login.html';

echo "<script>
        alert('✔ Contraseña actualizada con éxito.');
        window.location.href = '{$destino}';
      </script>";
