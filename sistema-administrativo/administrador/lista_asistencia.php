<?php
include 'includes/header.php';
include '../../Login/login_recursos/conexion_BD.php';

// Inicializar variables de filtrado
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$seccion = isset($_GET['seccion']) ? $_GET['seccion'] : '';
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';

// Inicializar la variable de resultados y el contador
$resultado = null;
$contador = 0;

// Comprobar si se ha realizado algún filtro
$filtrosActivos = !empty($fecha_inicio) || !empty($fecha_fin) || !empty($seccion) || !empty($nombre);

// Crear la consulta SQL base
$sql = "SELECT estudiantes.nombre, estudiantes.seccion, estudiantes.foto, COUNT(*) AS total_asistencias
        FROM asistencia
        JOIN estudiantes ON asistencia.estudiante_id = estudiantes.id
        WHERE 1=1";

$params = []; // Inicializar array para los parámetros

// Filtrar por rango de fechas si se han proporcionado
if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $sql .= " AND DATE(asistencia.fecha_hora) BETWEEN ? AND ?";
    $params[] = $fecha_inicio;
    $params[] = $fecha_fin;
} elseif (!empty($fecha_inicio)) {
    $sql .= " AND DATE(asistencia.fecha_hora) >= ?";
    $params[] = $fecha_inicio;
} elseif (!empty($fecha_fin)) {
    $sql .= " AND DATE(asistencia.fecha_hora) <= ?";
    $params[] = $fecha_fin;
}

// Añadir filtro de sección si está presente
if (!empty($seccion)) {
    $sql .= " AND estudiantes.seccion LIKE ?";
    $params[] = "%$seccion%";
}

// Añadir filtro de nombre si está presente
if (!empty($nombre)) {
    $sql .= " AND estudiantes.nombre LIKE ?";
    $params[] = "%$nombre%";
}

// Agrupar por estudiante para contar asistencias
$sql .= " GROUP BY estudiantes.id";

// Preparar la consulta
$stmt = $conexion_DB->prepare($sql);
if (!$stmt) {
    die('Error al preparar la consulta: ' . $conexion_DB->error);
}

// Definir y vincular los parámetros
if (!empty($params)) {
    $bind_types = str_repeat('s', count($params)); // Todas las variables son cadenas (s)
    $stmt->bind_param($bind_types, ...$params);
}

// Ejecutar la consulta
$stmt->execute();
$resultado = $stmt->get_result();

// Contar el número de filas devueltas
$contador = $resultado ? $resultado->num_rows : 0;

// Manejo de errores
if (!$resultado) {
    die('Error en la consulta: ' . $stmt->error);
}
?>

<body>
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-calendar-check"></i> Registro de Asistencias</h1>
                <a href="./models/asistencia/add_student_form.php" class="btn btn-success"><i class="fa fa-user-plus"></i> Añadir Estudiante</a>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="#">Registro de Asistencias</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <form id="filterForm" method="GET">
                            <div class="form-group">
                                <label for="fecha_inicio">Fecha Inicio:</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
                            </div>
                            <div class="form-group">
                                <label for="fecha_fin">Fecha Fin:</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>">
                            </div>
                            <div class="form-group">
                                <label for="seccion">Sección:</label>
                                <input type="text" class="form-control" id="seccion" name="seccion" value="<?php echo htmlspecialchars($seccion); ?>">
                            </div>
                            <div class="form-group">
                                <label for="nombre">Nombre del Alumno:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </form>

                        <div class="mt-4">
                            <?php if ($filtrosActivos && $resultado && $contador > 0): ?>
                                <p><strong>Se encontraron <?php echo $contador; ?> registros de asistencia.</strong></p>
                            <?php elseif ($filtrosActivos): ?>
                                <p>No se encontraron resultados para las fechas seleccionadas.</p>
                            <?php endif; ?>
                        </div>

                        <div class="table-responsive mt-4">
                            <?php if ($filtrosActivos && $contador > 0): ?>
                                <table class="table table-hover table-bordered" id="tableasistencias">
                                    <thead>
                                        <tr>
                                            <th>Foto</th>
                                            <th>Nombre</th>
                                            <th>Sección</th>
                                            <th>Total de Asistencias</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = $resultado->fetch_assoc()) {
                                            // Construir la URL de la foto
                                            $fotoUrl = './models/asistencia/uploads/' . $row['seccion'] . '/' . $row['foto'];
                                            echo "<tr>
                                                    <td><img src='$fotoUrl' alt='Foto de {$row['nombre']}' style='width: 150px; height: auto;'></td>
                                                    <td>{$row['nombre']}</td>
                                                    <td>{$row['seccion']}</td>
                                                    <td>{$row['total_asistencias']}</td>
                                                  </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>

                        <!-- Botones para exportar los datos -->
                        <?php if ($filtrosActivos): ?>
                            <div class="btn-group mt-3">
                                <a href="./models/asistencia/export.php?format=csv&fecha_inicio=<?php echo htmlspecialchars($fecha_inicio); ?>&fecha_fin=<?php echo htmlspecialchars($fecha_fin); ?>&seccion=<?php echo htmlspecialchars($seccion); ?>&nombre=<?php echo htmlspecialchars($nombre); ?>" class="btn btn-info">Exportar CSV</a>
                                <a href="./models/asistencia/export.php?format=xls&fecha_inicio=<?php echo htmlspecialchars($fecha_inicio); ?>&fecha_fin=<?php echo htmlspecialchars($fecha_fin); ?>&seccion=<?php echo htmlspecialchars($seccion); ?>&nombre=<?php echo htmlspecialchars($nombre); ?>" class="btn btn-success">Exportar Excel</a>
                                <a href="./models/asistencia/export.php?format=pdf&fecha_inicio=<?php echo htmlspecialchars($fecha_inicio); ?>&fecha_fin=<?php echo htmlspecialchars($fecha_fin); ?>&seccion=<?php echo htmlspecialchars($seccion); ?>&nombre=<?php echo htmlspecialchars($nombre); ?>" class="btn btn-danger">Exportar PDF</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
<?php include 'includes/footer.php'; ?>
