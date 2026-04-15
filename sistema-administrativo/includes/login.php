<?php
session_start();
require_once './conexion.php'; // Ajusta la ruta según sea necesario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['usuario']) || empty($_POST['pass'])) {
        $_SESSION['login_error'] = 'Todos los campos son necesarios. Por favor, complete todos los campos obligatorios.';
        header('Location: ../index.php');
        exit();
    }

    $login = $_POST['usuario'];
    $pass = $_POST['pass'];

    // Verificar si el usuario existe en la tabla alumnos
    $sql = 'SELECT * FROM alumnos WHERE cedula = ?';
    $query = $pdo->prepare($sql);
    $query->execute([$login]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($query->rowCount() > 0) {
        // Usuario encontrado en la tabla alumnos
        if (password_verify($pass, $result['clave'])) {
            // Actualizar la columna `u_acceso` con la fecha y hora actual
            $alumno_id = $result['alumno_id'];
            $fecha_hora_acceso = date('Y-m-d H:i:s'); // Obtener la fecha y hora actual

            $sql_actualizar_acceso = 'UPDATE alumnos SET u_acceso = ? WHERE alumno_id = ?';
            $stmt_actualizar_acceso = $pdo->prepare($sql_actualizar_acceso);
            $stmt_actualizar_acceso->execute([$fecha_hora_acceso, $alumno_id]);

            // Iniciar sesión para el alumno
            $_SESSION['activeA'] = true;
            $_SESSION['alumno_id'] = $result['alumno_id'];
            $_SESSION['nombre'] = $result['nombre_alumno'];
            $_SESSION['cedula'] = $result['cedula'];
            $_SESSION['u_acceso'] = $fecha_hora_acceso; // Almacenar el tiempo de acceso en la sesión
            header('Location: ../alumno/');
            exit();
        } else {
            $_SESSION['login_error'] = 'Contraseña incorrecta. Por favor, inténtelo de nuevo.';
            header('Location: ../index.php');
            exit();
        }
    } else {
        // Verificar en la tabla profesores
        $sql = 'SELECT * FROM profesor WHERE cedula = ?';
        $query = $pdo->prepare($sql);
        $query->execute([$login]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($query->rowCount() > 0) {
            if (password_verify($pass, $result['clave'])) {
                $_SESSION['activeP'] = true;
                $_SESSION['profesor_id'] = $result['profesor_id'];
                $_SESSION['nombre'] = $result['nombre'];
                $_SESSION['cedula'] = $result['cedula'];
                header('Location: ../profesor/');
                exit();
            } else {
                $_SESSION['login_error'] = 'Contraseña incorrecta. Por favor, inténtelo de nuevo.';
                header('Location: ../index.php');
                exit();
            }
        } else {
            // Verificar en la tabla administradores
            $sql = 'SELECT * FROM usuarios AS u INNER JOIN rol AS r ON u.rol = r.rol_id WHERE u.usuario = ?';
            $query = $pdo->prepare($sql);
            $query->execute([$login]);
            $result = $query->fetch(PDO::FETCH_ASSOC);

            if ($query->rowCount() > 0) {
                if (password_verify($pass, $result['clave'])) {
                    $_SESSION['active'] = true;
                    $_SESSION['id_usuario'] = $result['usuario_id'];
                    $_SESSION['nombre'] = $result['usuario'];
                    $_SESSION['rol'] = $result['rol_id'];
                    $_SESSION['nombre_rol'] = $result['nombre_rol'];
                    header('Location: ../administrador/');
                    exit();
                } else {
                    $_SESSION['login_error'] = 'Contraseña incorrecta. Por favor, inténtelo de nuevo.';
                    header('Location: ../index.php');
                    exit();
                }
            } else {
                $_SESSION['login_error'] = 'Usuario no encontrado. Verifique su cédula o nombre de usuario y vuelva a intentarlo.';
                header('Location: ../index.php');
                exit();
            }
        }
    }
}
?>
