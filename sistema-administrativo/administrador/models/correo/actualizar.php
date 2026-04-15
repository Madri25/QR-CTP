<?php
include '../../../../Login/login_recursos/conexion_BD.php';

// Obtener datos del formulario
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$correo = isset($_POST['correo']) ? $_POST['correo'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$parada_o_sector = isset($_POST['parada_o_sector']) ? $_POST['parada_o_sector'] : '';
$estado = isset($_POST['estado']) ? $_POST['estado'] : '';

// Validar que los campos obligatorios no estén vacíos
if (empty($nombre) || empty($correo) || empty($parada_o_sector) || !isset($estado)) {
    die("Todos los campos son obligatorios.");
}

// Validar el correo electrónico
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die("Correo electrónico no válido.");
}

// Manejo de la foto
$foto_path = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
    $target_dir = "./uploads/";

    // Obtener el ID del usuario antes de guardar la foto
    $sql = "SELECT id FROM usuarios_escaner WHERE Correo = ?";
    $stmt = $conexion_DB->prepare($sql);
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $stmt->bind_result($id);
    $stmt->fetch();
    $stmt->close();

    if ($id) {
        $target_file = $target_dir . $id . '_' . basename($_FILES["foto"]["name"]);
        
        // Verifica si el directorio existe, si no, créalo
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        // Mueve el archivo subido
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $foto_path = $target_file;
        } else {
            die("Error al subir la foto.");
        }
    } else {
        die("No se encontró el ID del usuario.");
    }
}

// Preparar la consulta de actualización
if (!empty($password)) {
    $password = md5($password); // Considera usar password_hash() para producción
    if ($foto_path) {
        $sql = "UPDATE usuarios_escaner SET Nombre = ?, parada_o_sector = ?, aprobado = ?, Contraseña = ?, foto = ? WHERE Correo = ?";
        $types = 'ssis'; // Especifica los tipos de datos: s = string, i = integer
        $params = [$nombre, $parada_o_sector, $estado, $password, $foto_path, $correo];
    } else {
        $sql = "UPDATE usuarios_escaner SET Nombre = ?, parada_o_sector = ?, aprobado = ?, Contraseña = ? WHERE Correo = ?";
        $types = 'ssiss'; // Especifica los tipos de datos: s = string, i = integer
        $params = [$nombre, $parada_o_sector, $estado, $password, $correo];
    }
} else {
    if ($foto_path) {
        $sql = "UPDATE usuarios_escaner SET Nombre = ?, parada_o_sector = ?, aprobado = ?, foto = ? WHERE Correo = ?";
        $types = 'ssiss'; // Especifica los tipos de datos: s = string, i = integer
        $params = [$nombre, $parada_o_sector, $estado, $foto_path, $correo];
    } else {
        $sql = "UPDATE usuarios_escaner SET Nombre = ?, parada_o_sector = ?, aprobado = ? WHERE Correo = ?";
        $types = 'ssis'; // Especifica los tipos de datos: s = string, i = integer
        $params = [$nombre, $parada_o_sector, $estado, $correo];
    }
}

$stmt = $conexion_DB->prepare($sql);
if (!$stmt) {
    die("Error al preparar la consulta: " . $conexion_DB->error);
}

// Vincular parámetros
$stmt->bind_param($types, ...$params);
if (!$stmt->execute()) {
    die("Error al actualizar el usuario: " . $stmt->error);
}

// Redirigir al usuario de vuelta a la lista
header('Location: ../../lista_correo.php'); // Asegúrate de que esta URL sea correcta
exit();

mysqli_close($conexion_DB); // Cierra la conexión a la base de datos
?>
