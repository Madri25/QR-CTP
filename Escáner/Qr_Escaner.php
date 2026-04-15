<?php 
session_start();
if(!isset($_SESSION['usuario']) || !is_array($_SESSION['usuario'])){
    echo '
        <script>
            alert("Debes de iniciar sesión para utilizar el código QR");
            window.location = "../Login/Login_Index.php";
        </script>    
    ';
    session_destroy();
    die();
}

$usuario = $_SESSION['usuario']; // Obtener los datos del usuario desde la sesión
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="Qr_Style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>𝐶𝑇𝑃 𝐸𝑠𝑐𝑎𝑛𝑒𝑟 𝑄𝑟 </title>
    <link rel="icon" type="image/png" href="../Imágenes/Fondos/Logo_Colegio.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>
<body>
            <header class="header">
                <nav> 
                    <div class="usuario-info">
                        <p>Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?></p>
                        <p>Sector: <?php echo htmlspecialchars($usuario['parada_o_sector']); ?></p>
                    </div> 
                    <a href="../Login/login_recursos/cerra_Seccion.php" class="cerrar_Seccion-button">Cerrar Sección</a>
                </nav>
                
                <div class="content">            
                    <div class="row justify-content-center mt-5">
                        <div class="col-sm-4">
                            <div id="reader"></div>
                        </div>
                    </div> 
                    
                    <div class="row mt-3">
                        <div class="col-6 text-center">
                            <label for="totalAsientos">Total de Asientos:</label>
                            <input type="number" id="totalAsientos" value="0">
                        </div>
                        <div class="col-6 text-center">
                            <label for="asientosDisponibles">Asientos Disponibles:</label>
                            <input type="number" id="asientosDisponibles" value="0" readonly>
                        </div>
                    </div>
                </div>
                <h5 id="Horario"></h5>
            </header>
    <script>
        // Establecer el ID del escaneador desde PHP
        const escaneadorId = <?php echo json_encode($usuario['id']); ?>;

        setInterval(() => {
            let Horario = new Date();
            let Horariohora = Horario.toLocaleString();
            document.getElementById("Horario").textContent = Horariohora;
        }, 1000);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="./qr_Recursos/Qr_Escaner.js"></script>
    <script src="./Qr_Escaner_global.js"></script>
    <script src="./asientos.js"></script>
</body>
</html>
