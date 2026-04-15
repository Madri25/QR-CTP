<!-- modals/subir_foto.php -->
<?php
include '../includes/conexion.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    exit('No se especificó un ID de usuario.');
}

// Obtener la información del usuario
$query = "SELECT * FROM usuarios_escaner WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch();

if (!$usuario) {
    exit('Usuario no encontrado.');
}
?>

<div class="modal fade" id="fotoModal" tabindex="-1" role="dialog" aria-labelledby="fotoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fotoModalLabel">Subir Foto para <?php echo htmlspecialchars($usuario['Nombre'], ENT_QUOTES, 'UTF-8'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="subir_foto_action.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="form-group">
                        <label for="foto">Selecciona una foto:</label>
                        <input type="file" class="form-control" name="foto" id="foto" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Subir Foto</button>
                </form>
            </div>
        </div>
    </div>
</div>
