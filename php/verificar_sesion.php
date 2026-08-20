<?php
session_start();

// Si no hay sesión iniciada → no deja entrar
if (!isset($_SESSION['id']) || !isset($_SESSION['rol'])) {
    echo "<script>
        alert('❌ Debes iniciar sesión primero.');
        window.location.href = '../login.html';
    </script>";
    exit();
}
?>


