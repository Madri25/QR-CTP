<?php
// Inicia la sesión
session_start();

// Verifica si la sesión está activa
if (isset($_SESSION)) {
    // Destruye la sesión
    session_unset();
    session_destroy();
}

// Redirige al usuario a la página principal
header('Location: ./');
exit(); // Asegúrate de que el script se detenga después de redirigir
?>
