// Simulación de datos (vendrán del login real)
const nombreUsuario = localStorage.getItem("usuario") || "Administrador";
const rolUsuario = localStorage.getItem("rol") || "Administrador";

// Mostrar datos del perfil
document.getElementById("nombreUsuario").textContent = nombreUsuario;
document.getElementById("rolUsuario").textContent = rolUsuario;
document.getElementById("inicialUsuario").textContent = nombreUsuario.charAt(0).toUpperCase();

// Contenedor de opciones
const panel = document.getElementById("panelOpciones");

// Función para crear tarjetas
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

// Opciones del administrador
panel.innerHTML += crearCard("fas fa-user-md", "Registrar Empleado", "Agrega o edita datos del personal médico.", "registrar_empleado.php");
panel.innerHTML += crearCard("fas fa-clinic-medical", "Registrar Consultorio", "Administra los consultorios disponibles.", "registrar_consultorio.php");
panel.innerHTML += crearCard("fas fa-users", "Registrar Usuario", "Gestiona los usuarios del sistema.", "registro_usuario.php");

// Cerrar sesión
document.getElementById("logout").addEventListener("click", () => {
  localStorage.removeItem("usuario");
  localStorage.removeItem("rol");
  window.location.href = "cerrar_sesion.php";
});
