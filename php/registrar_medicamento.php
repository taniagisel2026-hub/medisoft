<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol(['admin', 'medico']);
require 'conexion.php';

$medicamentos = $conexion->query("SELECT id_medicamento, nombre_medicamento, descripcion_medicamento, dosis, cantidad_disponible FROM medicamento ORDER BY nombre_medicamento")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registrar Medicamento | MEDISOFT</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
    <link href="../css/registrar_medicamento.css" rel="stylesheet" />
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="../imagenes/logo medisoft.png" height="50" alt="Logo" loading="lazy" />
                <img src="../imagenes/letra medisoft.png" class="brand-text d-none d-sm-block" alt="Medisoft" />
            </a>
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="btn btn-light text-primary" href="cerrar_sesion.php" id="logout">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Fin navbar -->

    <!-- Contenido principal -->
    <div class="main-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="form-container">
                        <h3>Registrar Medicamento</h3>
                        <form id="medicamentoForm" action="guardar_medicamento.php" method="POST">
                        <?= csrf_campo() ?>
                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-capsules fa-icon-left"></i>
                                <input type="text" id="nombre" name="nombre" class="form-control"
                                    placeholder="Nombre del medicamento" required />
                            </div>

                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-notes-medical fa-icon-left"></i>
                                <input type="text" id="descripcion" name="descripcion" class="form-control"
                                    placeholder="Descripción" required />
                            </div>

                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-pills fa-icon-left"></i>
                                <input type="text" id="dosis" name="dosis" class="form-control"
                                    placeholder="Dosis (ej. 500mg cada 8 horas)" required />
                            </div>

                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-boxes-stacked fa-icon-left"></i>
                                <input type="number" id="cantidad_disponible" name="cantidad_disponible" class="form-control"
                                    placeholder="Cantidad inicial en inventario" min="0" required />
                            </div>

                            <button type="submit" class="btn custom-register-btn w-100">REGISTRAR</button>
                        </form>

                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-5">
                <div class="col-12 col-lg-10">
                    <h4 class="text-center mb-3">Inventario de Medicamentos</h4>
                    <?php if (empty($medicamentos)): ?>
                        <p class="text-center text-muted">No hay medicamentos registrados todavía.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped align-middle bg-white">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Dosis</th>
                                        <th>Stock</th>
                                        <?php if ($_SESSION['rol'] === 'admin'): ?><th></th><?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($medicamentos as $m): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($m['nombre_medicamento']) ?></td>
                                            <td><?= htmlspecialchars($m['descripcion_medicamento']) ?></td>
                                            <td><?= htmlspecialchars($m['dosis']) ?></td>
                                            <td>
                                                <span class="badge <?= $m['cantidad_disponible'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                                                    <?= (int) $m['cantidad_disponible'] ?>
                                                </span>
                                            </td>
                                            <?php if ($_SESSION['rol'] === 'admin'): ?>
                                            <td>
                                                <form action="ajustar_stock_medicamento.php" method="POST" class="d-flex gap-1">
                                                <?= csrf_campo() ?>
                                                    <input type="hidden" name="id_medicamento" value="<?= (int) $m['id_medicamento'] ?>">
                                                    <input type="number" name="cantidad" class="form-control form-control-sm" style="width: 90px;" placeholder="+cantidad" required>
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">Agregar</button>
                                                </form>
                                            </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
  <script src="../js/bfcache_guard.js"></script>
</body>

</html>