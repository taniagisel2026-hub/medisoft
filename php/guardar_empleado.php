<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('admin');
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: registrar_empleado.php");
    exit();
}

csrf_validar();

$tipo_documento = $_POST['tipo_documento_usuario'] ?? '';
$documento      = $_POST['documento_usuario'] ?? '';
$nombre         = trim($_POST['nombre_usuario'] ?? '');
$apellido       = trim($_POST['apellido_usuario'] ?? '');
$direccion      = trim($_POST['direccion_usuario'] ?? '');
$telefono       = trim($_POST['telefono_usuario'] ?? '');
$correo         = trim($_POST['correo_electronico_usuario'] ?? '');
$cargo          = $_POST['cargo'] ?? '';

$cargosValidos = ['medico' => 'Médico', 'recepcionista' => 'Recepcionista', 'admin' => 'Administrador'];
$tiposValidos  = ['CC', 'CE', 'TI'];

if (!in_array($tipo_documento, $tiposValidos) || !$documento || !$nombre || !$apellido ||
    !$direccion || !$telefono || !$correo || !array_key_exists($cargo, $cargosValidos)) {
    die("Error: faltan campos obligatorios o son inválidos.");
}

$especialidad = trim($_POST['especialidad'] ?? '');
$tarjetaProfesional = $_POST['tarjeta_profesional'] ?? '';
$idConsultorio = filter_input(INPUT_POST, 'id_consultorio', FILTER_VALIDATE_INT);
$dias = $_POST['dias'] ?? [];
$horaInicio = $_POST['hora_inicio'] ?? '08:00';
$horaFin = $_POST['hora_fin'] ?? '17:00';

if ($cargo === 'medico') {
    $diasValidos = array_values(array_intersect(array_map('intval', $dias), range(1, 7)));
    if (!$especialidad || !$tarjetaProfesional || !$idConsultorio || empty($diasValidos) || $horaInicio >= $horaFin) {
        die("Error: para un médico debes indicar especialidad, tarjeta profesional, consultorio, al menos un día y un horario válido.");
    }
}

// Documento o correo ya registrados
$check = $conexion->prepare("SELECT id_usuario FROM usuario WHERE documento_usuario = ? OR correo_electronico_usuario = ?");
$check->bind_param("ss", $documento, $correo);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    echo "<script>
            alert('❌ Ya existe un usuario con ese documento o correo.');
            window.location.href='registrar_empleado.php';
          </script>";
    exit();
}
$check->close();

$tempPassword = substr(bin2hex(random_bytes(5)), 0, 8);
$passHash = password_hash($tempPassword, PASSWORD_DEFAULT);
$rol = $cargo; // 'medico' | 'recepcionista' | 'admin' — coincide con rol_usuario usado en el login

$conexion->begin_transaction();

try {
    $stmt = $conexion->prepare("
        INSERT INTO usuario
        (tipo_documento_usuario, documento_usuario, nombre_usuario, apellido_usuario,
         correo_electronico_usuario, direccion_usuario, telefono_usuario, contrasena_usuario, rol_usuario)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "sssssssss",
        $tipo_documento, $documento, $nombre, $apellido,
        $correo, $direccion, $telefono, $passHash, $rol
    );
    $stmt->execute();
    $idUsuario = $conexion->insert_id;
    $stmt->close();

    $stmtEmp = $conexion->prepare("INSERT INTO empleado (id_empleado, cargo) VALUES (?, ?)");
    $cargoLabel = $cargosValidos[$cargo];
    $stmtEmp->bind_param("is", $idUsuario, $cargoLabel);
    $stmtEmp->execute();
    $stmtEmp->close();

    if ($cargo === 'medico') {
        $stmtMed = $conexion->prepare("INSERT INTO medico (id_medico, especialidad_medico, numero_tarjeta_profesional, id_consultorio) VALUES (?, ?, ?, ?)");
        $stmtMed->bind_param("isii", $idUsuario, $especialidad, $tarjetaProfesional, $idConsultorio);
        $stmtMed->execute();
        $stmtMed->close();

        $stmtHor = $conexion->prepare("INSERT INTO horario_medico (id_medico, dia_semana, hora_inicio, hora_fin, duracion_slot_min) VALUES (?, ?, ?, ?, 30)");
        foreach ($diasValidos as $dia) {
            $stmtHor->bind_param("iiss", $idUsuario, $dia, $horaInicio, $horaFin);
            $stmtHor->execute();
        }
        $stmtHor->close();
    } elseif ($cargo === 'admin') {
        $stmtAdmin = $conexion->prepare("INSERT INTO administrador (id_administrador) VALUES (?)");
        $stmtAdmin->bind_param("i", $idUsuario);
        $stmtAdmin->execute();
        $stmtAdmin->close();
    }

    $conexion->commit();
} catch (mysqli_sql_exception $e) {
    $conexion->rollback();
    if ($conexion->errno === 1062) {
        echo "<script>
                alert('❌ Ya existe un usuario con ese documento o correo.');
                window.location.href='registrar_empleado.php';
              </script>";
        exit();
    }
    die("Error al registrar el empleado: " . $e->getMessage());
}

echo "<script>
        alert('✔ Empleado registrado con éxito.\\nDocumento: {$documento}\\nContraseña temporal: {$tempPassword}\\n(Compártela con el empleado, podrá cambiarla luego).');
        window.location.href='dashboard_admin.php';
      </script>";
