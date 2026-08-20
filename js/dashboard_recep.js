const panel = document.getElementById("panelOpciones");

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

panel.innerHTML += crearCard("fas fa-calendar-plus", "Asignar Cita Médica", "Registra nuevas citas para pacientes.", "asignar_cita.php");
panel.innerHTML += crearCard("fas fa-calendar-times", "Citas Agendadas", "Consulta o cancela citas de cualquier paciente.", "mis_citas.php");
panel.innerHTML += crearCard("fas fa-user-plus", "Registrar Usuario", "Registra nuevos pacientes en el sistema.", "registro_usuario.php");

document.getElementById("logout").addEventListener("click", () => {
  window.location.href = "cerrar_sesion.php";
});
