
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
    <title>bethropolis social</title>    
    <link rel="icon" type="image/png" href="img/logo.png">   
    <link rel="stylesheet" href="./lib/font-awesome/font-awesome.min.css"> 
    <link rel="stylesheet" href="./css/bootstrap.min.css">    
    <link rel="stylesheet" href="./css/style.css?a">    
 </head> 
<body> 
    <header>
        <nav class="navbar sticky-top navbar-expand-md navbar-light bg-light ">  
        <a href="./">  
         <img title="logo" src="img/logo.png" alt="logo">    
        </a> 
         <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
          </button>  
        <div class="navbar-collapse justify-content-right collapse" id="navbarSupportedContent" > 
        <?php 
        if (isset($_SESSION['userId'])){   
          echo '  
        <ul class="navbar-nav ml-auto mr-1">  
          <a href="./"><i title="home" class="fa fa-home fa-2x"></i></a>  
          <a href="social.php"><i title="friends" class="fa fa-users fa-2x"></i></a>    
          <a href="message.php"><i title="direct inbox" class="fa fa-envelope fa-2x"></i></a>    
          <a href="search.php"><i title="search for users or post" class="fa fa-search fa-2x" ></i></a>
          <a href="settings.php"><i title="settings" class="fa fa-cog fa-2x"></i></a>  
          <a href="inc/logout.inc.php"><i title="logout" class="fa fa-sign-out fa-2x"></i></a>
          </ul>         
          <div class="header-right">';   
         }     
         ?></div>   
        </nav>  
    </header>


