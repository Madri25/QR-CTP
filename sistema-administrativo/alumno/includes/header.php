<?php
  session_start();
  if(empty($_SESSION['activeA'])){
    header('Location: ../');
  }
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta name="description" content="Sistema Escolar">
    <title>𝐶𝑇𝑃 𝐴𝑙𝑢𝑚𝑛𝑜</title>
    <link rel="icon" type="png" href="../../Imágenes/Fondos/Logo_Colegio.png"> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <!-- Font-icon css-->
    <!--<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">-->
  </head>
  <body class="app sidebar-mini">
    <!-- Navbar-->
    <header class="app-header"><a class="app-header__logo" href="index.php">𝐴𝑙𝑢𝑚𝑛𝑜</a>
    <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Ocultar barra lateral"> <i class="fas fa-bars"></i></a>
      <!-- Navbar Right Menu-->
      <ul class="app-nav">
        <li class="app-search">
          <input class="app-search__input" type="search" placeholder="Search">
          <button class="app-search__button"><i class="fa fa-search"></i></button>
        </li>
        <!-- User Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
          <ul class="dropdown-menu settings-menu dropdown-menu-right">
          <li><a class="dropdown-item" href="page-user.html"><i class="fa fa-cog fa-lg"></i> Configuración</a></li> <!-- Enlace a la página de configuración del usuario. -->
          <li><a class="dropdown-item" href="page-user.html"><i class="fa fa-user fa-lg"></i> Perfil</a></li> <!-- Enlace a la página de perfil del usuario. -->
          <li><a class="dropdown-item" href="../includes/cerra_Seccion.php"><i class="fa fa-sign-out fa-lg"></i> Cerrar sesión</a></li> <!-- Enlace para cerrar sesión. -->
          </ul>
        </li>
      </ul>
    </header>
    <?php
        require_once 'nav.php'
    ?>