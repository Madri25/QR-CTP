<?php
session_start();

// Verifica si la sesión está activa
if (session_status() == PHP_SESSION_ACTIVE) {
    session_unset(); // Libera todas las variables de sesión
    session_destroy(); // Destruye la sesión
}

// Redirige al usuario con un código de estado 302 (Redirección temporal)
header("Location: /Pagina_xampp/Index.php", true, 302);
exit(); // Asegura que el script se detenga después de la redirección
?>
