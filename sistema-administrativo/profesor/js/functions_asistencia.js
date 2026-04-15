document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector('form');
    const seccionSelect = document.getElementById('seccion');
    const fechaInput = document.getElementById('fecha');
    const tableBody = document.querySelector('table tbody');
    
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevenir la recarga de la página

        // Obtener valores del formulario
        const seccion = seccionSelect.value;
        const fecha = fechaInput.value;

        // Validación rápida
        if (!seccion || !fecha) {
            Swal.fire({
                icon: 'error',
                title: 'Campos incompletos',
                text: 'Por favor, complete todos los campos antes de continuar.'
            });
            return;
        }

        // Enviar datos mediante AJAX
        fetch('./models/asistencia/asistencia_ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                seccion: seccion,
                fecha: fecha
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                // Si hubo un error, mostrar alerta con SweetAlert2
                Swal.fire({
                    icon: 'error',
                    title: 'Error al cargar la asistencia',
                    text: data.error
                });
            } else if (data.length === 0) {
                // Si no hay datos de asistencia
                Swal.fire({
                    icon: 'info',
                    title: 'Sin asistencia',
                    text: 'No se encontró asistencia para la sección y fecha seleccionada.'
                });
                // Limpiar tabla
                tableBody.innerHTML = '<tr><td colspan="3">No hay asistencia registrada.</td></tr>';
            } else {
                // Limpiar tabla y agregar nuevas filas
                tableBody.innerHTML = '';
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.nombre_estudiante}</td>
                        <td>${item.cedula}</td>
                        <td>${item.fecha_hora}</td>
                    `;
                    tableBody.appendChild(row);
                });
                Swal.fire({
                    icon: 'success',
                    title: 'Asistencia cargada',
                    text: 'La asistencia se ha cargado exitosamente.'
                });
            }
        })
        .catch(error => {
            // Manejo de errores de la red o del servidor
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'Hubo un problema al intentar conectar con el servidor. Por favor, inténtelo de nuevo más tarde.'
            });
        });
    });
});
