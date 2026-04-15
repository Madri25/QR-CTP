<?php
include '../includes/conexion.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    exit('No se especificó un ID de usuario.');
}

// Eliminar el usuario de la base de datos
$query = "DELETE FROM usuarios_escaner WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

if ($stmt->execute()) {
    header('Location: ../path_to_your_page.php'); // Redirige a la página después de eliminar
    exit();
} else {
    exit('Error al eliminar el usuario de la base de datos.');
}
?>
