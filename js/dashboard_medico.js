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

panel.innerHTML += crearCard("fas fa-calendar-check", "Mis Citas", "Consulta tu agenda de citas asignadas.", "mis_citas.php");
panel.innerHTML += crearCard("fas fa-pills", "Registrar Medicamento", "Controla los medicamentos recetados.", "registrar_medicamento.php");
panel.innerHTML += crearCard("fas fa-notes-medical", "Registrar Tratamiento", "Agrega tratamientos a los pacientes.", "registrar_tratamiento.php");

document.getElementById("logout").addEventListener("click", () => {
  window.location.href = "cerrar_sesion.php";
});
