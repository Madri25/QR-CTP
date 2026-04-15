<?php
  session_start(); // Inicia la sesión o reanuda una sesión existente.
  if(empty($_SESSION['active'])) { // Verifica si la variable de sesión 'active' está vacía.
    header('Location: ../'); // Si está vacía, redirige al usuario a la página principal (../).
  }
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta name="description" content="sistema escolar"> <!-- Proporciona una descripción de la página para motores de búsqueda y otros servicios. -->
    <title>𝐶𝑇𝑃 𝐴𝑑𝑚𝑖𝑛𝑖𝑠𝑡𝑟𝑎𝑐𝑖𝑜𝑛</title> <!-- Título de la página que aparece en la pestaña del navegador. -->
    <link rel="icon" type="png" href="../../Imágenes/Fondos/Logo_Colegio.png"> 
    <meta charset="utf-8"> <!-- Define la codificación de caracteres como UTF-8. -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Asegura que el contenido se renderice de acuerdo con los estándares de IE (Internet Explorer). -->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Configura la vista para que sea adaptable en dispositivos móviles. -->
    <!-- CSS principal -->
    <link rel="stylesheet" type="text/css" href="../css/style.css"> <!-- Enlaza la hoja de estilos principal. -->
    <link rel="stylesheet" type="text/css" href="../css/main.css"> <!-- Enlaza una hoja de estilos adicional. -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <!-- CSS para iconos de fuentes -->
    <!--<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">--> <!-- (Comentado) Enlaza una hoja de estilos para iconos de Font Awesome. -->
  </head>
  <body class="app sidebar-mini">
    <!-- Barra de navegación -->
    <header class="app-header">
      <a class="app-header__logo" href="index.php">𝐴𝑑𝑚𝑖𝑛𝑖𝑠𝑡𝑟𝑎𝑐𝑖𝑜𝑛</a> <!-- Enlace al inicio del sistema escolar. -->
      <!-- Botón para alternar la barra lateral -->
      <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"> <i class="fas fa-bars"></i></a>
      <!-- Menú derecho de la barra de navegación -->
      <ul class="app-nav">
        <!-- Menú de usuario -->
        <li class="dropdown">
          <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
          <ul class="dropdown-menu settings-menu dropdown-menu-right">
          <li><a class="dropdown-item" href="page-user.html"><i class="fa fa-cog fa-lg"></i> Configuración</a></li> <!-- Enlace a la página de configuración del usuario. -->
          <li><a class="dropdown-item" href="page-user.html"><i class="fa fa-user fa-lg"></i> Perfil</a></li> <!-- Enlace a la página de perfil del usuario. -->
          <li><a class="dropdown-item" href="../includes/cerra_Seccion.php"><i class="fa fa-sign-out fa-lg"></i> Cerrar sesión</a></li> <!-- Enlace para cerrar sesión. -->
          </ul>
        </li>
      </ul>
    </header>
<?php require_once 'nav.php'; ?> <!-- Incluye el archivo 'nav.php', que probablemente contiene la barra de navegación del sitio. -->
