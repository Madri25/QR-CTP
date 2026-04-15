<?php
session_start();

// Verificar si hay un mensaje de error en la sesión
$message = '';
if (isset($_SESSION['login_error'])) {
    $message = $_SESSION['login_error'];
    unset($_SESSION['login_error']); // Limpiar el mensaje después de mostrarlo
}

// Redireccionar si el usuario ya está autenticado
if (!empty($_SESSION['active'])) {
    header('Location: administrador/');
    exit();
} elseif (!empty($_SESSION['activeP'])) {
    header('Location: profesor/');
    exit();
} elseif (!empty($_SESSION['activeA'])) {
    header('Location: alumno/');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="icon" type="image/png" href="../Imágenes/Fondos/Logo_Colegio.png"> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>𝐶𝑇𝑃 𝐿𝑎 𝐶𝑎𝑟𝑝𝑖𝑜.</title>
</head>
<body>
    <header class="main-header">
        <div class="main-cont">
            <div class="desc-header">
                <img src="../Imágenes/Fondos/Logo_Colegio.png" alt="Logo Colegio">
            </div>
        </div>
        <div class="cont-header">
            <h1>Cᴏʟᴇɢɪᴏ Tᴇᴄɴɪᴄᴏ Pʀᴏғᴇsɪᴏɴᴀʟ Lᴀ Cᴀʀᴘɪᴏ</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            
            <form action="./includes/login.php" method="post">
                <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Nombre de usuario o cédula" required>
                </div>
                <div class="form-group">
                    <label for="pass">Contraseña</label>
                    <input type="password" name="pass" id="pass" class="form-control" placeholder="Contraseña" required>
                </div>
                <button type="submit" class="btn btn-primary">INICIAR SESIÓN</button>
            </form>
        </div>
    </header>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
