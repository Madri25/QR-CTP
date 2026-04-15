<?php
// Incluye el archivo de conexión PDO
include '../includes/conexion.php';

// Verifica si se ha recibido un ID de usuario
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    
    // Obtener la información del usuario
    $query = "SELECT * FROM usuarios_escaner WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $userId]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        die("Usuario no encontrado.");
    }
} else {
    die("ID de usuario no proporcionado.");
}

// Función para manejar la subida de la foto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $file = $_FILES['foto'];
    $targetDir = "../uploads/"; // Directorio para subir fotos
    $targetFile = $targetDir . basename($file["name"]);
    
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        // Actualizar la ruta de la foto en la base de datos
        $updateQuery = "UPDATE usuarios_escaner SET foto = :foto WHERE id = :id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([
            'foto' => $targetFile,
            'id' => $userId
        ]);
        header("Location: ../path/to/your/page.php"); // Redirigir después de la subida
        exit();
    } else {
        echo "Error al subir la foto.";
    }
}
?>

<!-- HTML para el modal -->
<div id="fotoModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Actualizar Foto de Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" value="<?php echo htmlspecialchars($usuario['Nombre'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="foto">Seleccionar nueva foto</label>
                        <input type="file" class="form-control-file" id="foto" name="foto">
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar Foto</button>
                </form>
                <?php if (!empty($usuario['foto'])): ?>
                    <img src="<?php echo htmlspecialchars($usuario['foto'], ENT_QUOTES, 'UTF-8'); ?>" alt="Foto Actual" style="width: 100%; height: auto; margin-top: 10px;">
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript para abrir el modal (si no se abre automáticamente)
    $('#fotoModal').modal('show');
</script>
