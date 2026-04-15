function openEditModal(nombre, correo, parada_o_sector, estado, foto) {
    // Establecer los valores en los campos del formulario
    document.getElementById('editUserName').value = nombre;
    document.getElementById('editUserEmail').value = correo;
    document.getElementById('editUserCorreoHidden').value = correo; // Campo oculto para el correo
    document.getElementById('editUserSector').value = parada_o_sector;
    document.getElementById('editUserPassword').value = ''; // Puedes dejar la contraseña vacía
    document.getElementById('editUserStatus').value = estado;

    // Si hay foto, mostrarla en el modal
    if (foto) {
        document.getElementById('currentUserFoto').src = foto;
        document.getElementById('currentUserFoto').style.display = 'block';
    } else {
        document.getElementById('currentUserFoto').style.display = 'none';
    }

    // Mostrar el modal
    $('#editUserModal').modal('show');
}