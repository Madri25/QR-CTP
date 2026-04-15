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
    $id = null;

    // Obtener el ID del usuario antes de guardar la foto
    $sql = "SELECT id FROM usuarios_escaner WHERE Correo = ?";
    $stmt = $conexion_DB->prepare($sql);
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $stmt->bind_result($id);
    $stmt->fetch();
    $stmt->close();

    if ($id) {
        // Crear el directorio específico para el usuario si no existe
        $target_dir = "./uploads/" . $nombre . "_" . $id . "/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        // Generar el path para el archivo
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);

        // Mover el archivo subido
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
    $password = password_hash($password, PASSWORD_DEFAULT); // Usar password_hash para mayor seguridad
    if ($foto_path) {
        $sql = "UPDATE usuarios_escaner SET Nombre = ?, parada_o_sector = ?, aprobado = ?, Contraseña = ?, foto = ? WHERE Correo = ?";
        $types = 'sssiss';
        $params = [$nombre, $parada_o_sector, $estado, $password, $foto_path, $correo];
    } else {
        $sql = "UPDATE usuarios_escaner SET Nombre = ?, parada_o_sector = ?, aprobado = ?, Contraseña = ? WHERE Correo = ?";
        $types = 'sssii';
        $params = [$nombre, $parada_o_sector, $estado, $password, $correo];
    }
} else {
    if ($foto_path) {
        $sql = "UPDATE usuarios_escaner SET Nombre = ?, parada_o_sector = ?, aprobado = ?, foto = ? WHERE Correo = ?";
        $types = 'ssiss';
        $params = [$nombre, $parada_o_sector, $estado, $foto_path, $correo];
    } else {
        $sql = "UPDATE usuarios_escaner SET Nombre = ?, parada_o_sector = ?, aprobado = ? WHERE Correo = ?";
        $types = 'ssis';
        $params = [$nombre, $parada_o_sector, $estado, $correo];
    }
}

$stmt = $conexion_DB->prepare($sql);
if (!$stmt) {
    die("Error al preparar la consulta: " . $conexion_DB->error);
}

// Debugging: Imprimir consulta y parámetros (solo en desarrollo)
echo "Consulta: $sql<br>";
print_r($params);

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
