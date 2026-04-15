<?php
// Configuración de la base de datos utilizando variables de entorno
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db   = getenv('DB_NAME') ?: 'sistema-escolar';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Mejora la seguridad de los datos obtenidos
} catch (PDOException $e) {
    error_log('Error en la conexión a la base de datos: ' . $e->getMessage()); // Registra el error en los logs
    http_response_code(500); // Envía un código de error HTTP 500 (Error interno del servidor)
    exit('Error de conexión a la base de datos.'); // Mensaje genérico sin detalles sensibles
}
?>
