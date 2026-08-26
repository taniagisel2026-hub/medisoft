<?php
// Evitar que el navegador guarde en caché páginas protegidas (para que el botón "atrás" no muestre datos de la sesión anterior)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

session_start();

// Si no hay sesión iniciada → no deja entrar
if (!isset($_SESSION['id']) || !isset($_SESSION['rol'])) {
    header("Location: ../login.html");
    exit();
}
?>


