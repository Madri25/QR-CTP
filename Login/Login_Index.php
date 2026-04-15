<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>𝐶𝑇𝑃 𝐿𝑜𝑔𝑖𝑛 𝑄𝑟</title>
    <link rel="icon" type="image/png" href="../Imágenes/Fondos/Logo_Colegio.png">
    <link rel="stylesheet" href="Login-style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/crypto-js@4.1.1/crypto-js.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.4/dist/parsley.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-3">
        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET['mensaje']) ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
    </div>

    <header>
        <div class="contenedor__todo">
            <div class="caja__trasera">
                <div class="caja__trasera-login">
                    <h3>¿Ya tienes una cuenta?</h3>
                    <p>Inicia sesión para entrar en la página</p>
                    <button id="btn__iniciar-sesion">Iniciar Sesión</button>
                </div>
                <div class="caja__trasera-register">
                    <h3>¿Aún no tienes una cuenta?</h3>
                    <p>Regístrate para que puedas iniciar sesión</p>
                    <button id="btn__registrarse">Registrarse</button>
                </div>
            </div>

            <!-- Formulario de Login y registro -->
            <div class="contenedor__login-register">
                <!-- Login -->
                <form id="form-login" action="./login_recursos/Login_Registro.php" method="POST" class="formulario__login" data-parsley-validate>
                    <h2>Iniciar Sesión</h2>
                    <input type="email" placeholder="Cᴏʀʀᴇᴏ Mᴇᴘ" name="Correo" required data-parsley-type="email">
                    <input type="password" placeholder="Cᴏɴᴛʀᴀsᴇɴ̃ᴀ" name="Contraseña" required data-parsley-minlength="6">
                    <button type="submit">Entrar</button>
                </form>

                <!-- Register -->
                <form id="form-register" action="./login_recursos/Login_Registro.php" method="POST" class="formulario__register" data-parsley-validate>
                    <h2>Registrarse</h2>
                    <input type="text" placeholder="Nᴏᴍʙʀᴇ Cᴏᴍᴘʟᴇᴛᴏ" name="nombre_Completo" required data-parsley-minlength="3">
                    <input type="email" placeholder="Cᴏʀʀᴇᴏ Mᴇᴘ" name="Correo" required data-parsley-type="email">
                    <input type="password" placeholder="Cᴏɴᴛʀᴀsᴇɴ̃ᴀ" name="Contraseña" required data-parsley-minlength="6">
                    <input type="text" placeholder="Parada o Sector" name="parada_o_sector" required>
                    <button type="submit">Registrarse</button>
                </form>
            </div>
        </div>
    </header>
    <script src="./login_recursos/Login-script.js"></script>
</body>
</html>

