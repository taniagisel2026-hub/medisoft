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

 document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll(".nav-link");

    links.forEach(link => {
      link.addEventListener("click", function () {
        links.forEach(l => l.classList.remove("active")); // quitar a todos
        this.classList.add("active"); // agregar al clicado
      });
    });
  });

document.getElementById('medicamentoForm').addEventListener('submit', function (e) {
  e.preventDefault();
  alert('¡Medicamento registrado correctamente!');
  
});

