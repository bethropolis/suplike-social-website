<?php
require_once 'inc/dbh.inc.php'; 

session_start(); 
if (!isset($_SESSION['userId'])) {
  header('Location: ./login.php'); 
  exit();   
}    
 if (isset($_GET['id'])){ 
  $to = $_GET['id'];
  $query = "SELECT * FROM `users` where `idusers`=".$to; 
  $result = mysqli_query($conn,$query)->fetch_assoc();
} 

$query = "SELECT `uidusers`,`usersFirstname`,`usersSecondname` FROM `users`"; 
$users = mysqli_query($conn,$query);
   
?>  
<!DOCTYPE html>
<html lang="en"> 
<head>  
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>message</title>
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="stylesheet" href="css/chat.css">   
  <script type="text/javascript" src="js/jquery.js"></script>
<script >
   var from = null, start = 0, url = 'http://localhost/files/php%20db%20test/inc/message.inc.php';
   var to =  "<?= $result['uidusers']?>", from = "<?= $_SESSION['userUid']?>" ;  
   $(document).ready(function() {   
    load();   
      $('form').submit(function (e) { 
        $.post(url, {
          message: $('#message').val(),  
          from: from,
          to: to  
        }); 
         
        $('#message').val(''); 
        return false;  
      }); 
   });

 
function load(){  
    $.get(url + '?start='+start+"&to="+to+"&from="+from, function(result){
      if (result.items) { 
        result.items.forEach(item =>{ 
         start = item.id;                 
          $('.messages').append(renderMessage(item));   
        });
        $('.messages').animate({scrollTop: $('.messages')[0].scrollHeight});
      } 
     load();  
    })
   }
  function renderMessage(item){ 
  let time = new Date(item.time); 
  let divide = 'msg msg-to';
  time = `${time.getHours()}:${time.getMinutes() < 10? '0': ''}${time.getMinutes()}`; 
   if(item.who_from === from){
     divide = 'msg msg-from'; 
   }  
    return '<div class="'+divide+'"><p>'+item.who_from+'</p>'+item.message+'<span>'+time+'</span></div>'; 
  }     
</script>   
</head>
<body>
    <nav>   
      <img src="img/logo.png" alt="logo" style="width:35px; height: 35px;">
      <ul>
        <a href="inc/logout.inc.php"><li>logout</li></a> 
      </ul>
    </nav>
  <div class="messages"></div>
    <form action="">      
      <input type="text" id="message" autofocus autocomplete="false" placeholder="enter message..">
      <input type="submit" value="send">
    </form>
    <?php require 'footer.php'; ?> 
</body>
</html>