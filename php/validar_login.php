<?php
session_start();
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tipo = $_POST['tipo_identificacion'];
    $documento = $_POST['numero_documento'];
    $passIngresada = $_POST['contrasena'];

    // Buscar usuario
    $sql = $conexion->prepare("
        SELECT id_usuario, nombre_usuario, contrasena_usuario, rol_usuario
        FROM usuario
        WHERE tipo_documento_usuario = ? AND documento_usuario = ?
    ");

    $sql->bind_param("ss", $tipo, $documento);
    $sql->execute();
    $resultado = $sql->get_result();

    if ($resultado->num_rows === 1) {

        $usuario = $resultado->fetch_assoc();
        $hashBD = $usuario['contrasena_usuario'];

        if (password_verify($passIngresada, $hashBD)) {

            $_SESSION['id'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre_usuario'];
            $_SESSION['rol'] = $usuario['rol_usuario'];

            // Redirección según rol
            if ($usuario['rol_usuario'] === 'admin') {
                $destino = '../php/dashboard_admin.php';
            } 
            else if ($usuario['rol_usuario'] === 'medico') {
                $destino = '../php/dashboard_medico.php';
            }
            else if ($usuario['rol_usuario'] === 'paciente') {
                $destino = '../php/dashboard_paciente.php';
            }
            else if ($usuario['rol_usuario'] === 'recepcionista') {
                $destino = '../php/dashboard_recep.php';
            } 
            else {
                $destino = '../index.html';
            }

            echo "<script>
                alert('✔ Bienvenido {$usuario['nombre_usuario']}');
                window.location.href = '{$destino}';
            </script>";

        } else {
            echo "<script>
                alert('❌ Contraseña incorrecta.');
                window.location.href='../login.html';
            </script>";
        }

    } else {
        echo "<script>
            alert('❌ Usuario no encontrado.');
            window.location.href='../login.html';
        </script>";
    }
}
?>
