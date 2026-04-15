<?php
// Determina el directorio base
$baseDir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

// Determina el esquema (http o https)
$scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';

// Determina el host
$host = $_SERVER['HTTP_HOST'];

// Construye la URL base
$baseUrl = $scheme . '://' . $host . $baseDir;

// Define la constante BASE_URL
define('BASE_URL', $baseUrl);
?>
