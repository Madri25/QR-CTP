<?php
include '../includes/conexion.php';

$id = $_POST['id'] ?? null;
$nombre = $_POST['nombre'] ?? null;
$correo = $_POST['correo'] ?? null;

if (!$id || !$nombre || !$correo) {
    exit('Datos inválidos.');
}

// Actualizar la información del usuario en la base de datos
$query = "UPDATE usuarios_escaner SET Nombre = :nombre, Correo = :correo WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
$stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

if ($stmt->execute()) {
    header('Location: ../path_to_your_page.php'); // Redirige a la página después de editar
    exit();
} else {
    exit('Error al actualizar el usuario en la base de datos.');
}
?>
