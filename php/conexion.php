<?php
$conexion = new mysqli("localhost", "root", "", "bd_citas_medicas");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
