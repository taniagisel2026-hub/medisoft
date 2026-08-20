<?php
session_start();
require "conexion.php";

// Si lo registra un recepcionista logueado, lo devolvemos a su propio panel;
// si es un visitante público registrándose por su cuenta, al sitio público.
$esRecepcionista = ($_SESSION['rol'] ?? null) === 'recepcionista';
$volverFormulario = $esRecepcionista ? 'registro_usuario.php' : 'registro_paciente.php';
$volverExito = $esRecepcionista ? 'dashboard_recep.php' : '../index.html';

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
    $fechaNacimiento = $_POST['fecha_nacimiento'] ?? null;
    $eps = $_POST['eps_paciente'] ?? null;
    $sexo = $_POST['sexo'] ?? null;

    // Validación
    if (!$tipo_documento || !$documento || !$nombre || !$apellido || !$direccion || !$telefono ||
        !$correo || !$password || !$fechaNacimiento || !$eps || !$sexo) {
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
            window.location.href='{$volverFormulario}';
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
                window.location.href='{$volverFormulario}';
              </script>";
        exit();
    }

    // Encriptar contraseña
    $passHash = password_hash($password, PASSWORD_DEFAULT);

    // Rol fijo
    $rol = "paciente";

    $conexion->begin_transaction();

    try {
        $sql = "INSERT INTO usuario
        (tipo_documento_usuario, documento_usuario, nombre_usuario, apellido_usuario,
         correo_electronico_usuario, direccion_usuario, telefono_usuario, contrasena_usuario, rol_usuario)
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
        $stmt->execute();
        $idUsuario = $conexion->insert_id;
        $stmt->close();

        $stmtPaciente = $conexion->prepare("INSERT INTO paciente (id_paciente, fecha_nacimiento, eps_paciente, sexo) VALUES (?, ?, ?, ?)");
        $stmtPaciente->bind_param("isss", $idUsuario, $fechaNacimiento, $eps, $sexo);
        $stmtPaciente->execute();
        $stmtPaciente->close();

        $conexion->commit();
    } catch (mysqli_sql_exception $e) {
        $conexion->rollback();
        die("Error al registrar: " . $e->getMessage());
    }

    echo "<script>
            alert('✔ Paciente registrado con éxito');
            window.location.href='{$volverExito}';
          </script>";
    exit();
}
