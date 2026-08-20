// Contenedor de opciones
const panel = document.getElementById("panelOpciones");

// Función para crear tarjetas
const crearCard = (icono, titulo, descripcion, enlace) => `
  <div class="col-md-6 col-lg-4">
    <div class="card">
      <i class="${icono} fa-2x"></i>
      <h5 class="mt-2">${titulo}</h5>
      <p>${descripcion}</p>
      <a href="${enlace}" class="btn btn-custom">Ir</a>
    </div>
  </div>
`;

// Opciones del administrador
panel.innerHTML += crearCard("fas fa-user-md", "Registrar Empleado", "Agrega o edita datos del personal médico.", "registrar_empleado.php");
panel.innerHTML += crearCard("fas fa-clinic-medical", "Registrar Consultorio", "Administra los consultorios disponibles.", "registrar_consultorio.php");
panel.innerHTML += crearCard("fas fa-pills", "Registrar Medicamento", "Controla los medicamentos disponibles.", "registrar_medicamento.php");
panel.innerHTML += crearCard("fas fa-users", "Registrar Usuario", "Gestiona los usuarios del sistema.", "registro_usuario.php");

// Cerrar sesión
document.getElementById("logout").addEventListener("click", () => {
  window.location.href = "cerrar_sesion.php";
});
