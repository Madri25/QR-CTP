<?php
require '../../../includes/conexion.php';

if (!empty($_GET)) {
    $idcontenido = $_GET['idcontenido'];

    $sql = "SELECT * FROM contenidos WHERE contenido_id = ?";
    $query = $pdo->prepare($sql);
    $query->execute([$idcontenido]);
    $result = $query->fetch();

    if (empty($result)) {
        $respuesta = array('status' => false, 'msg' => 'Datos no encontrados');
    } else {
        $respuesta = array('status' => true, 'data' => $result);
    }

    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
}
?>
