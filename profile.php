<?php

require 'inc/dbh.inc.php' ;
require 'inc/Auth/auth.php';
require 'header.php';

if (!isset($_SESSION['token'])) { 
  header('Location: ./login.php');
  exit();
}


$profile = isset($_GET['id']) ? $_GET['id'] : $_SESSION['token'];
$follow = 'follow';
$query = "SELECT * FROM `following` WHERE user=" . $un_ravel->_getUser($_SESSION['token']) . " AND `following`=".$un_ravel->_getUser($profile);    
$result = $conn->query($query)->fetch_assoc();
if (!is_null($result)) { 
  $follow = 'following';
}

?> 
<link rel="stylesheet" href="css/post.css">
<script type="text/javascript" src="./lib/jquery/jquery.js"></script>  
<script src="./lib/bootstrap/js/bootstrap.bundle.min.js"></script>        
<script type="text/javascript" src="js/index.js?v1.2"></script>   
<script>        
   profile = "<?= $profile ?>";  
   // took me long to debug but it is here where the post are rendered
   profile_request(profile);        
</script> 

<div class="col-sm-12 sidebar-sticky pt-3" style="max-width: 440px; margin: 0 auto">
    <div class="card card-profile text-center profile-card shadow" style="width: 94%;">
        <a href="#">
            <img class="profile-pic shadow-sm" src="img/M.jpg" title="" alt="profile picture" style="width: 70px;height: 70px; border-radius: 50%;">
        </a>
        <h4 id="profile-name"></h4>
        <h5 class="text-center userName"></h5>
        <p class="bio"></p>
        <ul class="row p-1 my-2 w100">
            <li id="following" class="col-6 my-2 ">following:</li>
            <li id="followers" class="col-6 my-2">followers:</li>
        </ul>
        <ul class="profile-opt">
          <a href="" class="message-btn"><button class="btn bg">message</button></a>  
              <button class="btn bg follow-btn"><?=$follow?></button>
        </ul>
    </div>
</div>
 
<h3 style="margin: 18px;">posts</h3>
<div id="main-post">

</div>

