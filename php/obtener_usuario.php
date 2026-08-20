<?php
session_start();
// después de validar usuario y contraseña:
$_SESSION['nombre_usuario'] = $usuario_nombre; // del login
$_SESSION['rol_usuario'] = $usuario_rol;       // del login
header("Location:dashboard.php");
exit();
?>
