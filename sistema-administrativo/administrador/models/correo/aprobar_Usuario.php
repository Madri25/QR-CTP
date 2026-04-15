<?php
include '../../../../Login/login_recursos/conexion_BD.php';

// Verificar si se ha recibido el parámetro 'correo'
if (isset($_GET['correo'])) {
    $correo = mysqli_real_escape_string($conexion_DB, $_GET['correo']);

    // Actualizar el estado de aprobación del usuario
    $query = "UPDATE usuarios_escaner SET aprobado = 1 WHERE Correo = '$correo'";
    $resultado = mysqli_query($conexion_DB, $query);

    if ($resultado) {
        echo '
            <script>
            alert("Usuario aprobado exitosamente.");
            window.location = "../../lista_correo.php";
            </script>
        ';
    } else {
        echo '
            <script>
            alert("Hubo un error al aprobar el usuario.");
             window.location = "../../lista_correo.php";
            </script>
        ';
    }
} else {
    // Redirigir si el parámetro 'correo' no está presente
    header('.location = "../../lista_correo.php');
    exit();
}

mysqli_close($conexion_DB); // Cierra la conexión a la base de datos
?>
