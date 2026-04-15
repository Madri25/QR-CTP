<?php
include '../includes/conexion.php';

$id = $_POST['id'] ?? null;
$foto = $_FILES['foto'] ?? null;

if (!$id || !$foto || $foto['error'] != UPLOAD_ERR_OK) {
    exit('Error al subir la foto.');
}

// Definir el directorio para guardar la foto
$uploadDir = '../uploads/';
$uploadFile = $uploadDir . basename($foto['name']);

// Mover el archivo al directorio de uploads
if (!move_uploaded_file($foto['tmp_name'], $uploadFile)) {
    exit('Error al mover la foto.');
}

// Actualizar la ruta de la foto en la base de datos
$query = "UPDATE usuarios_escaner SET foto = :foto WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':foto', $uploadFile, PDO::PARAM_STR);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

if ($stmt->execute()) {
    header('Location: ../path_to_your_page.php'); // Redirige a la página después de subir la foto
    exit();
} else {
    exit('Error al actualizar la foto en la base de datos.');
}
?>
