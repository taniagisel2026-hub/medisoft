<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('medico');
require 'conexion.php';

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');

if (!$nombre || !$descripcion) {
    die("Error: faltan campos obligatorios.");
}

$stmt = $conexion->prepare("INSERT INTO tratamiento (nombre_tratamiento, descripcion_tratamiento) VALUES (?, ?)");
$stmt->bind_param("ss", $nombre, $descripcion);
$stmt->execute();

echo "<script>
        alert('✔ Tratamiento registrado con éxito.');
        window.location.href='dashboard_medico.php';
      </script>";
