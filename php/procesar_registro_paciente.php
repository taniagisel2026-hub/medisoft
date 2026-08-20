<?php
require "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recibir datos
    $tipo_documento = $_POST['tipo_documento_usuario'] ?? null;
    $documento = $_POST['documento_usuario'] ?? null;
    $nombre = $_POST['nombre_usuario'] ?? null;
    $apellido = $_POST['apellido_usuario'] ?? null;
    $direccion = $_POST['direccion_usuario'] ?? null;
    $telefono = $_POST['telefono_usuario'] ?? null;
    $correo = $_POST['correo_electronico_usuario'] ?? null;
    $password = $_POST['contrasena_usuario'] ?? null;

    // Validación
    if (!$tipo_documento || !$documento || !$nombre || !$apellido || !$direccion || !$telefono || !$correo || !$password) {
        die("Error: faltan campos obligatorios.");
    }

    // Validar documento repetido
    $checkDoc = $conexion->prepare("SELECT id_usuario FROM usuario WHERE documento_usuario = ?");
    $checkDoc->bind_param("s", $documento);
    $checkDoc->execute();
    $checkDoc->store_result();

    if ($checkDoc->num_rows > 0) {
        echo "<script>
            alert('❌ El número de documento ya está registrado');
            window.location.href='registro_paciente.php';
          </script>";
        exit();
    }

    // Validar email repetido
    $checkEmail = $conexion->prepare("SELECT id_usuario FROM usuario WHERE correo_electronico_usuario = ?");
    $checkEmail->bind_param("s", $correo);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        echo "<script>
                alert('❌ El correo ya está registrado');
                window.location.href='registro_paciente.php';
              </script>";
        exit();
    }


    // Encriptar contraseña
    $passHash = password_hash($password, PASSWORD_DEFAULT);

    // Rol fijo
    $rol = "paciente";

    // INSERT de 9 campos (excluye id_usuario)

    $sql = "INSERT INTO usuario
(tipo_documento_usuario, documento_usuario, nombre_usuario, apellido_usuario,
 correo_electronico_usuario, direccion_usuario, telefono_usuario, contrasena_usuario, rol_usuario)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param(
        "sssssssss",
        $tipo_documento,        // tipo_documento_usuario
        $documento,             // documento_usuario
        $nombre,                // nombre_usuario
        $apellido,              // apellido_usuario
        $correo,                // correo_electronico_usuario
        $direccion,             // direccion_usuario
        $telefono,              // telefono_usuario
        $passHash,              // contrasena_usuario
        $rol                    // rol_usuario
    );




    // Ejecutar
    if ($stmt->execute()) {
        echo "<script>
                alert('✔ Paciente registrado con éxito');
                window.location.href='../index.html';
              </script>";
        exit();
    } else {
        echo "Error al registrar: " . $stmt->error;
    }

    $stmt->close();
}
