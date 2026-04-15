<?php
require_once '../../../includes/conexion.php';;
$response = ['status' => false, 'msg' => ''];

try {
    $idalumnoprofesor = $_POST['idalumnoprofesor'];
    $alumno_id = $_POST['listAlumno'];
    $pm_id = $_POST['listProfesor'];
    $periodo_id = $_POST['listPeriodo'];
    $estadop = $_POST['listEstado'];

    $query = "UPDATE alumno_profesor SET alumno_id = :alumno_id, pm_id = :pm_id, periodo_id = :periodo_id, estadop = :estadop WHERE ap_id = :idalumnoprofesor";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':alumno_id' => $alumno_id,
        ':pm_id' => $pm_id,
        ':periodo_id' => $periodo_id,
        ':estadop' => $estadop,
        ':idalumnoprofesor' => $idalumnoprofesor
    ]);

    $response['status'] = true;
    $response['msg'] = 'Registro actualizado correctamente.';
} catch (PDOException $e) {
    $response['msg'] = 'Error al actualizar el registro: ' . $e->getMessage();
}

echo json_encode($response);
?>
