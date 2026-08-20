// ========================
// MOSTRAR / OCULTAR CONTRASEÑA
// ========================
function togglePassword(inputId, iconElement) {
    const input = document.getElementById(inputId);
    const tipoNuevo = input.type === "password" ? "text" : "password";
    input.type = tipoNuevo;
    iconElement.classList.toggle("fa-eye");
    iconElement.classList.toggle("fa-eye-slash");
}

// ========================
// VALIDACIÓN DEL FORMULARIO
// ========================
document.getElementById("usuarioform").addEventListener("submit", function (e) {
    const telefono = document.querySelector('input[name="telefono_usuario"]').value.trim();
    const password = document.getElementById("password").value.trim();
    const confirmPassword = document.getElementById("confirmPassword").value.trim();

    // Validar teléfono
    if (!/^\d{10}$/.test(telefono)) {
        e.preventDefault();
        alert("⚠ El teléfono debe tener exactamente 10 números.");
        return;
    }

    // Validar contraseñas iguales
    if (password !== confirmPassword) {
        e.preventDefault();
        alert("⚠ Las contraseñas NO coinciden.");
        return;
    }

    // Si todo está bien, permitir enviar al PHP
});
