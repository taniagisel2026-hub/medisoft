<?php
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('medico');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registrar Tratamiento | MEDISOFT</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/registrar_tratamiento.css" />
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="../imagenes/logo medisoft.png" height="50" alt="Logo" loading="lazy" />
                <img src="../imagenes/letra medisoft.png" class="brand-text d-none d-sm-block" alt="Medisoft" />
            </a>
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarContent">
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
                        <h3>Registrar Tratamiento</h3>
                        <form id="tratamientoForm" action="guardar_tratamiento.php" method="POST">
                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-file-medical fa-icon-left"></i>
                                <input type="text" id="nombre" name="nombre" class="form-control"
                                    placeholder="Nombre del tratamiento" required />
                            </div>
                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-notes-medical fa-icon-left"></i>
                                <input type="text" id="descripcion" name="descripcion" class="form-control"
                                    placeholder="Descripción del tratamiento" required />
                            </div>
                            <button type="submit" class="btn custom-register-btn w-100">REGISTRAR</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
</body>

</html>