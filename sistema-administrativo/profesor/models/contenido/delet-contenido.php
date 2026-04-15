<?php
require_once '../../../includes/conexion.php';

if ($_POST) {
    $idcontenido = $_POST['idcontenido'];

    $sql = "SELECT * FROM contenidos WHERE contenido_id = ?";
    $query = $pdo->prepare($sql);
    $query->execute([$idcontenido]);
    $data = $query->fetch();

    $sqlEvaluaciones = "SELECT * FROM evaluaciones WHERE contenido_id = ?";
    $queryEvaluaciones = $pdo->prepare($sqlEvaluaciones);
    $queryEvaluaciones->execute([$idcontenido]);
    $dataEvaluaciones = $queryEvaluaciones->fetch();

    if (empty($dataEvaluaciones)) {
        $sqlDelete = "DELETE FROM contenidos WHERE contenido_id = ?";
        $queryDelete = $pdo->prepare($sqlDelete);
        $result = $queryDelete->execute([$idcontenido]);

        if ($result) {
            if (!empty($data['material'])) {
                unlink($data['material']);
            }
            $arrResponse = array('status' => true, 'msg' => 'Eliminado Correctamente');
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Error al eliminar');
        }
    } else {
        $arrResponse = array('status' => false, 'msg' => 'No se puede eliminar, ya tiene una evaluación asignada');
    }

    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
}
?>
