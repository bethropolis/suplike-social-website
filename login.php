<?php

require "header.php" ;

if (isset($_SESSION['userId'])) {
	header('Location: ./index.php?session=alrdylogdin') ;  
}
?> 
 <main>
   <div class="form"> 

    <h1>login</h1>

        <form class="" action="inc/login.inc.php" method="post"> 
        <input type="text" name="mailuid" placeholder="username or email..." autofocus>
        <input type="password" name="pwd" placeholder="password...">
        <button class="login-btn btn" style="height: 45px; width: 140px;" type="submit" name="login-submit">login</button>
      </form>don't have an account?<a href="signup.php">signup</a> 
   </div>
 </main>
 <?php

require "footer.php"
 ?>





