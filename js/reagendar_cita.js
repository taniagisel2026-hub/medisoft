document.addEventListener("DOMContentLoaded", function () {
  const fechaInput = document.getElementById("fecha");
  const horasContenedor = document.getElementById("horasDisponibles");
  const horaInput = document.getElementById("hora");
  const horaTexto = document.getElementById("horaSeleccionadaTexto");

  const hoy = new Date().toISOString().split("T")[0];
  fechaInput.min = hoy;

  fechaInput.addEventListener("change", function () {
    horaInput.value = "";
    horaTexto.textContent = "";

    const fecha = fechaInput.value;
    if (!fecha) return;

    horasContenedor.innerHTML = '<span class="text-muted small">Cargando horarios...</span>';

    fetch(`horarios_disponibles.php?id_medico=${ID_MEDICO}&fecha=${fecha}&excluir_cita=${ID_CITA}`)
      .then(res => res.json())
      .then(data => {
        if (!data.disponibles || data.disponibles.length === 0) {
          horasContenedor.innerHTML = '<span class="text-muted small">No hay horas disponibles ese día.</span>';
          return;
        }
        horasContenedor.innerHTML = "";
        data.disponibles.forEach(hora => {
          const btn = document.createElement("button");
          btn.type = "button";
          btn.className = "hora-btn";
          btn.textContent = hora;
          btn.addEventListener("click", () => {
            horaInput.value = hora;
            horasContenedor.querySelectorAll(".hora-btn").forEach(b => b.classList.remove("seleccionada"));
            btn.classList.add("seleccionada");
            horaTexto.textContent = `Hora seleccionada: ${hora}`;
          });
          horasContenedor.appendChild(btn);
        });
      })
      .catch(() => {
        horasContenedor.innerHTML = '<span class="text-danger small">No se pudieron cargar los horarios.</span>';
      });
  });

  document.getElementById("reagendarForm").addEventListener("submit", function (e) {
    if (!horaInput.value) {
      e.preventDefault();
      alert("⚠ Selecciona una hora disponible.");
    }
  });
});
