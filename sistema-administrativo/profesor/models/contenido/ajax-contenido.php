<?php
require_once '../../../includes/conexion.php';

if (!empty($_POST)) {
    if (empty($_POST['titulo']) || empty($_POST['descripcion'])) {
        $respuesta = array('status' => false, 'msg' => 'Todos los campos son necesarios');
    } else {
        $idcontenido = intval($_POST['idcontenido']);
        $idcurso = intval($_POST['idcurso']);
        $titulo = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);

        // Manejo del archivo
        $material = '';
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileName = basename($_FILES['file']['name']);
            $fileTmpPath = $_FILES['file']['tmp_name'];
            $fileSize = $_FILES['file']['size'];
            $fileType = $_FILES['file']['type'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            
            // Validar el tipo de archivo y tamaño
            $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
            if (!in_array($fileExtension, $allowedTypes)) {
                $respuesta = array('status' => false, 'msg' => 'Tipo de archivo no permitido');
            } elseif ($fileSize > 15000000) { // 15 MB
                $respuesta = array('status' => false, 'msg' => 'El archivo excede el tamaño permitido de 15MB');
            } else {
                // Generar un nombre único para el archivo y moverlo
                $uniqueFileName = uniqid() . '.' . $fileExtension;
                $uploadDir = '../../../uploads/contenido/';
                $destino = $uploadDir . $uniqueFileName;
                
                if (move_uploaded_file($fileTmpPath, $destino)) {
                    $material = $destino;
                } else {
                    $respuesta = array('status' => false, 'msg' => 'Error al subir el archivo');
                }
            }
        }

        // Insertar o actualizar en la base de datos
        if ($idcontenido == 0) {
            // Inserción
            $sqlInsert = 'INSERT INTO contenidos (titulo, descripcion, material, pm_id) VALUES (?, ?, ?, ?)';
            $queryInsert = $pdo->prepare($sqlInsert);
            $request = $queryInsert->execute([$titulo, $descripcion, $material, $idcurso]);
            
            if ($request) {
                $respuesta = array('status' => true, 'msg' => 'Contenido creado correctamente');
            } else {
                $errorInfo = $queryInsert->errorInfo();
                $respuesta = array('status' => false, 'msg' => 'Error en la creación: ' . $errorInfo[2]);
            }
        } else {
            // Actualización
            $sql = "SELECT material FROM contenidos WHERE contenido_id = ?";
            $query = $pdo->prepare($sql);
            $query->execute([$idcontenido]);
            $data = $query->fetch(PDO::FETCH_ASSOC);
            
            if (!empty($material)) {
                // Eliminar el archivo anterior
                if (!empty($data['material']) && file_exists($data['material'])) {
                    unlink($data['material']);
                }
                $sqlUpdate = 'UPDATE contenidos SET titulo = ?, descripcion = ?, material = ?, pm_id = ? WHERE contenido_id = ?';
                $queryUpdate = $pdo->prepare($sqlUpdate);
                $request = $queryUpdate->execute([$titulo, $descripcion, $material, $idcurso, $idcontenido]);
            } else {
                // Actualizar sin archivo
                $sqlUpdate = 'UPDATE contenidos SET titulo = ?, descripcion = ?, pm_id = ? WHERE contenido_id = ?';
                $queryUpdate = $pdo->prepare($sqlUpdate);
                $request = $queryUpdate->execute([$titulo, $descripcion, $idcurso, $idcontenido]);
            }
            
            if ($request) {
                $respuesta = array('status' => true, 'msg' => 'Contenido actualizado correctamente');
            } else {
                $errorInfo = $queryUpdate->errorInfo();
                $respuesta = array('status' => false, 'msg' => 'Error al actualizar: ' . $errorInfo[2]);
            }
        }
    }

    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
}
?>
