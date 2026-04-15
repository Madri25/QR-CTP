<?php
require_once 'includes/header.php';
require_once '../includes/conexion.php';
require_once '../includes/funciones.php';

// Verifica que el profesor esté autenticado
if (!isset($_SESSION['profesor_id'])) {
    header("Location: profesor/");
    exit();
}

$idProfesor = $_SESSION['profesor_id'];

// Inicializa las variables de sección y fecha
$seccion = '';
$fecha = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica si se ha enviado una sección y una fecha a través del formulario
    if (isset($_POST['seccion'])) {
        $seccion = $_POST['seccion'];
    }
    if (isset($_POST['fecha'])) {
        $fecha = $_POST['fecha'];
    }
}

// Consulta para obtener las secciones disponibles
$seccionesQuery = "SELECT DISTINCT seccion FROM estudiantes"; 
$secciones = $pdo->query($seccionesQuery)->fetchAll(PDO::FETCH_COLUMN);

// Consulta para obtener los datos de asistencia según la sección, fecha y profesor autenticado
$asistencia = [];
if ($seccion && $fecha) {
    $sql = "SELECT e.nombre AS nombre_estudiante, e.codigo AS cedula, a.fecha_hora, e.foto, e.seccion
            FROM asistencia a
            INNER JOIN estudiantes e ON a.estudiante_id = e.id
            INNER JOIN alumnos al ON e.codigo = al.cedula
            INNER JOIN alumno_profesor ap ON ap.alumno_id = al.alumno_id
            WHERE e.seccion = ? 
            AND DATE(a.fecha_hora) = ? 
            AND ap.pm_id = ?"; // Aseguramos que el profesor solo vea a sus estudiantes

    $query = $pdo->prepare($sql);
    $query->execute([$seccion, $fecha, $idProfesor]);
    $asistencia = $query->fetchAll(PDO::FETCH_ASSOC);
}
?>
<body>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Lista de Asistencia de Estudiantes</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">Asistencia de Estudiantes</a></li>
        </ul>
    </div>

    <!-- Formulario para seleccionar la sección y la fecha -->
    <div class="row">
        <form method="POST" action="asistencia.php" class="col-md-12">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="seccion">Seleccione una Sección:</label>
                    <select name="seccion" id="seccion" class="form-control" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($secciones as $sec) { ?>
                            <option value="<?= htmlspecialchars($sec) ?>" <?= ($sec === $seccion) ? 'selected' : '' ?>><?= htmlspecialchars($sec) ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="fecha">Seleccione una Fecha:</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Ver Asistencia</button>
        </form>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tablealumnos">
                            <thead>
                                <tr>
                                    <th>FOTO</th>
                                    <th>ALUMNO</th>
                                    <th>CEDULA</th>
                                    <th>FECHA DE ASISTENCIA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($asistencia)) {
                                    foreach ($asistencia as $data) {
                                        // Construir la URL de la foto
                                        $fotoUrl = '../administrador/models/asistencia/uploads/' . $data['seccion'] . '/' . $data['foto'];
                                        ?>
                                        <tr>
                                            <td>
                                                <img src="<?= $fotoUrl ?>" alt="Foto de <?= htmlspecialchars($data['nombre_estudiante']) ?>" style="width: 150px; height: auto;">
                                            </td>
                                            <td><?= htmlspecialchars($data['nombre_estudiante']) ?></td>
                                            <td><?= htmlspecialchars($data['cedula']) ?></td>
                                            <td><?= htmlspecialchars($data['fecha_hora'] ?? 'Sin asistencia') ?></td>
                                        </tr>
                                    <?php 
                                    } 
                                } else { 
                                    echo '<tr><td colspan="4">No hay asistencia registrada para esta sección en esta fecha.</td></tr>';
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <a href="index.php" class="btn btn-info"><< Volver Atras</a>
    </div>
</main>

<?php
require_once 'includes/footer.php';
?>
