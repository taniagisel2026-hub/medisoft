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
                        <h3>Registrar Empleado</h3>
                        <form id="empleadoForm" action="guardar_empleado.php" method="POST">

                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-id-card fa-icon-left"></i>
                                <select id="tipoIdentificacion" name="tipo_documento_usuario" class="form-select" required>
                                    <option selected disabled value="">Tipo de identificación</option>
                                    <option value="CC">Cédula de Ciudadanía</option>
                                    <option value="CE">Cédula de Extranjería</option>
                                    <option value="TI">Tarjeta de Identidad</option>
                                </select>
                            </div>

                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-user fa-icon-left"></i>
                                <input type="number" id="numeroDocumento" name="documento_usuario" class="form-control"
                                    placeholder="Número de documento del empleado" required />
                            </div>

                            <div class="row gx-2">
                                <div class="col-6 mb-3 input-icon-wrapper">
                                    <i class="fas fa-user fa-icon-left"></i>
                                    <input type="text" id="nombres" name="nombre_usuario" class="form-control"
                                        placeholder="Nombres" required />
                                </div>
                                <div class="col-6 mb-3 input-icon-wrapper">
                                    <i class="fas fa-user fa-icon-left"></i>
                                    <input type="text" id="apellidos" name="apellido_usuario" class="form-control"
                                        placeholder="Apellidos" required />
                                </div>
                            </div>

                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-location-dot fa-icon-left"></i>
                                <input type="text" id="direccion" name="direccion_usuario" class="form-control"
                                    placeholder="Dirección" required />
                            </div>

                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-mobile-alt fa-icon-left"></i>
                                <input type="number" id="celular" name="telefono_usuario" class="form-control"
                                    placeholder="Número de celular" required />
                            </div>

                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-envelope fa-icon-left"></i>
                                <input type="email" id="correo" name="correo_electronico_usuario" class="form-control"
                                    placeholder="Correo" required />
                            </div>

                            <div class="mb-3 input-icon-wrapper">
                                <i class="fas fa-id-badge fa-icon-left"></i>
                                <select id="cargo" name="cargo" class="form-select" required>
                                    <option selected disabled value="">Cargo</option>
                                    <option value="medico">Médico</option>
                                    <option value="recepcionista">Recepcionista</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>

                            <!-- Campos exclusivos para médico -->
                            <div id="camposMedico" style="display:none;">
                                <div class="mb-3 input-icon-wrapper">
                                    <i class="fas fa-stethoscope fa-icon-left"></i>
                                    <input type="text" id="especialidad" name="especialidad" class="form-control"
                                        placeholder="Especialidad médica" />
                                </div>

                                <div class="mb-3 input-icon-wrapper">
                                    <i class="fas fa-id-badge fa-icon-left"></i>
                                    <input type="number" id="tarjetaProfesional" name="tarjeta_profesional"
                                        class="form-control" placeholder="Número de tarjeta profesional" />
                                </div>

                                <label class="form-label d-block">Horario de atención</label>
                                <div class="mb-2 d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias[]" value="1" id="dia1" checked>
                                        <label class="form-check-label" for="dia1">Lun</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias[]" value="2" id="dia2" checked>
                                        <label class="form-check-label" for="dia2">Mar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias[]" value="3" id="dia3" checked>
                                        <label class="form-check-label" for="dia3">Mié</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias[]" value="4" id="dia4" checked>
                                        <label class="form-check-label" for="dia4">Jue</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias[]" value="5" id="dia5" checked>
                                        <label class="form-check-label" for="dia5">Vie</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias[]" value="6" id="dia6">
                                        <label class="form-check-label" for="dia6">Sáb</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias[]" value="7" id="dia7">
                                        <label class="form-check-label" for="dia7">Dom</label>
                                    </div>
                                </div>
                                <div class="row gx-2">
                                    <div class="col-6 mb-3 input-icon-wrapper">
                                        <i class="fas fa-clock fa-icon-left"></i>
                                        <input type="time" name="hora_inicio" class="form-control" value="08:00">
                                    </div>
                                    <div class="col-6 mb-3 input-icon-wrapper">
                                        <i class="fas fa-clock fa-icon-left"></i>
                                        <input type="time" name="hora_fin" class="form-control" value="17:00">
                                    </div>
                                </div>
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
