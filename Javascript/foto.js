const video = document.getElementById("video");
const canvas = document.getElementById("canvas");
const preview = document.getElementById("preview");
const btnCapturar = document.getElementById("capturar-foto");
const inputFoto = document.getElementById("foto");

async function iniciarCamara() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = stream;
    } catch (error) {
        console.error("Error al acceder a la cámara:", error);
        alert("No se pudo acceder a la cámara.");
    }
}

btnCapturar.addEventListener("click", () => {
    const context = canvas.getContext("2d");
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    const dataURL = canvas.toDataURL("image/png");
    preview.src = dataURL;
    preview.style.display = "block";

    inputFoto.value = dataURL; // Asignamos la foto como base64 al input
});

window.addEventListener("load", iniciarCamara);
