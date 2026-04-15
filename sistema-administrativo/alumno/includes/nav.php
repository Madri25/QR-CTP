<?php
require_once '../includes/conexion.php';
$idAlumno = $_SESSION['alumno_id'];

try {
    // Consulta para los cursos del alumno
    $sql = "SELECT * FROM alumno_profesor as ap 
            INNER JOIN alumnos as al ON ap.alumno_id = al.alumno_id 
            INNER JOIN profesor_materia as pm ON ap.pm_id = pm.pm_id 
            INNER JOIN grados as g ON pm.grado_id = g.grado_id 
            INNER JOIN aulas as a ON pm.aula_id = a.aula_id 
            INNER JOIN profesor as p ON pm.profesor_id = p.profesor_id 
            INNER JOIN materias as m ON pm.materia_id = m.materia_id 
            WHERE al.alumno_id = :idAlumno";
    
    $query = $pdo->prepare($sql);
    $query->bindParam(':idAlumno', $idAlumno, PDO::PARAM_INT);
    $query->execute();
    $row = $query->rowCount();  // Cantidad de cursos encontrados

    // Inicializamos las variables relacionadas a las calificaciones
    $queryn = null;
    $rown = 0;

    // Consulta para las calificaciones del alumno
    $sqlCalificaciones = "SELECT * FROM calificaciones as c
                          INNER JOIN profesor_materia as pm ON c.pm_id = pm.pm_id
                          INNER JOIN materias as m ON pm.materia_id = m.materia_id
                          INNER JOIN grados as g ON pm.grado_id = g.grado_id
                          INNER JOIN aulas as a ON pm.aula_id = a.aula_id
                          WHERE c.alumno_id = :idAlumno"; 
    $queryn = $pdo->prepare($sqlCalificaciones);
    $queryn->bindParam(':idAlumno', $idAlumno, PDO::PARAM_INT);
    $queryn->execute();
    $rown = $queryn->rowCount();  // Cantidad de calificaciones encontradas

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>

<!-- Sidebar menu -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user">
        <img class="app-sidebar__user-avatar" src="images/user.png" alt="User Image">
        <div>
            <p class="app-sidebar__user-name"><?= $_SESSION['nombre'] ?></p>
            <p class="app-sidebar__user-designation">Alumno</p>
        </div>
    </div>
    <ul class="app-menu">
        <!-- Menú de Inicio -->
        <li><a class="app-menu__item" href="index.php"><i class="app-menu__icon fa fa-home"></i><span class="app-menu__label">Inicio</span></a></li>
        
        <!-- Menú de Mis Cursos -->
        <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i class="app-menu__icon fa fa-laptop"></i>
                <span class="app-menu__label">Mis Cursos</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <?php if($row > 0) {
                    while($data = $query->fetch()) { ?>
                        <li>
                            <a class="treeview-item" href="contenido.php?curso=<?= $data['pm_id']?>">
                                <i class="icon fa fa-cicle-o"></i>
                                <?= $data['nombre_materia']; ?> - <?= $data['nombre_grado']; ?> - <?= $data['nombre_aula']; ?>
                            </a>
                        </li>
                <?php } } ?>
            </ul>
        </li>

        <!-- Menú de Calificaciones -->
        <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i class="app-menu__icon fa fa-laptop"></i>
                <span class="app-menu__label">Calificaciones</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <?php if($rown > 0) {
                    while($datan = $queryn->fetch()) { ?>
                        <li>
                            <a class="treeview-item" href="notas.php?curso=<?= $datan['pm_id']?>">
                                <i class="icon fa fa-cicle-o"></i>
                                <?= $datan['nombre_materia']; ?> - <?= $datan['nombre_grado']; ?> - <?= $datan['nombre_aula']; ?>
                            </a>
                        </li>
                <?php } } ?>
            </ul>
        </li>

        <!-- Cerrar sesión -->
        <li><a class="app-menu__item" href="../logout.php"><i class="app-menu__icon fas fa-sign-out-alt"></i><span class="app-menu__label">Cerrar sesión</span></a></li>
    </ul>
</aside>

