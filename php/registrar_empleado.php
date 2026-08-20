<?php
// Proteger acceso solo para ADMIN
include 'verificar_sesion.php';
include 'verificar_rol.php';
permitirRol('admin');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registrar Empleado | MEDISOFT</title>

    <!-- Estilos externos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/registrar_empleado.css" />
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
                    <a class="btn btn-light text-primary" href="#" id="logout">Cerrar sesión</a>
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
                        <h3>Registrar Empleado</h3>
                        <form id="empleadoForm">

                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-id-card fa-icon-left"></i>
                                <select id="tipoIdentificacion" name="tipoIdentificacion" class="form-select" required>
                                    <option selected disabled>Tipo de identificación</option>
                                    <option>Cédula de Ciudadanía</option>
                                    <option>Cédula de Extranjería</option>
                                    <option>Tarjeta de Identidad</option>
                                </select>
                            </div>

                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-user fa-icon-left"></i>
                                <input type="number" id="numeroDocumento" name="numeroDocumento" class="form-control"
                                    placeholder="Número de documento del empleado" required />
                            </div>

                            <div class="row gx-2">
                                <div class="col-6 mb-3 input-icon-wrapper">
                                    <i class="fas fa-user fa-icon-left"></i>
                                    <input type="text" id="nombres" name="nombres" class="form-control"
                                        placeholder="Nombres" required />
                                </div>
                                <div class="col-6 mb-3 input-icon-wrapper">
                                    <i class="fas fa-user fa-icon-left"></i>
                                    <input type="text" id="apellidos" name="apellidos" class="form-control"
                                        placeholder="Apellidos" required />
                                </div>
                            </div>

                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-id-badge fa-icon-left"></i>
                                <input type="text" id="cargo" name="cargo" class="form-control" placeholder="Cargo"
                                    required />
                            </div>

                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-stethoscope fa-icon-left"></i>
                                <select id="especialidad" name="especialidad" class="form-select">
                                    <option selected disabled>Especialidad (si aplica)</option>
                                    <option>Medicina general</option>
                                    <option>Cardiología</option>
                                    <option>Dermatología</option>
                                    <option>Oftalmología</option>
                                </select>
                            </div>

                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-mobile-alt fa-icon-left"></i>
                                <input type="number" id="celular" name="celular" class="form-control"
                                    placeholder="Número de celular" required />
                            </div>

                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-envelope fa-icon-left"></i>
                                <input type="email" id="correo" name="correo" class="form-control" placeholder="Correo"
                                    required />
                            </div>

                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-house-medical fa-icon-left"></i>
                                <input type="number" id="consultorio" name="consultorio" class="form-control"
                                    placeholder="Consultorio asignado (si aplica)" />
                            </div>

                            <!-- Botón -->
                            <button type="submit" class="btn custom-register-btn w-100">REGISTRAR</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
    <script src="../js/registrar_empleado.js"></script>
</body>

</html>