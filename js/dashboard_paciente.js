// Contenedor de las tarjetas
const panel = document.getElementById("panelOpciones");

// Plantillas de tarjetas
const crearCard = (icono, titulo, descripcion, enlace) => `
  <div class="panel-card-col">
    <div class="card">
      <i class="${icono} fa-2x"></i>
      <h5 class="mt-2">${titulo}</h5>
      <p>${descripcion}</p>
      <a href="${enlace}" class="btn btn-custom">Ir</a>
    </div>
  </div>
`;

panel.innerHTML += crearCard("fas fa-user", "Información Personal", "Consulta tus datos personales.", "perfil_paciente.php");
panel.innerHTML += crearCard("fas fa-calendar-check", "Asignar Cita", "Programa tus citas médicas fácilmente.", "asignar_cita.php");
panel.innerHTML += crearCard("fas fa-calendar-times", "Mis Citas", "Consulta o cancela tus citas médicas.", "mis_citas.php");

// Botón de cerrar sesión
document.getElementById("logout").addEventListener("click", function () {
    window.location.href = "cerrar_sesion.php";
});
