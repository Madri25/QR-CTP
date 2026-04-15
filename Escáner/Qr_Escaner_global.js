let scanning = false; // Variable para controlar el estado de escaneo
const successSound = new Audio('./qr_recursos/Audio.mp3'); // Sonido de éxito
const errorSound = new Audio('./qr_recursos/error.mp3'); // Sonido de error

// Inicializa el número de asientos disponibles
let totalAsientos = 0;
let asientosDisponibles = 0;

// Establecer el número total de asientos al cargar
document.getElementById("totalAsientos").addEventListener('input', function() {
    totalAsientos = parseInt(this.value) || 0; // Obtener el número total de asientos
    asientosDisponibles = totalAsientos; // Inicializar asientos disponibles
    document.getElementById("asientosDisponibles").value = asientosDisponibles; // Actualizar el campo de asientos disponibles
});

// Función que se llama cuando el escaneo es exitoso
function escanerCorrecto(codigoText, codigoResult) {
    if (scanning) return; // Evitar escanear mientras ya se está procesando uno

    scanning = true; // Marcar que se está procesando un escaneo

    console.log(`Código coincidente = ${codigoText}`, codigoResult);

    // Registrar la asistencia en la base de datos
    registrarAsistencia(codigoText);
}

// Función para actualizar asientos disponibles
function actualizarAsientosDisponibles() {
    if (asientosDisponibles > 0) {
        asientosDisponibles--; // Disminuir asientos disponibles
        document.getElementById("asientosDisponibles").value = asientosDisponibles; // Actualizar el campo de asientos disponibles
    } else {
        mostrarAlerta('Sin Asientos Disponibles', 'No hay más asientos disponibles en el autobús.', 'error');
    }
}

// Función que se llama cuando ocurre un error en el escaneo
function escanerIncorrecto(error) {
    console.warn(`Error de escaneo = ${error}`);
    scanning = false; // Asegurarse de que se marque que el escaneo ha terminado
}

// Función para mostrar alertas usando SweetAlert
function mostrarAlerta(titulo, mensaje, tipo) {
    Swal.fire({
        position: "top-center",
        icon: tipo,
        title: titulo,
        text: mensaje,
        showConfirmButton: tipo === 'error'
    });
}

// Función para registrar la asistencia en el servidor
function registrarAsistencia(codigoQR) {
    fetch('./qr_recursos/registrar_asistencia.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ codigo: codigoQR, escaneador_id: escaneadorId }) // Usar el ID del escaneador desde PHP
    })
    .then(response => response.json())
    .then(data => {
        if (data.exito) {
            // Si la respuesta es exitosa, reproducir sonido de éxito
            successSound.play();
            mostrarAlerta(`Asistencia registrada: ${codigoQR}`, '', 'success');
            console.log('Asistencia registrada exitosamente');

            // Actualizar el número de asientos disponibles
            actualizarAsientosDisponibles();
        } else {
            // Reproducir sonido de error solo si el error se debe a un código no válido
            errorSound.play();
            mostrarAlerta('Error al registrar la asistencia', data.mensaje, 'error');
            console.error('Error al registrar la asistencia:', data.mensaje);
        }
    })
    .catch(error => {
        // Reproducir sonido de error solo si hay un error en la solicitud
        errorSound.play();
        mostrarAlerta('Error en la solicitud', 'No se pudo conectar al servidor.', 'error');
        console.error('Error:', error);
    })
    .finally(() => {
        // Asegurarse de que el escaneo pueda reanudarse después de procesar la solicitud
        setTimeout(() => {
            scanning = false; // Marcar que se ha completado el proceso de escaneo
        }, 6000); // Tiempo en milisegundos
    });
}

// Inicializar el escáner QR
const html5QrcodeScanner = new Html5QrcodeScanner(
    "reader",
    {
        fps: 10,
        qrbox: { width: 250, height: 250 }
    },
    true
);

// Configurar el escáner para usar las funciones de éxito y error
html5QrcodeScanner.render(escanerCorrecto, escanerIncorrecto);
