<?php
include './inc/dbh.inc.php'; 
if(!isset($_GET['id'])){
    echo "<h1>comment could not be found. go <a href='./'>back</a></h1>"; 
    die(); 
}
$post_id = $_GET['id'];  

$sql = ""


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./lib/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="./lib/font-awesome/font-awesome.min.css"> 
    <link rel="stylesheet" href="css/comment.css"> 

</head>

<body>
    <div id="app">
        <nav><a href="./">
                <img title="go to homepage" src="img/logo.png" alt="logo" style="width:35px; height: 35px;">
            </a>
            <div class="nav-content">

                <i @click="goBack" class="fa fas fa-arrow-left toShow fa-2x" v-show="comment != null"></i>
                <a href="./" class="home">
                    <i class="fa fas fa-home fa-2x"></i>
                </a>
                <a href="inc/logout.inc.php" class="log-out">
                    <i class="fa fas fa-sign-out fa-2x"></i>
                </a>
            </div>
        </nav>

</body>

</html>