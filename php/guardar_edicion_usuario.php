<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('admin');
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: gestionar_usuarios.php");
    exit();
}

csrf_validar();

$idUsuario = filter_input(INPUT_POST, 'id_usuario', FILTER_VALIDATE_INT);
$tipoDocumento = $_POST['tipo_documento_usuario'] ?? '';
$documento = $_POST['documento_usuario'] ?? '';
$nombre = trim($_POST['nombre_usuario'] ?? '');
$apellido = trim($_POST['apellido_usuario'] ?? '');
$direccion = trim($_POST['direccion_usuario'] ?? '');
$telefono = trim($_POST['telefono_usuario'] ?? '');
$correo = trim($_POST['correo_electronico_usuario'] ?? '');

$tiposValidos = ['CC', 'CE', 'TI'];
if (!$idUsuario || !in_array($tipoDocumento, $tiposValidos) || !$documento || !$nombre || !$apellido || !$direccion || !$telefono || !$correo) {
    die("Error: faltan campos obligatorios o son inválidos.");
}

$stmtRol = $conexion->prepare("SELECT rol_usuario FROM usuario WHERE id_usuario = ?");
$stmtRol->bind_param("i", $idUsuario);
$stmtRol->execute();
$fila = $stmtRol->get_result()->fetch_assoc();

if (!$fila) {
    die("Usuario no encontrado.");
}
$rol = $fila['rol_usuario'];

// Documento o correo duplicados en OTRO usuario
$check = $conexion->prepare("SELECT id_usuario FROM usuario WHERE (documento_usuario = ? OR correo_electronico_usuario = ?) AND id_usuario <> ?");
$check->bind_param("ssi", $documento, $correo, $idUsuario);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    die("Error: ya existe otro usuario con ese documento o correo.");
}

$conexion->begin_transaction();
try {
    $stmt = $conexion->prepare("
        UPDATE usuario
        SET tipo_documento_usuario = ?, documento_usuario = ?, nombre_usuario = ?, apellido_usuario = ?,
            direccion_usuario = ?, telefono_usuario = ?, correo_electronico_usuario = ?
        WHERE id_usuario = ?
    ");
    $stmt->bind_param("sssssssi", $tipoDocumento, $documento, $nombre, $apellido, $direccion, $telefono, $correo, $idUsuario);
    $stmt->execute();

    if ($rol === 'medico') {
        $especialidad = trim($_POST['especialidad'] ?? '');
        $tarjetaProfesional = $_POST['tarjeta_profesional'] ?? '';
        $idConsultorio = filter_input(INPUT_POST, 'id_consultorio', FILTER_VALIDATE_INT);
        $dias = $_POST['dias'] ?? [];
        $horaInicio = $_POST['hora_inicio'] ?? '08:00';
        $horaFin = $_POST['hora_fin'] ?? '17:00';
        $diasValidos = array_values(array_intersect(array_map('intval', $dias), range(1, 7)));

        if (!$especialidad || !$tarjetaProfesional || !$idConsultorio || empty($diasValidos) || $horaInicio >= $horaFin) {
            throw new Exception("Para un médico debes indicar especialidad, tarjeta profesional, consultorio, al menos un día y un horario válido.");
        }

        $stmtMed = $conexion->prepare("UPDATE medico SET especialidad_medico = ?, numero_tarjeta_profesional = ?, id_consultorio = ? WHERE id_medico = ?");
        $stmtMed->bind_param("siii", $especialidad, $tarjetaProfesional, $idConsultorio, $idUsuario);
        $stmtMed->execute();

        // Propagar el nuevo consultorio a las citas pendientes de este médico
        $stmtActualizarCitas = $conexion->prepare("UPDATE cita SET id_consultorio = ? WHERE id_medico = ? AND estado_cita = 'pendiente'");
        $stmtActualizarCitas->bind_param("ii", $idConsultorio, $idUsuario);
        $stmtActualizarCitas->execute();

        $conexion->query("DELETE FROM horario_medico WHERE id_medico = " . (int) $idUsuario);
        $stmtHor = $conexion->prepare("INSERT INTO horario_medico (id_medico, dia_semana, hora_inicio, hora_fin, duracion_slot_min) VALUES (?, ?, ?, ?, 30)");
        foreach ($diasValidos as $dia) {
            $stmtHor->bind_param("iiss", $idUsuario, $dia, $horaInicio, $horaFin);
            $stmtHor->execute();
        }
    }

    $conexion->commit();
} catch (Exception $e) {
    $conexion->rollback();
    if ($conexion->errno === 1062) {
        die("Error: ya existe otro usuario con ese documento o correo.");
    }
    die("Error al guardar los cambios: " . $e->getMessage());
}

echo "<script>
        alert('✔ Usuario actualizado con éxito.');
        window.location.href='gestionar_usuarios.php';
      </script>";
