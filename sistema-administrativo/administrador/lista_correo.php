<?php
include '../../Login/login_recursos/conexion_BD.php';
include 'includes/header.php';

// Obtener la lista de usuarios
$query = "SELECT * FROM usuarios_escaner";
$resultado = mysqli_query($conexion_DB, $query);

if (!$resultado) {
    die("Error al obtener los usuarios: " . mysqli_error($conexion_DB));
}
?>
    <style>
        .user-photo {
    width: 160px; /* Tamaño fijo para las imágenes en la tabla */
    height: 160px;
    object-fit: cover; /* Mantiene la proporción y cubre el área sin distorsión */
    /* Opcional: Asegúrate de que las imágenes de entrada sean de alta resolución */
        }

        .modal-user-photo {
            width: 200px; /* Tamaño de la imagen en el modal */
            height: 200px;
            object-fit: cover; /* Mantiene la proporción y cubre el área sin distorsión */
            /* Opcional: Asegúrate de que las imágenes de entrada sean de alta resolución */
        }
    </style>
</head>
<body>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-envelope"></i> Administración de Correos</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="#">Administración de Correos</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="tableUsuarios">
                                <thead>
                                    <tr>
                                        <th>Nombre Completo</th>
                                        <th>Correo</th>
                                        <th>Parada o Sector</th>
                                        <th>Estado</th>
                                        <th>Foto</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($usuario = mysqli_fetch_assoc($resultado)) { 
                                        $id = $usuario['id'];
                                        $nombre = htmlspecialchars($usuario['Nombre'], ENT_QUOTES, 'UTF-8');
                                        $foto_path = $usuario['foto'] ? './models/correo/uploads/' . $nombre . '_' . $id . '/' . basename($usuario['foto']) : '';
                                    ?>
                                    <tr>
                                        <td><?php echo $nombre; ?></td>
                                        <td><?php echo htmlspecialchars($usuario['Correo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['parada_o_sector'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo $usuario['aprobado'] ? 'Aprobado' : 'No aprobado'; ?></td>
                                        <td>
                                            <?php if (!empty($foto_path) && file_exists($foto_path)) { ?>
                                                <img src="<?php echo $foto_path; ?>" alt="Foto de Usuario" class="user-photo">
                                            <?php } else { ?>
                                                No disponible
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if (!$usuario['aprobado']) { ?>
                                                <a href="./models/correo/aprobar_usuario.php?correo=<?php echo urlencode($usuario['Correo']); ?>" class="btn btn-primary btn-sm">Aprobar</a>
                                            <?php } ?>
                                            <button type="button" class="btn btn-primary btn-sm" onclick="openEditModal('<?php echo $nombre; ?>', '<?php echo htmlspecialchars($usuario['Correo'], ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars($usuario['parada_o_sector'], ENT_QUOTES, 'UTF-8'); ?>', '<?php echo $usuario['aprobado'] ? '1' : '0'; ?>', '<?php echo $foto_path; ?>')">Editar</button>
                                            <a href="./models/correo/eliminar_usuario.php?correo=<?php echo urlencode($usuario['Correo']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?');">Eliminar</a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Editar Usuario -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Actualizar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editUserForm" method="POST" action="./models/correo/editar_usuario.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="editUserCorreoHidden" name="correo">
                        <div class="form-group">
                            <label for="editUserName">Nombre:</label>
                            <input type="text" class="form-control" id="editUserName" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="editUserEmail">Correo:</label>
                            <input type="email" class="form-control" id="editUserEmail" name="correo" required>
                        </div>
                        <div class="form-group">
                            <label for="editUserPassword">Contraseña:</label>
                            <input type="password" class="form-control" id="editUserPassword" name="password" placeholder="Dejar en blanco para no cambiar">
                        </div>
                        <div class="form-group">
                            <label for="editUserSector">Parada o Sector:</label>
                            <input type="text" class="form-control" id="editUserSector" name="parada_o_sector" required>
                        </div>
                        <div class="form-group">
                            <label for="editUserStatus">Estado:</label>
                            <select class="form-control" id="editUserStatus" name="estado">
                                <option value="1">Aprobado</option>
                                <option value="0">No aprobado</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editUserFoto">Foto:</label>
                            <input type="file" class="form-control-file" id="editUserFoto" name="foto">
                            <img id="currentUserFoto" src="" alt="Foto de Usuario" class="modal-user-photo" style="display: none;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
<?php
mysqli_close($conexion_DB); // Cierra la conexión a la base de datos
?>
