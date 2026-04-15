document.addEventListener('DOMContentLoaded', function() {
    // Obtener los elementos del formulario
    const form = document.querySelector('form');
    const codigoInput = document.getElementById('codigo');
    const nombreInput = document.getElementById('nombre');
    const seccionInput = document.getElementById('seccion');
    const paradaInput = document.getElementById('parada_o_sector');
    const fotoInput = document.getElementById('foto');
    const previewImage = document.createElement('img'); // Crear una imagen para previsualizar

    // Establecer algunas propiedades para la imagen de previsualización
    previewImage.style.maxWidth = '150px';
    previewImage.style.marginTop = '10px';
    fotoInput.parentElement.appendChild(previewImage); // Agregar la imagen debajo del input de foto

    // Función para validar que los campos no estén vacíos
    function validateForm() {
        let isValid = true;

        // Verificar que todos los campos tengan valores
        if (codigoInput.value.trim() === "") {
            alert('El campo código es obligatorio.');
            isValid = false;
        } else if (nombreInput.value.trim() === "") {
            alert('El campo nombre es obligatorio.');
            isValid = false;
        } else if (seccionInput.value.trim() === "") {
            alert('El campo sección es obligatorio.');
            isValid = false;
        } else if (paradaInput.value.trim() === "") {
            alert('El campo parada o sector es obligatorio.');
            isValid = false;
        } else if (!fotoInput.files.length) {
            alert('Debe seleccionar una foto.');
            isValid = false;
        }

        return isValid;
    }

    // Evento para validar el formulario antes de enviarlo
    form.addEventListener('submit', function(event) {
        if (!validateForm()) {
            event.preventDefault(); // Evitar el envío si el formulario no es válido
        }
    });

    // Evento para previsualizar la imagen seleccionada
    fotoInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result; // Establecer la imagen de previsualización
            };
            reader.readAsDataURL(file); // Leer el archivo seleccionado
        }
    });
});
