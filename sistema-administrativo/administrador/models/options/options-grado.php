<?php

require_once '../../../includes/conexion.php';

// Inicializa una variable para el nombre del grado, si se pasa como parámetro
$nombre_grado = isset($_GET['nombre_grado']) ? trim($_GET['nombre_grado']) : '';

// Prepara la consulta SQL
$sql = "SELECT grado_id, nombre_grado FROM grados WHERE estado = 1";

// Si se proporciona un nombre de grado, agrega un filtro
if (!empty($nombre_grado)) {
    $sql .= " AND nombre_grado LIKE :nombre_grado";
}

$query = $pdo->prepare($sql);

// Si se proporcionó un nombre de grado, enlaza el parámetro
if (!empty($nombre_grado)) {
    $query->bindValue(':nombre_grado', "%$nombre_grado%", PDO::PARAM_STR);
}

$query->execute();
$data = $query->fetchAll(PDO::FETCH_ASSOC);

// Envía la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE);
