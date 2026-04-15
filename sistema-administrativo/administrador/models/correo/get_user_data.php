<?php
include '../../../../Login/login_recursos/conexion_BD.php';

// Verificar si se ha proporcionado un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['error' => 'ID del usuario no proporcionado.']);
    exit;
}

// Sanitizar el ID del usuario
$id = intval($_GET['id']);

// Consulta para obtener los datos del usuario
$query = "SELECT Nombre, Correo, parada_o_sector, aprobado, foto FROM usuarios_escaner WHERE id = ?";
$stmt = $conexion_DB->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró el usuario
if ($result->num_rows === 0) {
    echo json_encode(['error' => 'Usuario no encontrado.']);
    exit;
}

// Obtener los datos del usuario
$usuario = $result->fetch_assoc();

// Devolver los datos del usuario en formato JSON
echo json_encode($usuario);

// Cerrar la conexión
$stmt->close();
$conexion_DB->close();
?>
