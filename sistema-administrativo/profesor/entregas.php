<?php
if (!empty($_GET['curso']) || empty($_GET['contenido']) || empty($_GET['eva'])) {
  $curso = $_GET['curso'];
  $contenido = $_GET['contenido'];
  $evaluacion = $_GET['eva'];
} else {
  header("Location: profesor/");
}
require_once 'includes/header.php';
require_once '../includes/arreglo.php';
require_once '../includes/conexion.php';



$idProfesor = $_SESSION['profesor_id'];

$sql = "SELECT*,date_format(fecha, '%d/%m/%Y') as fecha FROM evaluaciones WHERE contenido_id = $contenido AND evaluacion_id = $evaluacion";
$query = $pdo->prepare($sql);
$query->execute();
$row = $query->rowCount();

$sqla = "SELECT * FROM ev_entregadas as ev INNER JOIN alumnos as a ON ev.alumno_id = a.alumno_id INNER JOIN evaluaciones as eva ON ev.evaluacion_id = eva.evaluacion_id INNER JOIN contenidos as c ON eva.contenido_id = c.contenido_id WHERE ev.evaluacion_id = ?";
$querya = $pdo->prepare($sqla);
$querya->execute(array($evaluacion));
$rowa = $querya->rowCount();


?>

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-dashboard"></i> Evaluaciones Entregadas</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="#">Evaluaciones Entregadas</a></li>
    </ul>
  </div>
  <div class="row">
    <?php if ($row > 0) {
      while ($data = $query->fetch()) {
    ?>
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-title-w-btn">
              <h3 class="title"><?= $data['titulo']; ?></h3>
            </div>
            <div class="title-body">
              <b><?= $data['descripcion']; ?></b> <br><br>
              <b>Fecha: <kbd class="bg-info"><?= $data['fecha']; ?></kbd></b><br><br>
              <b>Valor: <?= $data['porcentaje']; ?></b>
            </div>

          </div>
        </div>
  </div>
<?php }
    } ?>
</div>
<div class="row mt-2 bg-secondary text-white p-2">
  <h3>Evaluaciones Entregadas</h3>

</div>

<div class="row mt-3">
  <?php if ($rowa > 0) {
    while ($data2 = $querya->fetch()) {
      $valor = '';
      $cargar = '';
      $alumno = $data2['alumno_id'];
      $ev_entregada = $data2['ev_entregada_id'];

      $sqln = "SELECT * FROM notas WHERE ev_entregada_id = $ev_entregada" ;
      $queryn = $pdo->prepare($sqln);
      $queryn->execute();
      $datan = $queryn->rowCount();
      if ($datan > 0) {
        $valor = '<kbd class="bg-success">Calificado</kbd>';
        $cargar = '';
      } else {
        require_once 'includes/modals/modal_nota.php';
        $valor = '<kbd class="bg-danger">Sin Calificar</kbd>';
        $cargar = '<button class="btn btn-warning" onclick="modalNota()" >Cargar Nota</button>';
      }


  ?>

      <div class="col-md-12">
        <div class="tile">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Alumno</th>
                <th>Observacion</th>
                <th>Material</th>
                <th>Estatus</th>
                <th>Cargar Nota</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?= $data2['nombre_alumno'] ?></td>
                <td><?= $data2['observacion'] ?></td>
                <td>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text"><i class="fas fa-download"></i></div>
                    </div>
                    <a class="btn btn-primary" href="BASE_URL" <?= $data2['material_alumno']; ?> target="_blank">Material</a>
                  </div>
                </td>
                <td><?= $valor ?></td>
                <td><?= $cargar ?></td>
              </tr>

            </tbody>

          </table>

        </div>
      </div>

</div>
<?php
    }
  }
?>

</div>

<div class="row">
  <a href="evaluacion.php?curso=<?= $curso ?>&contenido=<?= $contenido; ?>" class="btn btn-info"><< Volver Atras</a>
</div>
</main>

<?php
require_once 'includes/footer.php';
?>