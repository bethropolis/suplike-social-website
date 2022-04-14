<?php

require "header.php";

if (isset($_SESSION['userId'])) {
  header('Location: ./index.php?session=alrdylogdin');
}
?>
<main>
  <?php
  if (isset($_GET['error'])) {
    echo '<div class="alert alert-danger text-center" role="alert">';
    if ($_GET['error'] == 'notset') {
      echo '<h5><a href="./inc/dbh.inc.php">setup Database config</a></h5>';
    }
    if ($_GET['error'] == 'emptyfields') {
      echo '<h5 > enter input on all fields</h5>';
    }
    if ($_GET['error'] == 'sqlerror') {
      echo '<h5>there is a server error. please contact admin</h5>';
    }
    if ($_GET['error'] == 'wrongpwd') {
      echo '<h5>wrong password</h5>';
    }
    echo '</div>';
  }
  if (isset($_GET['dbSet'])) {
    echo '<div class="alert alert-success text-center" role="alert">';
    echo '<h5>Database configurations have been set</h5>'; 
    echo '</div>';
  }
 if (isset($_GET['acc'])) {
    echo '<div class="alert alert-info text-center" role="alert">';
    echo '<h5>Your account has been created. Please login</h5>'; 
    echo '</div>';
  }
  if(isset($_GET['acc_deleted'])){
    echo '<div class="alert alert-success text-center" role="alert">';
    echo '<h5>Your account has been deleted</h5>'; 
    echo '</div>';
  }
 if (isset($_GET['fol'])) {
    echo '<div class="alert alert-info text-center" role="alert">';
    echo '<h5>Users you follow were saved please confirm login to continue</h5>'; 
    echo '</div>';
  }
  # nouser 
  if (isset($_GET['noUser'])) {
    echo '<div class="alert alert-danger text-center" role="alert">';
    echo '<h5>User does not exist</h5>'; 
    echo '</div>';
  }
  ?>
  <div class="center w-100">
    <h1>login</h1>
    <form class="form mx-auto" action="inc/login.inc.php" method="post" style="background: white !important;">
      <label for="user" class="w-100 text-left" style="font-size: 1.1em">username or email:</label><br />
      <input type="text" id="user"class="w-100" name="mailuid" 
      placeholder="username or email..." autocomplete="false" style="font-size: 1.2em;padding:0.8em"><br /><br />
      <label for="pwd" class="w-100 text-left" style="font-size: 1.1em">password:</label> <br />
      <input type="password" id="pwd"class="w-100" name="pwd" 
      placeholder="password..." style="font-size: 1.2em;padding:0.8em"> <br /><br />
      <button class="login-btn my-1 w-100 bg btn" type="submit" name="login-submit" style="font-size: 1.2em;padding:0.7em; border-radius: 1.5em;">login</button>
    </form>
    <h5 class="my-1"style="font-size: 1.44em">don't have an account?<a href="./signup.php" style="color: var(--ac);">signup</a> </h5>
  </div>
</main>
<script>
  sessionStorage.clear()
  sessionStorage.setItem('load', true)
</script>


<?php
require "footer.php"
?>