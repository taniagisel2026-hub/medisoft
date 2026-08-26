<?php
require 'conexion.php';

$token = $_GET['token'] ?? $_POST['token'] ?? '';
$error = null;
$exito = false;

$usuario = null;
if ($token) {
    $stmt = $conexion->prepare("SELECT id_usuario, nombre_usuario FROM usuario WHERE reset_token = ? AND reset_token_expira > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();
}

if (!$token || !$usuario) {
    $error = 'Este enlace no es válido o ya venció. Solicita uno nuevo.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nueva = $_POST['nueva_contrasena'] ?? '';
    $confirmar = $_POST['confirmar_contrasena'] ?? '';

    if (strlen($nueva) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($nueva !== $confirmar) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        $hash = password_hash($nueva, PASSWORD_DEFAULT);
        $update = $conexion->prepare("UPDATE usuario SET contrasena_usuario = ?, reset_token = NULL, reset_token_expira = NULL WHERE id_usuario = ?");
        $update->bind_param("si", $hash, $usuario['id_usuario']);
        $update->execute();
        $exito = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Restablecer contraseña | MEDISOFT</title>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/login.css" />
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="../index.html">
        <img src="../imagenes/logo medisoft.png" height="50" alt="Logo" loading="lazy" />
        <img src="../imagenes/letra medisoft.png" class="brand-text d-none d-sm-block" alt="Medisoft" />
      </a>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row vh-100">
      <div class="col-md-6 d-none d-md-block">
        <img src="../imagenes/PANEL MEDISOFT.png" alt="Login image" class="img-fluid"
          style="width: 100%; height: calc(100vh - 56px); object-fit: cover;">
      </div>

      <div class="col-md-6 d-flex align-items-center justify-content-center bg-light">
        <div class="form-container bg-white p-4 shadow rounded mx-auto" style="max-width: 420px; width: 100%;">

          <?php if ($exito): ?>
            <h3 class="text-center mb-3">¡Contraseña actualizada!</h3>
            <p class="text-center text-muted small mb-4">Ya puedes iniciar sesión con tu nueva contraseña.</p>
            <a href="../login.html" class="btn custom-register-btn w-100">IR A INICIAR SESIÓN</a>

          <?php elseif ($error && !$usuario): ?>
            <h3 class="text-center mb-3">Enlace no válido</h3>
            <div class="alert alert-danger small"><?= htmlspecialchars($error) ?></div>
            <a href="recuperar_contrasena.php" class="btn custom-register-btn w-100">SOLICITAR UN ENLACE NUEVO</a>

          <?php else: ?>
            <h3 class="text-center mb-3">Nueva contraseña</h3>
            <p class="text-muted text-center small mb-4">Hola <?= htmlspecialchars($usuario['nombre_usuario']) ?>, escribe tu nueva contraseña.</p>

            <?php if ($error): ?>
              <div class="alert alert-danger small"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
              <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

              <div class="mb-3 input-icon-wrapper">
                <i class="fas fa-lock fa-icon-left"></i>
                <input type="password" class="form-control" name="nueva_contrasena" placeholder="Nueva contraseña" required minlength="6" />
              </div>

              <div class="mb-3 input-icon-wrapper">
                <i class="fas fa-lock fa-icon-left"></i>
                <input type="password" class="form-control" name="confirmar_contrasena" placeholder="Confirmar contraseña" required minlength="6" />
              </div>

              <button type="submit" class="btn custom-register-btn w-100">GUARDAR CONTRASEÑA</button>
            </form>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</body>

</html>
