<?php
function permitirRol($rolesPermitidos) {
    
    if (!isset($_SESSION['rol'])) {
        header("Location: ../index.html");
        exit();
    }

    // Convertir a array si solo envían un rol
    if (!is_array($rolesPermitidos)) {
        $rolesPermitidos = [$rolesPermitidos];
    }

    // Validar rol
    if (!in_array($_SESSION['rol'], $rolesPermitidos)) {
        header("Location: ../index.html");
        exit();
    }
}
?>



