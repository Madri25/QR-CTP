<?php

require_once '../../../includes/conexion.php';

// Inicializa una variable para el nombre de la materia, si se pasa como parámetro
$nombre_materia = isset($_GET['nombre_materia']) ? trim($_GET['nombre_materia']) : '';

// Prepara la consulta SQL
$sql = "SELECT materia_id, nombre_materia FROM materias WHERE estado = 1";

// Si se proporciona un nombre de materia, agrega un filtro
if (!empty($nombre_materia)) {
    $sql .= " AND nombre_materia LIKE :nombre_materia";
}

$query = $pdo->prepare($sql);

// Si se proporcionó un nombre de materia, enlaza el parámetro
if (!empty($nombre_materia)) {
    $query->bindValue(':nombre_materia', "%$nombre_materia%", PDO::PARAM_STR);
}

$query->execute();
$data = $query->fetchAll(PDO::FETCH_ASSOC);

// Envía la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE);
