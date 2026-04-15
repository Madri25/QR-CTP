<?php
require_once '../../../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['idcontenido']) && isset($_POST['titulo']) && isset($_POST['descripcion']) && isset($_POST['pm_id'])) {

        $idcontenido = intval($_POST['idcontenido']);
        $titulo = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);
        $pm_id = intval($_POST['pm_id']);

        // Manejo del archivo (si existe)
        $material = '';
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileName = basename($_FILES['file']['name']);
            $fileTmpPath = $_FILES['file']['tmp_name'];
            $fileSize = $_FILES['file']['size'];
            $fileType = $_FILES['file']['type'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
            if (!in_array($fileExtension, $allowedTypes)) {
                $arrResponse = array('status' => false, 'msg' => 'Tipo de archivo no permitido');
                echo json_encode($arrResponse);
                exit;
            } elseif ($fileSize > 5242880) { // 5 MB
                $arrResponse = array('status' => false, 'msg' => 'El archivo es demasiado grande (máximo 5MB)');
                echo json_encode($arrResponse);
                exit;
            } else {
                $uniqueFileName = uniqid() . '.' . $fileExtension;
                $uploadDir = '../../uploads/contenidos/';
                $rutaDestino = $uploadDir . $uniqueFileName;

                if (!move_uploaded_file($fileTmpPath, $rutaDestino)) {
                    $arrResponse = array('status' => false, 'msg' => 'Error al subir el archivo');
                    echo json_encode($arrResponse);
                    exit;
                }

                $material = $rutaDestino;
            }
        }

        // Si no se envía un archivo nuevo, se debe conservar el anterior
        $sql = "SELECT material FROM contenidos WHERE contenido_id = ?";
        $query = $pdo->prepare($sql);
        $query->execute([$idcontenido]);
        $result = $query->fetch();

        if (!$material && $result) {
            $material = $result['material']; // Mantener el archivo anterior
        }

        // SQL para actualizar los datos
        $sql = "UPDATE contenidos SET titulo = ?, descripcion = ?, material = ?, pm_id = ? WHERE contenido_id = ?";
        $params = array($titulo, $descripcion, $material, $pm_id, $idcontenido);

        try {
            $query = $pdo->prepare($sql);
            $result = $query->execute($params);

            if ($result) {
                $arrResponse = array('status' => true, 'msg' => 'Contenido actualizado correctamente.');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al actualizar el contenido.');
            }
        } catch (PDOException $e) {
            $arrResponse = array('status' => false, 'msg' => 'Error: ' . $e->getMessage());
        }

        echo json_encode($arrResponse);
    } else {
        $arrResponse = array('status' => false, 'msg' => 'Datos incompletos.');
        echo json_encode($arrResponse);
    }
} else {
    http_response_code(405); // Método no permitido
    $arrResponse = array('status' => false, 'msg' => 'Método no permitido.');
    echo json_encode($arrResponse);
}
?>
