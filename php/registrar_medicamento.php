<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol(['admin', 'medico']);
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

                            <button type="submit" class="btn custom-register-btn w-100">REGISTRAR</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
</body>

</html>