const nombreUsuario = localStorage.getItem("usuario") || "Médico";
const rolUsuario = localStorage.getItem("rol") || "Empleado Médico";

document.getElementById("nombreUsuario").textContent = nombreUsuario;
document.getElementById("rolUsuario").textContent = rolUsuario;
document.getElementById("inicialUsuario").textContent = nombreUsuario.charAt(0).toUpperCase();

const panel = document.getElementById("panelOpciones");

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

panel.innerHTML += crearCard("fas fa-user", "Información Personal", "Consulta y edita tus datos personales.", "perfil_paciente.html");
panel.innerHTML += crearCard("fas fa-pills", "Registrar Medicamento", "Controla los medicamentos recetados.", "registrar_medicamento.php");
panel.innerHTML += crearCard("fas fa-notes-medical", "Registrar Tratamiento", "Agrega tratamientos a los pacientes.", "registrar_tratamiento.php");

document.getElementById("logout").addEventListener("click", () => {
  localStorage.removeItem("usuario");
  localStorage.removeItem("rol");
  window.location.href = "cerrar_sesion.php";
});
