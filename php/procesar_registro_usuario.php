<?php
require "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tipo_documento = $_POST['tipo_documento_usuario'] ?? null;
    $documento = $_POST['documento_usuario'] ?? null;
    $nombre = $_POST['nombre_usuario'] ?? null;
    $apellido = $_POST['apellido_usuario'] ?? null;
    $correo = $_POST['correo_electronico_usuario'] ?? null;
    $direccion = $_POST['direccion_usuario'] ?? null;
    $telefono = $_POST['telefono_usuario'] ?? null;
    $password = $_POST['contrasena_usuario'] ?? null;
    $rol = $_POST['rol_usuario'] ?? null;

    if (!$tipo_documento || !$documento || !$nombre || !$apellido || !$correo || !$password || !$rol) {
        die("Error: faltan campos obligatorios.");
    }

    $checkEmail = $conexion->prepare("SELECT id_usuario FROM usuario WHERE correo_electronico_usuario = ?");
    $checkEmail->bind_param("s", $correo);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        echo "<script>
                alert('❌ El correo ya está registrado');
                window.location.href='registro_usuario.php';
              </script>";
        exit();
    }

    $passHash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuario
        (tipo_documento_usuario, documento_usuario, nombre_usuario, apellido_usuario, correo_electronico_usuario, direccion_usuario, telefono_usuario, contrasena_usuario, rol_usuario)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param(
        "sssssssss",
        $tipo_documento,
        $documento,
        $nombre,
        $apellido,
        $correo,
        $direccion,
        $telefono,
        $passHash,
        $rol
    );

    if ($stmt->execute()) {
        echo "<script>
                alert('✔ Usuario registrado con éxito');
                window.location.href='../index.html';
              </script>";
        exit();
    } else {
        echo "Error al registrar: " . $stmt->error;
    }

    $stmt->close();
}
?>
