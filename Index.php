<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>𝐶𝑇𝑃 𝐿𝑎 𝐶𝑎𝑟𝑝𝑖𝑜</title>
    <link rel="stylesheet" href="Web-style.css"> 
    <link rel="icon" href="../Pagina_xampp/Imágenes/Fondos/Logo_Colegio.png" type="image/png">
    <!-- Conexión a Google Fonts para importar la fuente Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <nav class="navigation">
            <ul class="list">
                <!-- Elemento de la lista: Logo de autobús -->
                <li class="list_item">
                    <img src="../Pagina_xampp/Imágenes/Iconos Bus/bus.icono-Photoroom.png" class="Icono_bus" alt="Ícono de autobús que representa el transporte escolar">
                </li>

                <!-- Elemento de la lista: QR con menú desplegable -->
                <li class="list_item--click" aria-haspopup="true" aria-expanded="false">
                    <div class="list_button list__button--click">
                        <img src="../Pagina_xampp/Box Icons/qr-scanner.svg" class="list_img" alt="Ícono de escáner QR">
                        <a href="#" class="nav_link">QR <img src="../Pagina_xampp/Box Icons/flecha.svg" class="list_arrow" alt="Flecha indicando menú desplegable"></a>
                    </div>
                    <ul class="list_show" role="menu">
                        <li class="list_inside" role="menuitem">
                            <a href="../Pagina_xampp/Escáner/Qr_Escaner.php" class="nav_link--inside">Escáner QR</a>
                            <img src="../Pagina_xampp/Box Icons/escaner.svg" alt="Escáner QR icono">
                        </li>
                        <li class="list_inside" role="menuitem">
                            <a href="../Pagina_xampp/Login/Login_Index.php" class="nav_link--inside">Inicio de Sesión</a>
                            <img src="../Pagina_xampp/Box Icons/user.svg" alt="Ícono de usuario">
                        </li>
                    </ul>
                </li>

                <!-- Elemento de la lista: Información con menú desplegable -->
                <li class="list_item--click" aria-haspopup="true" aria-expanded="false">
                    <div class="list_button list__button--click">
                        <img src="../Pagina_xampp/Box Icons/busqueda.svg" class="list_img" alt="Ícono de búsqueda">
                        <a href="#" class="nav_link">Información <img src="../Pagina_xampp/Box Icons/flecha.svg" class="list_arrow" alt="Flecha indicando menú desplegable"></a>
                    </div>
                    <ul class="list_show" role="menu">
                        <li class="list_inside" role="menuitem">
                            <a href="../Pagina_xampp/Propósitos/Propósitos.php" class="nav_link--inside">Propósitos</a>
                            <img src="../Pagina_xampp/Box Icons/meta.svg" alt="Ícono de propósitos">
                        </li>
                    </ul>
                </li>

                <!-- Elemento de la lista: Administración -->
                <li class="list_item">
                    <div class="list_button">
                        <img src="../Pagina_xampp/Box Icons/Herramienta.svg" class="list_img" alt="Ícono de administración">
                        <a href="../Pagina_xampp/sistema-administrativo/" class="nav_link">Sistema Escolar</a>
                    </div>
                </li>

                <!-- Elemento de la lista: Ubicación -->
                <li class="list_item">
                    <div class="list_button">
                        <img src="../Pagina_xampp/Box Icons/ubicacion.svg" class="list_img" alt="Ícono de ubicación">
                        <a href="https://www.google.com/maps/place/Escuela+Rafael+Vargas+Quir%C3%B3s/@9.9510775,-84.0931379,17z/data=!3m1!4b1!4m6!3m5!1s0x8fa0e4bba19e307f:0x3f3a6911b2227977!8m2!3d9.9510775!4d-84.090563!16s%2Fg%2F11cn7d6dz1?entry=ttu" class="nav_link">Ubicación</a>
                    </div>
                </li>

                <!-- Elemento de la lista: Facebook -->
                <li class="list_item">
                    <div class="list_button">
                        <img src="../Pagina_xampp/Box Icons/face.svg" class="list_img" alt="Ícono de Facebook">
                        <a href="https://www.facebook.com/CTPlacarpio?locale=es_LA" class="nav_link">Facebook</a>
                    </div>
                </li> 
                  <!-- Información de copyright y logo del colegio -->
                    <p>&copy; 2024 CTP. Todos los derechos reservados.</p>
                    <img src="../Pagina_xampp/Imágenes/Fondos/Logo_Colegio.png" class="Logo_Colegio" alt="Logo del Colegio CTP La Carpio">
            </ul>
        </nav>
      
    </header>
    <!-- Inclusión de script JavaScript -->
    <script src="Web-script.js"></script>
</body>
</html>
