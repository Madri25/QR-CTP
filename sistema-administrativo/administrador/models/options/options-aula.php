<?php

require_once '../../../includes/conexion.php';

// Inicializa una variable para el nombre del aula, si se pasa como parámetro
$nombre_aula = isset($_GET['nombre_aula']) ? trim($_GET['nombre_aula']) : '';

// Prepara la consulta SQL
$sql = "SELECT aula_id, nombre_aula FROM aulas WHERE estado = 1";

// Si se proporciona un nombre de aula, agrega un filtro
if (!empty($nombre_aula)) {
    $sql .= " AND nombre_aula LIKE :nombre_aula";
}

$query = $pdo->prepare($sql);

// Si se proporcionó un nombre de aula, enlaza el parámetro
if (!empty($nombre_aula)) {
    $query->bindValue(':nombre_aula', "%$nombre_aula%", PDO::PARAM_STR);
}

$query->execute();
$data = $query->fetchAll(PDO::FETCH_ASSOC);

// Envía la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE);
