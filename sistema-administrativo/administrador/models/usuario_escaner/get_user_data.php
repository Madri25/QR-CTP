<?php
include '../../includes/conexion.php'; // Incluye el archivo de conexión PDO

// Verifica si se ha enviado el ID del usuario
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepara y ejecuta la consulta para obtener los datos del usuario
    $query = "SELECT * FROM usuarios_escaner WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Obtén los datos del usuario
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica si se encontró el usuario
    if ($usuario) {
        // Envía los datos del usuario en formato JSON
        echo json_encode($usuario);
    } else {
        // Envía un error si no se encontró el usuario
        echo json_encode(['error' => 'Usuario no encontrado']);
    }
} else {
    // Envía un error si no se ha proporcionado un ID
    echo json_encode(['error' => 'ID de usuario no proporcionado']);
}
?>
