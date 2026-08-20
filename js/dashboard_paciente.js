/// Leer los datos que PHP dejó en el HTML
const nombreUsuario = document.getElementById("nombreUsuario").dataset.nombre;
const rolUsuario = document.getElementById("rolUsuario").dataset.rol;

// Actualizar inicial dinámicamente
document.getElementById("inicialUsuario").textContent =
  nombreUsuario.charAt(0).toUpperCase();

// Contenedor de las tarjetas
const panel = document.getElementById("panelOpciones");
panel.innerHTML = ""; // Limpia antes de agregar

// Plantillas de tarjetas
const crearCard = (icono, titulo, descripcion, enlace) => `
  <div class="col-md-4">
    <div class="card">
      <i class="${icono} fa-2x"></i>
      <h5 class="mt-2">${titulo}</h5>
      <p>${descripcion}</p>
      <a href="${enlace}" class="btn btn-custom btn-sm">Ir</a>
    </div>
  </div>
`;

// Opciones según rol
if (rolUsuario === "Administrador") {
  panel.innerHTML += crearCard("fas fa-user-md", "Registrar Empleado", "Agrega o edita datos del personal médico.", "registrar_empleado.php");
  panel.innerHTML += crearCard("fas fa-clinic-medical", "Registrar Consultorio", "Administra los consultorios disponibles.", "registrar_consultorio.php");
  panel.innerHTML += crearCard("fas fa-pills", "Registrar Medicamento", "Controla los medicamentos disponibles.", "registrar_medicamento.php");
  panel.innerHTML += crearCard("fas fa-notes-medical", "Registrar Tratamiento", "Gestiona los tratamientos médicos.", "registrar_tratamiento.php");
  panel.innerHTML += crearCard("fas fa-users", "Registrar Usuario", "Administra los usuarios del sistema.", "registro_usuario.php");
} else {
  panel.innerHTML += crearCard("fas fa-user", "Información Personal", "Consulta tus datos personales.", "perfil_paciente.php");
  panel.innerHTML += crearCard("fas fa-calendar-check", "Asignar Cita", "Programa tus citas médicas fácilmente.", "asignar_cita.php");
  panel.innerHTML += crearCard("fas fa-calendar-times", "Mis Citas", "Consulta o cancela tus citas médicas.", "mis_citas.php");
}

// Botón de cerrar sesión

document.getElementById("logout").addEventListener("click", function () {
    window.location.href = "../php/cerrar_sesion.php";
});


