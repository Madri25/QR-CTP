<?php
require_once '../../../includes/conexion.php';

if (!empty($_POST)) {
    // Verificar que los campos obligatorios no estén vacíos
    if (empty($_POST['titulo']) || empty($_POST['descripcion']) || empty($_POST['fecha']) || empty($_POST['valor']) || empty($_POST['idcontenido'])) {
        $respuesta = array('status' => false, 'msg' => 'Todos los campos son necesarios');
    } else {
        // Captura de valores
        $idevaluacion = intval($_POST['idevaluacion']); // Asegurar que sea un número
        $idcontenido = intval($_POST['idcontenido']);   // Asegurar que sea un número
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $fecha = $_POST['fecha'];
        $valor = floatval($_POST['valor']); // Asegurar que sea un número decimal

        try {
            // Si idevaluacion es 0, es una nueva evaluación; de lo contrario, es una actualización
            if ($idevaluacion == 0) {
                $sqlInsert = 'INSERT INTO evaluaciones (titulo, descripcion, fecha, porcentaje, contenido_id) VALUES (?, ?, ?, ?, ?)';
                $queryInsert = $pdo->prepare($sqlInsert);
                $request = $queryInsert->execute(array($titulo, $descripcion, $fecha, $valor, $idcontenido));
                $accion = 1;
            } else {
                $sqlUpdate = 'UPDATE evaluaciones SET titulo = ?, descripcion = ?, fecha = ?, porcentaje = ?, contenido_id = ? WHERE evaluacion_id = ?';
                $queryUpdate = $pdo->prepare($sqlUpdate);
                $request = $queryUpdate->execute(array($titulo, $descripcion, $fecha, $valor, $idcontenido, $idevaluacion));
                $accion = 2;
            }

            // Verificar si la consulta se ejecutó correctamente
            if ($request) {
                if ($accion == 1) {
                    $respuesta = array('status' => true, 'msg' => 'Evaluación creada correctamente');
                } else {
                    $respuesta = array('status' => true, 'msg' => 'Evaluación actualizada correctamente');
                }
            } else {
                $respuesta = array('status' => false, 'msg' => 'No se pudo completar la operación');
            }
        } catch (PDOException $e) {
            // Manejar errores de PDO
            $respuesta = array('status' => false, 'msg' => 'Error en la operación: ' . $e->getMessage());
        }
    }

    // Respuesta final en formato JSON
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
}
?>