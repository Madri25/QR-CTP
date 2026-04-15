<?php
// registrar_asistencia.php

header('Content-Type: application/json');

// Obtener el código QR y el ID del escaneador desde la solicitud
$data = json_decode(file_get_contents('php://input'));
if (!isset($data->codigo) || empty($data->codigo) || !isset($data->escaneador_id) || empty($data->escaneador_id)) {
    echo json_encode(['exito' => false, 'mensaje' => 'Código QR o ID del escaneador no proporcionado']);
    exit;
}

$codigo = trim($data->codigo);
$escaneador_id = trim($data->escaneador_id);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistema-escolar"; // Asegúrate de que el nombre de la base de datos sea correcto

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(['exito' => false, 'mensaje' => 'Conexión fallida: ' . $conn->connect_error]);
    exit;
}

// Obtener la zona del usuario escaneador
$sqlEscaneador = "SELECT parada_o_sector FROM usuarios_escaner WHERE id = ?";
$stmtEscaneador = $conn->prepare($sqlEscaneador);
if ($stmtEscaneador === false) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error en la preparación de la consulta: ' . $conn->error]);
    $conn->close();
    exit;
}
$stmtEscaneador->bind_param("i", $escaneador_id);
$stmtEscaneador->execute();
$stmtEscaneador->bind_result($escaneador_parada_o_sector);
$stmtEscaneador->fetch();
$stmtEscaneador->close();

if (!$escaneador_parada_o_sector) {
    echo json_encode(['exito' => false, 'mensaje' => 'Usuario escaneador no encontrado']);
    $conn->close();
    exit;
}

// Verificar si el código QR existe en la tabla de estudiantes
$sqlEstudiante = "SELECT id, nombre, seccion, parada_o_sector FROM estudiantes WHERE codigo = ?";
$stmtEstudiante = $conn->prepare($sqlEstudiante);
if ($stmtEstudiante === false) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error en la preparación de la consulta: ' . $conn->error]);
    $conn->close();
    exit;
}
$stmtEstudiante->bind_param("s", $codigo);
$stmtEstudiante->execute();
$stmtEstudiante->bind_result($estudiante_id, $nombre, $seccion, $estudiante_parada_o_sector);
$stmtEstudiante->fetch();
$stmtEstudiante->close();

if (!$estudiante_id) {
    echo json_encode(['exito' => false, 'mensaje' => 'Estudiante no encontrado']);
    $conn->close();
    exit;
}

// Verificar si el escaneador y el estudiante están en la misma zona
if ($escaneador_parada_o_sector !== $estudiante_parada_o_sector) {
    echo json_encode(['exito' => false, 'mensaje' => 'No tiene permiso para registrar la asistencia para este estudiante']);
    $conn->close();
    exit;
}

// Verificar si ya se registró asistencia para este estudiante hoy
$sqlCheck = "SELECT COUNT(*) AS count FROM asistencia WHERE estudiante_id = ? AND DATE(fecha_hora) = CURDATE()";
$stmtCheck = $conn->prepare($sqlCheck);
if ($stmtCheck === false) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error en la preparación de la consulta: ' . $conn->error]);
    $conn->close();
    exit;
}
$stmtCheck->bind_param("i", $estudiante_id);
$stmtCheck->execute();
$stmtCheck->bind_result($count);
$stmtCheck->fetch();
$stmtCheck->close();

if ($count > 0) {
    echo json_encode(['exito' => false, 'mensaje' => 'Asistencia ya registrada para hoy']);
} else {
    // Registrar la nueva asistencia
    $sqlInsert = "INSERT INTO asistencia (estudiante_id, fecha_hora) VALUES (?, NOW())";
    $stmtInsert = $conn->prepare($sqlInsert);
    if ($stmtInsert === false) {
        echo json_encode(['exito' => false, 'mensaje' => 'Error en la preparación de la consulta: ' . $conn->error]);
        $conn->close();
        exit;
    }
    $stmtInsert->bind_param("i", $estudiante_id);

    if ($stmtInsert->execute()) {
        echo json_encode(['exito' => true, 'mensaje' => 'Asistencia registrada correctamente']);
    } else {
        echo json_encode(['exito' => false, 'mensaje' => 'Error en la consulta: ' . $stmtInsert->error]);
    }

    $stmtInsert->close();
}

$conn->close();
?>
