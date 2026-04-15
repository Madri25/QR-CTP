<?php
    require_once 'includes/header.php';
    require_once 'includes/modals/modal_usuario.php';
?>

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Lista de Usuarios</h1>
            <button class="btn btn-success" type="button" onclick="openModalU()">Nuevo Usuario</button>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">Lista de usuarios</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <!-- Botones para exportar la tabla de usuarios -->
                        <div class="btn-group mt-3">
                            <a href="./models/usuarios/general_export.php?format=csv" class="btn btn-info">Exportar CSV</a>
                            <a href="./models/usuarios/general_export.php?format=xls" class="btn btn-success">Exportar Excel</a>
                            <a href="./models/usuarios/general_export.php?format=pdf" class="btn btn-danger">Exportar PDF</a>
                        </div>
                
                    
                    <!-- Tabla de usuarios -->
                    <div class="table-responsive mt-3">
                        <table class="table table-hover table-bordered" id="tableusuarios">
                            <thead>
                                <tr>
                                    <th>ACCIONES</th>
                                    <th>ID</th>
                                    <th>NOMBRE</th>
                                    <th>USUARIOS</th>
                                    <th>ROL</th>
                                    <th>ESTADO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se llenarán los datos de la tabla -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
    require_once 'includes/footer.php';
?>

