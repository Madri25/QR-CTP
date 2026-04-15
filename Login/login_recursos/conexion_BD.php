<?php

// Datos de conexión
$servidor = "localhost";
$usuario = "root";
$contrasena = ""; // Asume que no hay contraseña para el usuario 'root'
$base_datos = "sistema-escolar";

// Crear la conexión
$conexion_DB = mysqli_connect($servidor, $usuario, $contrasena, $base_datos);

// Verificar la conexión
if (!$conexion_DB) {
    // Si la conexión falla, se muestra un mensaje de error y se detiene la ejecución
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}

// Opcional: establecer el juego de caracteres para la conexión
mysqli_set_charset($conexion_DB, "utf8");

?>
