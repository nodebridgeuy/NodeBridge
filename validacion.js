function validarCorreo(valor) {
    const patronCorreo = /^[\w.-]+@[\w.-]+\.\w{2,}$/;
    
    if (!patronCorreo.test(valor)) {
        return false;
    }
    return true;
}


document.getElementById('Formulario').addEventListener('submit', function(event) {
    event.preventDefault();

    const inputCorreo = document.getElementById('correo').value;
    const mensajeError = document.getElementById('errorCorreo');

    if (inputCorreo.trim() === "") {
        mensajeError.style.color = "red";
        mensajeError.textContent = "El campo es obligatorio.";
    } else if (!validarCorreo(inputCorreo)) {
        mensajeError.style.color = "red";
        mensajeError.textContent = "Por favor, ingresa un correo válido.";
    } else {
        mensajeError.style.color = "green";
        mensajeError.textContent = "¡Correo válido!";
    }
});