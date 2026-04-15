<?php
require_once '../../../includes/conexion.php';

$response = ['status' => false, 'msg' => ''];

try {
    $alumno_id = $_POST['listAlumno'];
    $pm_id = $_POST['listProfesor'];
    $periodo_id = $_POST['listPeriodo'];
    $estadop = $_POST['listEstado'];

    $query = "INSERT INTO alumno_profesor (alumno_id, pm_id, periodo_id, estadop) VALUES (:alumno_id, :pm_id, :periodo_id, :estadop)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':alumno_id' => $alumno_id,
        ':pm_id' => $pm_id,
        ':periodo_id' => $periodo_id,
        ':estadop' => $estadop
    ]);

    $response['status'] = true;
    $response['msg'] = 'Registro agregado correctamente.';
} catch (PDOException $e) {
    $response['msg'] = 'Error al agregar el registro: ' . $e->getMessage();
}

echo json_encode($response);
?>
