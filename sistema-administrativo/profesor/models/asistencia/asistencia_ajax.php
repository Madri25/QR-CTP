<?php
require_once '../../../includes/conexion.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    $seccion = $data['seccion'];
    $fecha = $data['fecha'];
    $idProfesor = $_SESSION['profesor_id']; // Asegúrate de que el profesor esté autenticado

    if ($seccion && $fecha) {
        // Consulta SQL para obtener los datos de asistencia
        $sql = "SELECT e.nombre AS nombre_estudiante, e.codigo AS cedula, a.fecha_hora 
                FROM asistencia a
                INNER JOIN estudiantes e ON a.estudiante_id = e.id
                INNER JOIN alumnos al ON e.codigo = al.cedula
                INNER JOIN alumno_profesor ap ON ap.alumno_id = al.alumno_id
                WHERE e.seccion = ? 
                AND DATE(a.fecha_hora) = ? 
                AND ap.pm_id = ?";
        
        $query = $pdo->prepare($sql);
        $query->execute([$seccion, $fecha, $idProfesor]);
        $asistencia = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($asistencia) {
            echo json_encode($asistencia);
        } else {
            echo json_encode([]);
        }
    } else {
        echo json_encode(['error' => 'Sección o fecha no válida.']);
    }
} else {
    echo json_encode(['error' => 'Método de solicitud no válido.']);
}
