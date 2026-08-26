// Contenedor de opciones
const panel = document.getElementById("panelOpciones");

// Función para crear tarjetas
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

// Opciones del administrador
panel.innerHTML += crearCard("fas fa-user-md", "Registrar Empleado", "Agrega o edita datos del personal médico.", "registrar_empleado.php");
panel.innerHTML += crearCard("fas fa-users-gear", "Gestionar Usuarios", "Edita, activa o desactiva usuarios existentes.", "gestionar_usuarios.php");
panel.innerHTML += crearCard("fas fa-clinic-medical", "Consultorios", "Registra consultorios y consulta su ocupación.", "registrar_consultorio.php");
panel.innerHTML += crearCard("fas fa-pills", "Inventario de Medicamentos", "Registra medicamentos y controla el stock.", "registrar_medicamento.php");
panel.innerHTML += crearCard("fas fa-users", "Registrar Usuario", "Gestiona los usuarios del sistema.", "registro_usuario.php");

// Cerrar sesión
document.getElementById("logout").addEventListener("click", () => {
  window.location.href = "cerrar_sesion.php";
});
