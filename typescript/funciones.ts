class Validator {
    static onlyLetters(value: string, maxLength: number = 50): boolean {
        const regex = /^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/;
        return regex.test(value) && value.length <= maxLength;
    }

    static celular(value: string, maxLength: number = 8): boolean {
        const regex = /^[0-9]+$/;
        return regex.test(value) && value.length <= maxLength;
    }

    static validateLength(value: string, minLength: number, maxLength: number): boolean {
        return value.length >= minLength && value.length <= maxLength;
    }
}

// Obtener los elementos del formulario
const form = document.getElementById("register-form") as HTMLFormElement;
const carnetInput = document.querySelector("input[name='carnet']") as HTMLInputElement;
const nombreInput = document.querySelector("input[name='nombres']") as HTMLInputElement;
const apellidosInput = document.querySelector("input[name='apellidos']") as HTMLInputElement;
const celularInput = document.querySelector("input[name='celular']") as HTMLInputElement;
const passwordInput = document.querySelector("input[name='password']") as HTMLInputElement;
const seccionInput = document.querySelector("input[name='seccion']") as HTMLInputElement;



const errorNombre = document.getElementById("errorNombre") as HTMLSpanElement;
const errorTelefono = document.getElementById("errorTelefono") as HTMLSpanElement;
const errorPassword = document.getElementById("errorPassword") as HTMLSpanElement;

// Manejo del envío del formulario
form.addEventListener("submit", (event) => {
    event.preventDefault(); // Evita que el formulario se envíe automáticamente

    let isValid = true;

    // Validación del nombre
    if (!Validator.onlyLetters(nombreInput.value)) {
        errorNombre.textContent = "Solo se permiten letras (máx. 50 caracteres).";
        isValid = false;
    } else {
        errorNombre.textContent = "";
    }

    // Validación del teléfono
    if (!Validator.celular(celularInput.value)) {
        errorTelefono.textContent = "Solo se permiten números (máx. 8 caracteres).";
        isValid = false;
    } else {
        errorTelefono.textContent = "";
    }

    // Validación de la contraseña
    if (!Validator.validateLength(passwordInput.value, 6, 12)) {
        errorPassword.textContent = "Debe tener entre 6 y 12 caracteres.";
        isValid = false;
    } else {
        errorPassword.textContent = "";
    }

    // Si todo es válido, mostrar en consola los datos
    if (isValid) {
        console.log("Nombre:", nombreInput.value);
        console.log("Teléfono:", celularInput.value);
        console.log("Contraseña:", passwordInput.value);
        alert("¡Formulario enviado con éxito!");
    }
});
