<?php

require_once '../../../includes/conexion.php';

// Inicializa una variable para el nombre del profesor, si se pasa como parámetro
$nombre_profesor = isset($_GET['nombre_profesor']) ? trim($_GET['nombre_profesor']) : '';

// Prepara la consulta SQL
$sql = "SELECT profesor_id, nombre FROM profesor WHERE estado = 1";

// Si se proporciona un nombre de profesor, agrega un filtro
if (!empty($nombre_profesor)) {
    $sql .= " AND nombre LIKE :nombre_profesor";
}

$query = $pdo->prepare($sql);

// Si se proporcionó un nombre de profesor, enlaza el parámetro
if (!empty($nombre_profesor)) {
    $query->bindValue(':nombre_profesor', "%$nombre_profesor%", PDO::PARAM_STR);
}

$query->execute();
$data = $query->fetchAll(PDO::FETCH_ASSOC);

// Envía la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE);
