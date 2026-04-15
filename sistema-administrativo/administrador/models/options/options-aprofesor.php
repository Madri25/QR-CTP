<?php

require_once '../../../includes/conexion.php';

// Inicializa las variables para los parámetros de filtrado
$profesor_nombre = isset($_GET['profesor_nombre']) ? trim($_GET['profesor_nombre']) : '';
$materia_nombre = isset($_GET['materia_nombre']) ? trim($_GET['materia_nombre']) : '';

// Prepara la consulta SQL
$sql = 'SELECT * FROM profesor_materia AS pm 
INNER JOIN profesor AS p ON pm.profesor_id = p.profesor_id 
INNER JOIN grados AS g ON pm.grado_id = g.grado_id 
INNER JOIN aulas AS a ON pm.aula_id = a.aula_id 
INNER JOIN materias AS m ON pm.materia_id = m.materia_id 
INNER JOIN periodos AS pe ON pm.periodo_id = pe.periodo_id 
WHERE pm.estadopm = 1';

// Añade condiciones de filtrado si se proporcionan
$conditions = [];
if (!empty($profesor_nombre)) {
    $conditions[] = 'p.nombre_profesor LIKE :profesor_nombre';
}
if (!empty($materia_nombre)) {
    $conditions[] = 'm.nombre_materia LIKE :materia_nombre';
}

// Si hay condiciones, se agregan a la consulta
if (count($conditions) > 0) {
    $sql .= ' AND ' . implode(' AND ', $conditions);
}

$query = $pdo->prepare($sql);

// Enlaza los parámetros de filtrado si es necesario
if (!empty($profesor_nombre)) {
    $query->bindValue(':profesor_nombre', "%$profesor_nombre%", PDO::PARAM_STR);
}
if (!empty($materia_nombre)) {
    $query->bindValue(':materia_nombre', "%$materia_nombre%", PDO::PARAM_STR);
}

$query->execute();
$data = $query->fetchAll(PDO::FETCH_ASSOC);

// Envía la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE);
