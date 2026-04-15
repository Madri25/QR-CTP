<?php
  session_start(); // Inicia la sesión o reanuda una sesión existente.
  if(empty($_SESSION['active'])) { // Verifica si la variable de sesión 'active' está vacía.
    header('Location: ../../../'); // Si está vacía, redirige al usuario a la página principal (../).
  }
include '../../../../Login/login_recursos/conexion_BD.php';

// Verifica si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoge los datos del formulario
    $codigo = mysqli_real_escape_string($conexion_DB, $_POST['codigo']);
    $nombre = mysqli_real_escape_string($conexion_DB, $_POST['nombre']);
    $seccion = mysqli_real_escape_string($conexion_DB, $_POST['seccion']);
    $parada_o_sector = mysqli_real_escape_string($conexion_DB, $_POST['parada_o_sector']);
    $foto = $_FILES['foto'];

    // Nombre del archivo subido
    $fileName = basename($foto['name']);
    
    // Crear un directorio específico para la sección
    $sectionDir = __DIR__ . '/uploads/' . $seccion . '/';
    
    // Verifica si el directorio de la sección ya existe, si no, lo crea
    if (!file_exists($sectionDir)) {
        mkdir($sectionDir, 0755, true);
    }

    $filePath = $sectionDir . $fileName;

    // Verifica si el archivo se ha subido correctamente
    if ($foto['error'] !== UPLOAD_ERR_OK) {
        echo "Error al subir el archivo: " . $foto['error'];
    } else {
        // Verifica si ya existe un estudiante con el mismo nombre en la misma sección
        $query = "SELECT * FROM estudiantes WHERE nombre = '$nombre' AND seccion = '$seccion'";
        $result = mysqli_query($conexion_DB, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "El nombre ya está registrado en esta sección.";
        } else {
            // Verifica si la foto ya está en la base de datos
            $query = "SELECT * FROM estudiantes WHERE foto = '$fileName'";
            $result = mysqli_query($conexion_DB, $query);

            if (mysqli_num_rows($result) > 0) {
                echo "La foto ya está registrada en la base de datos.";
            } else {
                // Mueve el archivo subido al directorio de destino
                if (move_uploaded_file($foto['tmp_name'], $filePath)) {
                    // Inserta los datos en la base de datos
                    $sql = "INSERT INTO estudiantes (codigo, nombre, seccion, parada_o_sector, foto) VALUES ('$codigo', '$nombre', '$seccion', '$parada_o_sector', '$fileName')";
                    if (mysqli_query($conexion_DB, $sql)) {
                        echo "Estudiante añadido con éxito.";
                    } else {
                        echo "Error al añadir el estudiante: " . mysqli_error($conexion_DB);
                    }
                } else {
                    echo "Error al mover la foto.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>𝐴ñ𝑎𝑑𝑖𝑟 𝐸𝑠𝑡𝑢𝑑𝑖𝑎𝑛𝑡𝑒</title>
    <link rel="stylesheet" href="Style.css">
    <link rel="icon" type="png" href="../../../../Imágenes/Fondos/Logo_Colegio.png"> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Añadir Nuevo Estudiante</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="codigo">Código:</label>
                <input type="text" class="form-control" id="codigo" name="codigo" required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="seccion">Sección:</label>
                <input type="text" class="form-control" id="seccion" name="seccion" required>
            </div>
            <div class="form-group">
                <label for="parada_o_sector">Parada o Sector:</label>
                <input type="text" class="form-control" id="parada_o_sector" name="parada_o_sector" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Añadir Estudiante</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="formHandler.js" ></script>
</body>
</html>
