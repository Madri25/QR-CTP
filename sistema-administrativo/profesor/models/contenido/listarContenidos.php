<?php
require '../../../includes/conexion.php';

// Variables para la paginación y la búsqueda
$limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
$offset = isset($_POST['start']) ? intval($_POST['start']) : 0;
$search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

// Total de registros sin filtrar
$sqlTotal = "SELECT COUNT(*) as total FROM contenidos";
$queryTotal = $pdo->prepare($sqlTotal);
$queryTotal->execute();
$totalRecords = $queryTotal->fetch()['total'];

// Total de registros filtrados
$sqlFiltered = "SELECT COUNT(*) as filtered FROM contenidos WHERE titulo LIKE :search OR descripcion LIKE :search";
$queryFiltered = $pdo->prepare($sqlFiltered);
$queryFiltered->execute([':search' => "%$search%"]);
$filteredRecords = $queryFiltered->fetch()['filtered'];

// Obtener los datos para la tabla
$sqlData = "SELECT contenido_id, titulo, descripcion, material FROM contenidos WHERE titulo LIKE :search OR descripcion LIKE :search LIMIT :offset, :limit";
$queryData = $pdo->prepare($sqlData);
$queryData->bindValue(':search', "%$search%");
$queryData->bindValue(':offset', $offset, PDO::PARAM_INT);
$queryData->bindValue(':limit', $limit, PDO::PARAM_INT);
$queryData->execute();
$data = $queryData->fetchAll(PDO::FETCH_ASSOC);

// Preparar la respuesta JSON
$response = array(
    "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 1,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $filteredRecords,
    "data" => $data
);

echo json_encode($response);
?>
