<?php
session_start();

// Si no hay sesión iniciada → no deja entrar
if (!isset($_SESSION['id']) || !isset($_SESSION['rol'])) {
    header("Location: ../login.html");
    exit();
}
?>


