<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('admin');
require 'conexion.php';
csrf_validar();

$numero = filter_input(INPUT_POST, 'numero', FILTER_VALIDATE_INT);
$ubicacion = trim($_POST['ubicacion'] ?? '');

if (!$numero || !$ubicacion) {
    die("Error: faltan campos obligatorios.");
}

$stmt = $conexion->prepare("INSERT INTO consultorio (numero, ubicacion, disponibilidad) VALUES (?, ?, 1)");
$stmt->bind_param("is", $numero, $ubicacion);
$stmt->execute();

echo "<script>
        alert('✔ Consultorio registrado con éxito.');
        window.location.href='dashboard_admin.php';
      </script>";
