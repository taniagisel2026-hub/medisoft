<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('admin');
require 'conexion.php';

$usuarios = $conexion->query("
    SELECT id_usuario, tipo_documento_usuario, documento_usuario, nombre_usuario, apellido_usuario,
           correo_electronico_usuario, rol_usuario, activo
    FROM usuario
    ORDER BY rol_usuario, nombre_usuario
")->fetch_all(MYSQLI_ASSOC);

$rolLabel = [
    'admin' => 'Administrador',
    'medico' => 'Médico',
    'recepcionista' => 'Recepcionista',
    'paciente' => 'Paciente',
];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestionar Usuarios | MEDISOFT</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/dashboard.css?v=10" />
  <style>
    .input-icon-wrapper { position: relative; }
    .input-icon-wrapper i.fa-icon-left {
      position: absolute; top: 50%; left: 14px; transform: translateY(-50%);
      color: #0a4c84; pointer-events: none;
    }
    .input-icon-wrapper input { padding-left: 2.4rem; }
  </style>
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

  <div class="container mt-4 mb-5">
    <h3 class="text-center mb-4">Gestionar Usuarios</h3>

    <div class="row justify-content-center mb-3">
      <div class="col-12 col-md-6 input-icon-wrapper">
        <i class="fas fa-search fa-icon-left"></i>
        <input type="text" id="buscarUsuario" class="form-control" placeholder="Buscar por nombre, documento o rol..." />
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-striped align-middle" id="tablaUsuarios">
        <thead>
          <tr>
            <th>Documento</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estado</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($usuarios as $u): ?>
            <tr data-filtro="<?= htmlspecialchars(mb_strtolower($u['nombre_usuario'] . ' ' . $u['apellido_usuario'] . ' ' . $u['documento_usuario'] . ' ' . ($rolLabel[$u['rol_usuario']] ?? $u['rol_usuario']))) ?>">
              <td><?= htmlspecialchars($u['tipo_documento_usuario'] . ' ' . $u['documento_usuario']) ?></td>
              <td><?= htmlspecialchars($u['nombre_usuario'] . ' ' . $u['apellido_usuario']) ?></td>
              <td><?= htmlspecialchars($u['correo_electronico_usuario']) ?></td>
              <td><?= htmlspecialchars($rolLabel[$u['rol_usuario']] ?? $u['rol_usuario']) ?></td>
              <td>
                <span class="badge <?= $u['activo'] ? 'bg-success' : 'bg-secondary' ?>">
                  <?= $u['activo'] ? 'Activo' : 'Inactivo' ?>
                </span>
              </td>
              <td>
                <div class="d-flex gap-1">
                  <a href="editar_usuario.php?id_usuario=<?= (int) $u['id_usuario'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                  <?php if ((int) $u['id_usuario'] !== (int) $_SESSION['id']): ?>
                    <form action="cambiar_estado_usuario.php" method="POST" onsubmit="return confirm('¿<?= $u['activo'] ? 'Desactivar' : 'Activar' ?> a este usuario?');">
                    <?= csrf_campo() ?>
                      <input type="hidden" name="id_usuario" value="<?= (int) $u['id_usuario'] ?>">
                      <button type="submit" class="btn btn-sm <?= $u['activo'] ? 'btn-outline-danger' : 'btn-outline-success' ?>">
                        <?= $u['activo'] ? 'Desactivar' : 'Activar' ?>
                      </button>
                    </form>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <footer class="footer">
    © 2025 MEDISOFT | Sistema de Gestión Médica
  </footer>

  <script>
    document.getElementById("buscarUsuario").addEventListener("input", function () {
      const texto = this.value.trim().toLowerCase();
      document.querySelectorAll("#tablaUsuarios tbody tr").forEach(fila => {
        fila.style.display = fila.dataset.filtro.includes(texto) ? "" : "none";
      });
    });
  </script>
  <script src="../js/bfcache_guard.js"></script>
</body>

</html>
