<?php
if(!empty($_GET['curso'])){
    $curso = $_GET['curso'];
}else{
    header("Location: profesor/");
}
    require_once 'includes/header.php';
    require_once '../includes/conexion.php';
   

    $idProfesor = $_SESSION['profesor_id'];

    $sqlc = "SELECT * FROM alumno_profesor as ap INNER JOIN profesor_materia as pm ON ap.pm_id = pm.pm_id INNER JOIN alumnos as al ON ap.alumno_id = al.alumno_id WHERE pm.profesor_id = $idProfesor AND pm.pm_id = $curso GROUP BY al.alumno_id";
    $queryc = $pdo->prepare($sqlc);
    $queryc->execute();
    $rowc = $queryc->rowCount();
    ?>

<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Notas Cargadas</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="#">Notas Cargadas</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
                <div class="title-body">
                      <div class="table-responsive">
                             <table class="table table-hover table-bordered">
                                   <thead>
                                         <tr>
                                             <th>ALUMNO</th>
                                             <th>VER NOTAS</th>
                                         </tr>
                                   </thead>
                                    <tbody>
                                                <?php if($rowc > 0){
                                                    while($data = $queryc->fetch()){
                                                    ?>    
                                                    <tr>
                                                        <td><?=$data['nombre_alumno'] ?></td>
                                                        <td><a class="btn btn-primary btn-sm" title="Ver Notas" href="list-notas.php?alumno=<?= $data['alumno_id'] ?>&curso=<?=$data['pm_id'] ?>"><i class="fas fa-list"></i></a></td> 
                                                    </tr>    
                                      <?php }} ?>          
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