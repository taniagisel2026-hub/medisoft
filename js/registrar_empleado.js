document.addEventListener("DOMContentLoaded", function () {
  const cargoSelect = document.getElementById("cargo");
  const camposMedico = document.getElementById("camposMedico");
  const especialidad = document.getElementById("especialidad");
  const tarjetaProfesional = document.getElementById("tarjetaProfesional");

  function actualizarCamposMedico() {
    const esMedico = cargoSelect.value === "medico";
    camposMedico.style.display = esMedico ? "block" : "none";
    especialidad.required = esMedico;
    tarjetaProfesional.required = esMedico;
  }

  cargoSelect.addEventListener("change", actualizarCamposMedico);
  actualizarCamposMedico();
});
