<?php
include '../../../../Login/login_recursos/conexion_BD.php';

// Obtener el correo del usuario a eliminar
$correo = isset($_GET['correo']) ? $_GET['correo'] : '';

if (empty($correo)) {
    die("Correo electrónico no proporcionado.");
}

// Preparar la consulta para eliminar al usuario
$sql = "DELETE FROM usuarios_escaner WHERE Correo = ?";
$stmt = $conexion_DB->prepare($sql);
if (!$stmt) {
    die("Error al preparar la consulta: " . $conexion_DB->error);
}
$stmt->bind_param('s', $correo);

if (!$stmt->execute()) {
    die("Error al eliminar el usuario: " . $stmt->error);
}

// Cerrar la conexión y redirigir a la página de administración
$stmt->close();
$conexion_DB->close();
header('Location: ../../lista_correo.php'); // Ajusta la ruta si es necesario
exit();
?>
