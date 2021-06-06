<?php

require "header.php" ;

if (isset($_SESSION['userId'])) {
	header('Location: ./index.php?session=alrdylogdin') ;  
}
?> 
 <main>
   <div class="center">  
    <h1>login</h1> 
        <form class="form mx-auto" action="inc/login.inc.php" method="post"> 
        <label for="user">username or email:</label><br/>  
        <input type="text" id="user" name="mailuid" placeholder="username or email..." autofocus><br/><br/>
        <label for="pwd">password:</label> <br/>  
        <input type="password" id="pwd" name="pwd" placeholder="password..."> <br/><br/> 
        <button class="login-btn my-1 bg btn" style="height: 45px; width: 140px;" type="submit" name="login-submit">login</button>
      </form><h5 class="my-1">don't have an account?<a href="./signup.php" style="color: var(--ac);">signup</a> </h5> 
   </div>  
 </main>
<script>
   $(".form").on
</script> 


 <?php
require "footer.php"
 ?>







