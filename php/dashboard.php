<?php
session_start();
if (!isset($_SESSION['nombre_usuario'])) {
  header("Location: login.html");
  exit();
}
?>

