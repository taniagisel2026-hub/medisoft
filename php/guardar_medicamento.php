<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol(['admin', 'medico']);
require 'conexion.php';

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$dosis = trim($_POST['dosis'] ?? '');

if (!$nombre || !$descripcion || !$dosis) {
    die("Error: faltan campos obligatorios.");
}

$stmt = $conexion->prepare("INSERT INTO medicamento (nombre_medicamento, descripcion_medicamento, dosis) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nombre, $descripcion, $dosis);
$stmt->execute();

$destino = $_SESSION['rol'] === 'admin' ? 'dashboard_admin.php' : 'dashboard_medico.php';
echo "<script>
        alert('✔ Medicamento registrado con éxito.');
        window.location.href='{$destino}';
      </script>";
