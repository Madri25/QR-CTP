<?php
// Evita cualquier salida antes de la generación del archivo
ob_start();

// Conectar a la base de datos
include '../../../../Login/login_recursos/conexion_BD.php';

// Obtener los parámetros de filtrado
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$seccion = isset($_GET['seccion']) ? $_GET['seccion'] : '';
$nombre_estudiante = isset($_GET['nombre']) ? $_GET['nombre'] : '';

// Crear la consulta SQL base
$sql = "SELECT estudiantes.nombre, estudiantes.seccion, asistencia.fecha_hora, estudiantes.foto
        FROM asistencia
        JOIN estudiantes ON asistencia.estudiante_id = estudiantes.id
        WHERE 1=1";

// Añadir filtros
if ($fecha_inicio && $fecha_fin) {
    $sql .= " AND DATE(asistencia.fecha_hora) BETWEEN ? AND ?";
}

if ($seccion) {
    $sql .= " AND estudiantes.seccion LIKE ?";
}

if ($nombre_estudiante) {
    $sql .= " AND estudiantes.nombre LIKE ?";
}

// Preparar y ejecutar la consulta
$stmt = $conexion_DB->prepare($sql);
if (!$stmt) {
    die('Error al preparar la consulta: ' . $conexion_DB->error);
}

// Asignar parámetros según los filtros aplicados
$params = [];
$types = '';

if ($fecha_inicio && $fecha_fin) {
    $params[] = $fecha_inicio;
    $params[] = $fecha_fin;
    $types .= 'ss';
}

if ($seccion) {
    $params[] = "%$seccion%";
    $types .= 's';
}

if ($nombre_estudiante) {
    $params[] = "%$nombre_estudiante%";
    $types .= 's';
}

// Vincular parámetros si hay
if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$resultado = $stmt->get_result();

// Comprobar el formato de exportación
$format = $_GET['format'] ?? 'csv';

if ($format == 'csv') {
    // Exportar CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="reporte_asistencia.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, array('Nombre', 'Sección', 'Fecha y Hora', 'Foto'));

    while ($row = $resultado->fetch_assoc()) {
        $fotoUrl = '../../models/asistencia/uploads/' . $row['seccion'] . '/' . $row['foto'];
        fputcsv($output, array($row['nombre'], $row['seccion'], $row['fecha_hora'], $fotoUrl));
    }
    fclose($output);
    exit();

} elseif ($format == 'xls') {
    // Exportar Excel
    require '../../vendor/autoload.php';
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Nombre');
    $sheet->setCellValue('B1', 'Sección');
    $sheet->setCellValue('C1', 'Fecha y Hora');
    $sheet->setCellValue('D1', 'Foto');

    $rowIndex = 2;
    while ($row = $resultado->fetch_assoc()) {
        $fotoUrl = '../../models/asistencia/uploads/' . $row['seccion'] . '/' . $row['foto'];
        $sheet->setCellValue("A{$rowIndex}", $row['nombre']);
        $sheet->setCellValue("B{$rowIndex}", $row['seccion']);
        $sheet->setCellValue("C{$rowIndex}", $row['fecha_hora']);
        $sheet->setCellValue("D{$rowIndex}", $fotoUrl);
        $rowIndex++;
    }

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="reporte_asistencia.xlsx"');
    $writer->save('php://output');
    exit();

} elseif ($format == 'pdf') {
    // Exportar PDF
    require_once '../../vendor/autoload.php';
    if (!class_exists('TCPDF')) {
        die('TCPDF no está disponible. Verifique la ruta de inclusión.');
    }
    
    $pdf = new \TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('Helvetica', '', 12);
    
    // Agregar el logo del colegio
    $logoPath = '../../../../Imágenes/Fondos/Logo_Colegio.png';
    $pdf->Image($logoPath, 10, 5, 50, '', 'PNG', '', 'T', true, 300, '', false, false, 0, false, false, false);

    // Añadir espacio después del logotipo
    $pdf->Ln(40); // Añadir 40 unidades de espacio para desplazar más la tabla hacia abajo
    
    // Generar la tabla de asistencia
    $html = '<h2 style="text-align: center;">Reporte de Asistencias</h2>';
    $html .= '<table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse;">';
    $html .= '<thead><tr><th>Nombre</th><th>Sección</th><th>Fecha y Hora</th><th>Foto</th></tr></thead>';
    $html .= '<tbody>';

    while ($row = $resultado->fetch_assoc()) {
        $fotoUrl = '../../models/asistencia/uploads/' . $row['seccion'] . '/' . $row['foto'];
        $html .= '<tr>';
        $html .= '<td>' . $row['nombre'] . '</td>';
        $html .= '<td>' . $row['seccion'] . '</td>';
        $html .= '<td>' . $row['fecha_hora'] . '</td>';
        $html .= '<td><img src="' . $fotoUrl . '" alt="Foto" style="width: 100px; height: auto;"></td>';
        $html .= '</tr>';
    }
    $html .= '</tbody></table>';
    
    // Escribir el HTML en el PDF
    $pdf->writeHTML($html);
    $pdf->Output('reporte_asistencia.pdf', 'D');
    exit();

} else {
    die('Formato no soportado.');
}
