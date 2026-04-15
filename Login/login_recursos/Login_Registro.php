<?php 
session_start();
include 'conexion_BD.php';

// Función para evitar SQL Injection y manejar errores
function ejecutarConsulta($conexion, $consulta, $parametros = [], $tipos = '') {
    $stmt = $conexion->prepare($consulta);
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conexion->error); // Manejo de errores en la preparación
    }
    
    if ($parametros) {
        $stmt->bind_param($tipos, ...$parametros);
    }

    if (!$stmt->execute()) {
        die("Error en la ejecución de la consulta: " . $stmt->error); // Manejo de errores en la ejecución
    }
    
    return $stmt;
}

// Registro de usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_Completo'], $_POST['Correo'], $_POST['Contraseña'], $_POST['parada_o_sector'])) {
    $nombre_Completo = trim($_POST['nombre_Completo']); // Eliminar espacios en blanco
    $correo = trim($_POST['Correo']);
    $contraseña = password_hash(trim($_POST['Contraseña']), PASSWORD_DEFAULT); // Asegurarse de que la contraseña esté protegida
    $parada_o_sector = trim($_POST['parada_o_sector']);

    // Verificar que no se repita el correo
    $stmt = ejecutarConsulta($conexion_DB, "SELECT * FROM usuarios_escaner WHERE Correo = ?", [$correo], 's');
    if ($stmt->get_result()->num_rows > 0) {
        header("Location: ../Login_Index.php?mensaje=Error, este correo ya fue registrado. Intenta nuevamente con un nuevo correo.");
        exit();
    }

    // Verificar que no se repita el nombre
    $stmt = ejecutarConsulta($conexion_DB, "SELECT * FROM usuarios_escaner WHERE Nombre = ?", [$nombre_Completo], 's');
    if ($stmt->get_result()->num_rows > 0) {
        header("Location: ../Login_Index.php?mensaje=Error, este usuario ya fue registrado. Intenta nuevamente con un nuevo usuario.");
        exit();
    }

    // Insertar el nuevo usuario en la base de datos
    $query = "INSERT INTO usuarios_escaner (Nombre, Correo, Contraseña, aprobado, parada_o_sector) VALUES (?, ?, ?, 0, ?)";
    $stmt = ejecutarConsulta($conexion_DB, $query, [$nombre_Completo, $correo, $contraseña, $parada_o_sector], 'ssss');

    if ($stmt) {
        header("Location: ../Login_Index.php?mensaje=Has sido registrado exitosamente. Espera a ser aprobado.");
    } else {
        header("Location: ../Login_Index.php?mensaje=Hubo un error al registrar. Intente nuevamente.");
    }
    exit();
}

// Inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Correo'], $_POST['Contraseña'])) {
    $correo = trim($_POST['Correo']);
    $contraseña = $_POST['Contraseña'];

    // Verificar si el correo existe en la base de datos
    $stmt = ejecutarConsulta($conexion_DB, "SELECT * FROM usuarios_escaner WHERE Correo = ?", [$correo], 's');
    $usuario = $stmt->get_result()->fetch_assoc();

    // Verificar si el usuario existe
    if ($usuario) {
        // Verificar la contraseña
        if (password_verify($contraseña, $usuario['Contraseña'])) {
            if ($usuario['aprobado'] == 1) {
                // Almacenar el id del usuario en la sesión junto con otros datos
                $_SESSION['usuario'] = [
                    'id' => $usuario['id'],
                    'correo' => $correo,
                    'nombre' => $usuario['Nombre'],
                    'parada_o_sector' => $usuario['parada_o_sector']
                ];
                header("Location: ../../Escáner/Qr_Escaner.php");
                exit();
            } else {
                header("Location: ../Login_Index.php?mensaje=Tu cuenta aún no ha sido aprobada. Por favor espera a ser aprobado.");
            }
        } else {
            header("Location: ../Login_Index.php?mensaje=Contraseña incorrecta. Vuelve a intentar.");
        }
    } else {
        header("Location: ../Login_Index.php?mensaje=Correo no registrado. Vuelve a intentar.");
    }
    exit();
}
?>
