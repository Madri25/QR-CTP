<?php
session_start();

// Verificar si la sesión está activa
if (empty($_SESSION['active'])) {
    header('Location: ../../');
    exit();
}

include '../includes/conexion.php'; // Incluye el archivo de conexión PDO

// Obtener el correo del usuario a aprobar
$correo = $_GET['correo'] ?? '';

if ($correo) {
    // Actualizar el estado del usuario
    $query = "UPDATE usuarios_escaner SET aprobado = 1 WHERE Correo = :correo";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':correo', $correo);

    if ($stmt->execute()) {
        header('Location: ../admin_users.php');
        exit();
    } else {
        die("Error al aprobar el usuario.");
    }
}

?>
