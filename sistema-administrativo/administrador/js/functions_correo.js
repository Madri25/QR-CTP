
function openFotoModal(id) {
    $('#foto-id').val(id);
    $('#fotoModal').modal('show');
}

function openEditModal(id) {
    // Llama a un archivo PHP que devuelva los datos del usuario en formato JSON
    $.ajax({
        url: './models/correo/get_user_data.php', // Asegúrate de tener este archivo para obtener datos del usuario
        method: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function(data) {
            $('#edit-id').val(data.id);
            $('#edit-nombre').val(data.nombre);
            $('#edit-correo').val(data.correo);
            $('#editModal').modal('show');
        }
    });
}

function confirmDelete(id) {
    $('#delete-id').val(id);
    $('#deleteModal').modal('show');
}

