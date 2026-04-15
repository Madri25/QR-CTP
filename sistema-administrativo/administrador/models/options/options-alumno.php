<?php

require_once '../../../includes/conexion.php';

// Inicializa una variable para el nombre del alumno, si se pasa como parámetro
$nombre_alumno = isset($_GET['nombre_alumno']) ? trim($_GET['nombre_alumno']) : '';

// Prepara la consulta SQL
$sql = "SELECT alumno_id, nombre_alumno FROM alumnos WHERE estado = 1";

// Si se proporciona un nombre de alumno, agrega un filtro
if (!empty($nombre_alumno)) {
    $sql .= " AND nombre_alumno LIKE :nombre_alumno";
}

$query = $pdo->prepare($sql);

// Si se proporcionó un nombre de alumno, enlaza el parámetro
if (!empty($nombre_alumno)) {
    $query->bindValue(':nombre_alumno', "%$nombre_alumno%", PDO::PARAM_STR);
}

$query->execute();
$data = $query->fetchAll(PDO::FETCH_ASSOC);

// Envía la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE);
