<?php 
require '../../inc/dbh.inc.php';
session_start();
if(!isset($_SESSION['userId'])) {
    header("Location: ../../index.php?error=notloggedin");
    exit();
}
$id = $_SESSION['userId'];
$sql ="SELECT `key` FROM `api` WHERE `user` = $id";
if(mysqli_num_rows(mysqli_query($conn, $sql)) > 0) {
    $row = mysqli_fetch_assoc(mysqli_query($conn, $sql));
    $token = $row['key'];
} else {
    $token = 'generate new api key';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Developer Page</title>
  <link rel="stylesheet" href="../../lib/bootstrap/css/bootstrap.min.css">
  <script type="text/javascript" src="../../lib/jquery/jquery.js"></script>
</head>
<body>
<!-- back to home ancor tag -->
<a href="../../index.php" class=""><-- Back Home</a>
<div class="container">
   <h2>Developer page</h2>
   <br><br>
   <div class="center row">
      <label for="api" class="col-2"><h4>Api key</h4> </label>
      <input type="text" value="<?= $token ?>" class="col-8 w-100" id="api">
   </div><br>
   <div class="center mx-auto w-50">
    <button class="generate btn btn-primary" title="generate new api key">generate</button>    
   </div>
</div>

<article class="w-50 mx-auto">
    <h2>Developer page</h2>
    <p>
        This is the developer page.
    </p>
    <p class="flow-text">
        the documentation for the API is available <a href="http://suplike.docs.apiary.io/" target="_blank">here</a>
        <!-- warning about api key -->
        <br><br>
        <strong>Warning:</strong>
        <br>
        <strong>1. </strong>
        <span>
            You can only change your token once an hour.
        </span>
        <br>
        <strong>2. </strong>
        <span>
            Your usage of the api should follow the suplike guide lines.
        </span>
        <br>
    </p>
</article>
<script>
    $(document).ready(function(){
        $('.generate').click(function(){
            $.ajax({
                url: '../../inc/Auth/a.php',
                type: 'GET',
                success: function(data){
                    if(data.code == 1) {
                        $('#api').val(data.token);
                    } else {
                        alert(data.msg);
                    }
                },
                error: function(data){
                    console.log(data);
                }
            });
        });
    });
</script>