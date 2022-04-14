<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>Suplike social website</title>
  <meta name="description"
    content="suplike social is a website for friends and family to share" />

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://bethro.alwaysdata.net" />
  <meta property="og:title" content="join in and meet with others" />
  <meta property="og:site_name" content="suplike social website">
  <meta property="og:description"
    content="suplike social is a website for friends and family to share" />
  <meta property="og:image" content="https://bethro.alwaysdata.net/img/graphic.png" />
  <link itemprop="thumbnailUrl" href="https://bethro.alwaysdata.net/img/graphic.png" />
  <meta property="og:image:width" content="300">
  <meta property="og:image:height" content="150">
<!-- No need to change anything here -->
<meta property="og:type" content="website" />
<meta property="og:image:type" content="image/jpeg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:url" content="https://bethro.alwaysdata.net" />
  <meta property="twitter:title" content="jokes to like" />
  <meta property="twitter:description"
    content="suplike social is a website for friends and family to share" />
  <meta property="twitter:image" content="https://bethro.alwaysdata.net/img/graphic.png" />
  
  <meta name="theme-color" content="rgba(67, 22, 228, 0.844)">
  <!-- Windows Phone -->
  <meta name="msapplication-navbutton-color" content="rgba(67, 22, 228, 0.844)">
  <!-- iOS Safari -->
  <meta name="apple-mobile-web-app-status-bar-style" content="rgba(67, 22, 228, 0.844)">
  <link rel="apple-touch-icon" href="img/icon/apple-touch-icon.png" />
  <link rel="shortcut icon" href="img/icon/favicon.ico" type="image/x-icon" /> 
  <link rel="stylesheet" href="./lib/font-awesome/font-awesome.min.css" defer>
  <link rel="stylesheet" href="./lib/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/style.min.css">
  <link rel="manifest" href="manifest.json"> 
  <link rel="stylesheet" href="./lib/lightbox/lightbox.min.css">
  <script type="text/javascript" src="./lib/jquery/jquery.js"></script>
  <script src="lib/lazyload/jquery.lazyload-any.js" defer></script>  
  <script src="./js/online.js" defer></script>
  <script src="registerSW.js"></script>
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
          <a href="notification.php"><i title="notification" class="fa fa-bell fa-2x"></i></a>
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