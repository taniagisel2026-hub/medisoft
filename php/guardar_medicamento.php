<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol(['admin', 'medico']);
require 'conexion.php';

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$dosis = trim($_POST['dosis'] ?? '');
$cantidad = filter_input(INPUT_POST, 'cantidad_disponible', FILTER_VALIDATE_INT);

if (!$nombre || !$descripcion || !$dosis || $cantidad === false || $cantidad === null || $cantidad < 0) {
    die("Error: faltan campos obligatorios o la cantidad no es válida.");
}

$stmt = $conexion->prepare("INSERT INTO medicamento (nombre_medicamento, descripcion_medicamento, dosis, cantidad_disponible) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $nombre, $descripcion, $dosis, $cantidad);
$stmt->execute();

$destino = $_SESSION['rol'] === 'admin' ? 'dashboard_admin.php' : 'dashboard_medico.php';
echo "<script>
        alert('✔ Medicamento registrado con éxito.');
        window.location.href='{$destino}';
      </script>";
