<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('paciente');
require 'conexion.php';

$stmt = $conexion->prepare("
    SELECT u.tipo_documento_usuario, u.documento_usuario, u.nombre_usuario, u.apellido_usuario,
           u.correo_electronico_usuario, u.direccion_usuario, u.telefono_usuario,
           p.fecha_nacimiento, p.eps_paciente, p.sexo
    FROM usuario u
    LEFT JOIN paciente p ON p.id_paciente = u.id_usuario
    WHERE u.id_usuario = ?
");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$perfil = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mi Perfil | MEDISOFT</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/dashboard.css" />
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../imagenes/logo medisoft.png" height="50" alt="Logo" loading="lazy" />
        <img src="../imagenes/letra medisoft.png" class="brand-text d-none d-sm-block" alt="Medisoft" />
      </a>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="btn btn-light text-primary" href="cerrar_sesion.php">Cerrar sesión</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container mt-4 mb-5" style="max-width: 600px;">
    <h3 class="text-center mb-4">Mi Perfil</h3>
    <div class="form-container">
      <dl class="row">
        <dt class="col-sm-5">Documento</dt>
        <dd class="col-sm-7"><?= htmlspecialchars($perfil['tipo_documento_usuario'] . ' ' . $perfil['documento_usuario']) ?></dd>

        <dt class="col-sm-5">Nombre completo</dt>
        <dd class="col-sm-7"><?= htmlspecialchars($perfil['nombre_usuario'] . ' ' . $perfil['apellido_usuario']) ?></dd>

        <dt class="col-sm-5">Correo</dt>
        <dd class="col-sm-7"><?= htmlspecialchars($perfil['correo_electronico_usuario']) ?></dd>

        <dt class="col-sm-5">Dirección</dt>
        <dd class="col-sm-7"><?= htmlspecialchars($perfil['direccion_usuario']) ?></dd>

        <dt class="col-sm-5">Teléfono</dt>
        <dd class="col-sm-7"><?= htmlspecialchars($perfil['telefono_usuario']) ?></dd>

        <dt class="col-sm-5">Fecha de nacimiento</dt>
        <dd class="col-sm-7"><?= htmlspecialchars($perfil['fecha_nacimiento'] ?? 'No registrada') ?></dd>

        <dt class="col-sm-5">EPS</dt>
        <dd class="col-sm-7"><?= htmlspecialchars($perfil['eps_paciente'] ?? 'No registrada') ?></dd>

        <dt class="col-sm-5">Sexo</dt>
        <dd class="col-sm-7"><?= htmlspecialchars($perfil['sexo'] ?? 'No registrado') ?></dd>
      </dl>
    </div>
  </div>

  <footer class="footer">
    © 2025 MEDISOFT | Sistema de Gestión Médica
  </footer>
</body>

</html>
