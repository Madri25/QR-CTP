<?php

require_once '../../../includes/conexion.php';

// Inicializa una variable para el nombre del período, si se pasa como parámetro
$nombre_periodo = isset($_GET['nombre_periodo']) ? trim($_GET['nombre_periodo']) : '';

// Prepara la consulta SQL
$sql = "SELECT periodo_id, nombre_periodo FROM periodos WHERE estado = 1";

// Si se proporciona un nombre de período, agrega un filtro
if (!empty($nombre_periodo)) {
    $sql .= " AND nombre_periodo LIKE :nombre_periodo";
}

$query = $pdo->prepare($sql);

// Si se proporcionó un nombre de período, enlaza el parámetro
if (!empty($nombre_periodo)) {
    $query->bindValue(':nombre_periodo', "%$nombre_periodo%", PDO::PARAM_STR);
}

$query->execute();
$data = $query->fetchAll(PDO::FETCH_ASSOC);

// Envía la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE);
