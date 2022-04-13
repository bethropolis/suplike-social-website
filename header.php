<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta name="theme-color" content="rgba(67, 22, 228, 0.844)">
  <!-- Windows Phone -->
  <meta name="msapplication-navbutton-color" content="rgba(67, 22, 228, 0.844)">
  <!-- iOS Safari -->
  <meta name="apple-mobile-web-app-status-bar-style" content="rgba(67, 22, 228, 0.844)">
  <title>suplike</title>
  <link rel="icon" type="image/png" href="img/logo.png">
  <link rel="stylesheet" href="./lib/font-awesome/font-awesome.min.css" defer>
  <link rel="stylesheet" href="./lib/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/style.css?kkk">
  <link rel="manifest" media="screen and (max-device-width: 600px)" href="manifest.json"> 
  <link rel="stylesheet" href="./lib/lightbox/lightbox.min.css">
  <script type="text/javascript" src="./lib/jquery/jquery.js"></script>
  <script src="./js/online.js" defer></script>

</head>

<body>
  <div class="loader">
    <progress-ring stroke="4" radius="60" progress="0"></progress-ring>
    <h2>suplike</h2>
  </div>
  <script src="js/loader.js"></script>
  <header style="position: sticky;top: 0;z-index: 20;">
    <nav class="navbar sticky-top navbar-expand-md navbar-light bg-light p-1">
      <a href="./">
        <img title="logo" src="img/logo.png" alt="logo">
      </a>
      <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse justify-content-right collapse" id="navbarSupportedContent">
        <?php
        if (isset($_SESSION['userId'])) {
          echo '<ul class="navbar-nav ml-auto mr-1">';
          echo '<a href="./"><i title="home" class="fa fa-home fa-2x"></i></a> ';

          echo '<div id="header-addons">';
          if ($_SESSION['isAdmin'] == true) {
            echo '<a href="./dashboard/"><i title="dashboard" class="fa fa-bar-chart fa-2x"></i></a> ';
          }
          echo '</div>';

          echo '  
          <a href="social.php"><i title="friends" class="fa fa-users fa-2x"></i></a>    
          <a href="message.php"><i title="direct inbox" class="fa fa-envelope fa-2x"></i></a>    
          <a href="search.php"><i title="search for users or post" class="fa fa-search fa-2x" ></i></a>
          <a href="settings.php?profile"><i title="settings" class="fa fa-cog fa-2x"></i></a>   
          <a href="inc/logout.inc.php"><i id="logout" title="logout" class="fa fa-sign-out fa-2x"></i></a>
          </ul>';
        }

        if (!isset($_SESSION['userId'])) {
          echo `<ul class="navbar-nav ml-auto mr-1">
                 <a href="https://github.com/bethropolis/suplike-social-website"><i title="project on github" class="fa fa-github fa-2x" ></i></a>
                 </ul>
                `;
        }

        ?></div>
    </nav>
  </header>