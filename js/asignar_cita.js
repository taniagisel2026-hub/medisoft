document.addEventListener("DOMContentLoaded", function () {
  const especialidadSelect = document.getElementById("especialidad");
  const medicoSelect = document.getElementById("medico");
  const fechaInput = document.getElementById("fecha");
  const horasContenedor = document.getElementById("horasDisponibles");
  const horaInput = document.getElementById("hora");
  const horaTexto = document.getElementById("horaSeleccionadaTexto");

  const hoy = new Date().toISOString().split("T")[0];
  fechaInput.min = hoy;

  especialidadSelect.addEventListener("change", function () {
    const especialidad = this.value.trim().toLowerCase();
    const disponibles = MEDICOS.filter(m => m.especialidad_medico.trim().toLowerCase() === especialidad);

    medicoSelect.innerHTML = '<option selected disabled value="">Selecciona el médico</option>';
    disponibles.forEach(m => {
      const opt = document.createElement("option");
      opt.value = m.id_medico;
      opt.textContent = `${m.nombre_usuario} ${m.apellido_usuario}`;
      medicoSelect.appendChild(opt);
    });

    medicoSelect.disabled = disponibles.length === 0;
    fechaInput.disabled = true;
    fechaInput.value = "";
    resetHoras();
  });

  medicoSelect.addEventListener("change", function () {
    fechaInput.disabled = !this.value;
    resetHoras();
  });

  fechaInput.addEventListener("change", cargarHorasDisponibles);
  medicoSelect.addEventListener("change", cargarHorasDisponibles);

  function resetHoras() {
    horaInput.value = "";
    horaTexto.textContent = "";
    horasContenedor.innerHTML = '<span class="text-muted small">Elige médico y fecha para ver las horas disponibles.</span>';
  }

  function cargarHorasDisponibles() {
    const idMedico = medicoSelect.value;
    const fecha = fechaInput.value;
    if (!idMedico || !fecha) return;

    horaInput.value = "";
    horasContenedor.innerHTML = '<span class="text-muted small">Cargando horarios...</span>';

    fetch(`horarios_disponibles.php?id_medico=${idMedico}&fecha=${fecha}`)
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
  }

  document.getElementById("citaForm").addEventListener("submit", function (e) {
    if (!horaInput.value) {
      e.preventDefault();
      alert("⚠ Selecciona una hora disponible.");
    }
  });

  if (ES_RECEPCIONISTA) {
    const btnBuscar = document.getElementById("buscarPacienteBtn");
    const documentoInput = document.getElementById("documentoPaciente");
    const resultado = document.getElementById("pacienteEncontrado");
    const idPacienteInput = document.getElementById("id_paciente");

    btnBuscar.addEventListener("click", function () {
      const documento = documentoInput.value.trim();
      if (!documento) return;

      fetch(`buscar_paciente.php?documento=${encodeURIComponent(documento)}`)
        .then(res => res.json())
        .then(data => {
          if (data.encontrado) {
            idPacienteInput.value = data.id_paciente;
            resultado.style.display = "block";
            resultado.className = "mb-3 small text-success";
            resultado.textContent = `✔ Paciente: ${data.nombre} ${data.apellido}`;
          } else {
            idPacienteInput.value = "";
            resultado.style.display = "block";
            resultado.className = "mb-3 small text-danger";
            resultado.textContent = "❌ No se encontró ningún paciente con ese documento.";
          }
        })
        .catch(() => {
          idPacienteInput.value = "";
          resultado.style.display = "block";
          resultado.className = "mb-3 small text-danger";
          resultado.textContent = "❌ Ocurrió un error buscando el paciente. Intenta de nuevo o vuelve a iniciar sesión.";
        });
    });
  }
});
