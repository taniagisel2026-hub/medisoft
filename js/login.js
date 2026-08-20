document.getElementById("loginForm").addEventListener("submit", function(e) {
  e.preventDefault(); // Evita que recargue la página

  const formData = new FormData(this);

  // Enviar los datos al archivo PHP
  fetch("php/validar_login.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.estado === "ok") {
      // Guardar los datos del usuario en el almacenamiento local
      localStorage.setItem("usuarioNombre", data.nombre);
      localStorage.setItem("usuarioApellido", data.apellido);

      // Redirigir a la página principal
      window.location.href = "index.html";
    } else {
      alert("Datos incorrectos. Verifica tu usuario o contraseña.");
    }
  })
  .catch(error => {
    console.error("Error al conectar con el servidor:", error);
    alert("Error en la conexión con el servidor.");
  });
});

// Mostrar / ocultar contraseña
function togglePassword(inputId, iconElement) {
  const input = document.getElementById(inputId);
  const type = input.type === 'password' ? 'text' : 'password';
  input.type = type;
  iconElement.classList.toggle('fa-eye');
  iconElement.classList.toggle('fa-eye-slash');
}

// Efecto de botones
document.addEventListener("DOMContentLoaded", function () {
  const loginBtn = document.getElementById("loginBtn");
  const registerBtn = document.getElementById("registerBtn");

  function activarBoton(activo, inactivo) {
    activo.classList.remove("btn-outline-light", "text-white");
    activo.classList.add("btn-light", "text-primary");

    inactivo.classList.remove("btn-light", "text-primary");
    inactivo.classList.add("btn-outline-light", "text-white");
  }

  loginBtn.addEventListener("click", () => activarBoton(loginBtn, registerBtn));
  registerBtn.addEventListener("click", () => activarBoton(registerBtn, loginBtn));
});

// Resaltar enlaces del navbar
document.addEventListener("DOMContentLoaded", function () {
  const links = document.querySelectorAll(".nav-link");

  links.forEach(link => {
    link.addEventListener("click", function () {
      links.forEach(l => l.classList.remove("active"));
      this.classList.add("active");
    });
  });
});
