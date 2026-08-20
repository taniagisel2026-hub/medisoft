const nombreUsuario = localStorage.getItem("usuario") || "Recepcionista";
const rolUsuario = localStorage.getItem("rol") || "Empleado Recepcionista";

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

panel.innerHTML += crearCard("fas fa-calendar-plus", "Asignar Cita Médica", "Registra nuevas citas para pacientes.", "asignar_cita.php");
panel.innerHTML += crearCard("fas fa-calendar-times", "Cancelar Cita Médica", "Gestiona cancelaciones de citas.", "cancelar_cita.html");
panel.innerHTML += crearCard("fas fa-user-plus", "Registrar Usuario", "Registra nuevos pacientes en el sistema.", "registro_usuario.php");

document.getElementById("logout").addEventListener("click", () => {
  localStorage.removeItem("usuario");
  localStorage.removeItem("rol");
  window.location.href = "cerrar_sesion.php";
});
