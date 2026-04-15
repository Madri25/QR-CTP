<?php
// Incluir la conexión a la base de datos
require_once '../../../includes/conexion.php'; // Ajusta la ruta según sea necesario

// Incluir las dependencias de Composer
require '../../../../vendor/autoload.php'; // Ajusta la ruta según sea necesario

// Importar las clases necesarias
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// No es necesario usar un namespace para TCPDF

// Obtener el formato de exportación y la tabla
$format = isset($_GET['format']) ? $_GET['format'] : 'csv';
$table = isset($_GET['table']) ? $_GET['table'] : 'usuarios'; // Tabla por defecto

// Definir columnas a excluir por tabla
$excludeColumns = [
    'usuarios' => ['Contraseña'], // Ejemplo: Excluir la columna 'Contraseña' de la tabla 'usuarios'
    'otra_tabla' => ['confidencial'] // Agrega otras tablas y columnas confidenciales según sea necesario
];

// Verificar si la tabla existe en la base de datos
$sqlCheckTable = "SHOW TABLES LIKE :table";
$stmtCheck = $pdo->prepare($sqlCheckTable);
$stmtCheck->execute(['table' => $table]);
if ($stmtCheck->rowCount() === 0) {
    die('Tabla no encontrada.');
}

// Crear la consulta SQL base
$sql = "SELECT * FROM $table";

try {
    // Preparar y ejecutar la consulta
    $stmt = $pdo->query($sql);
    if (!$stmt) {
        throw new Exception('Error en la consulta.');
    }

    // Inicializar variables comunes
    $columnCount = $stmt->columnCount();
    $columns = array();
    $exclude = isset($excludeColumns[$table]) ? $excludeColumns[$table] : [];
    
    for ($i = 0; $i < $columnCount; $i++) {
        $column = $stmt->getColumnMeta($i);
        if (!in_array($column['name'], $exclude)) {
            $columns[] = $column['name'];
        }
    }

    if ($format == 'csv') {
        // Exportar CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="reporte_' . $table . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, $columns);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $filteredRow = array();
            foreach ($columns as $columnName) {
                if (isset($row[$columnName])) {
                    $filteredRow[$columnName] = $row[$columnName];
                }
            }
            fputcsv($output, $filteredRow);
        }
        fclose($output);
        exit();

    } elseif ($format == 'xls') {
        // Exportar Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $colIndex = 'A';
        foreach ($columns as $columnName) {
            $sheet->setCellValue($colIndex . '1', $columnName);
            $colIndex++;
        }

        $rowIndex = 2;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $colIndex = 'A';
            foreach ($columns as $columnName) {
                if (isset($row[$columnName])) {
                    $sheet->setCellValue($colIndex . $rowIndex, $row[$columnName]);
                }
                $colIndex++;
            }
            $rowIndex++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="reporte_' . $table . '.xlsx"');
        $writer->save('php://output');
        exit();

    } elseif ($format == 'pdf') {
        // Exportar PDF
        if (!class_exists('TCPDF')) {
            die('TCPDF no está disponible. Verifique la ruta de inclusión.');
        }
        
        // Asegurar que no haya salida antes de esta sección
        ob_start();
        
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('Helvetica', '', 12);
        
        // Agregar el título
        $pdf->Cell(0, 10, 'Reporte de ' . ucfirst($table), 0, 1, 'C');
        
        // Agregar la tabla
        $html = '<table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse;">';
        $html .= '<thead><tr>';
        foreach ($columns as $columnName) {
            $html .= '<th>' . $columnName . '</th>';
        }
        $html .= '</tr></thead><tbody>';
        
        // Ejecutar nuevamente la consulta para leer los datos
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $html .= '<tr>';
            foreach ($columns as $columnName) {
                if (isset($row[$columnName])) {
                    $html .= '<td>' . htmlspecialchars($row[$columnName]) . '</td>';
                }
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        $pdf->writeHTML($html);
        
        // Limpiar el buffer de salida antes de enviar el PDF
        ob_end_clean();
        
        $pdf->Output('reporte_' . $table . '.pdf', 'D');
        exit();
    } else {
        die('Formato no soportado.');
    }
} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}
?>
