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

  ?>
  <div class="center">
    <h1>login</h1>
    <form class="form mx-auto" action="inc/login.inc.php" method="post">
      <label for="user">username or email:</label><br />
      <input type="text" id="user" name="mailuid" placeholder="username or email..." autocomplete="false"><br /><br />
      <label for="pwd">password:</label> <br />
      <input type="password" id="pwd" name="pwd" placeholder="password..."> <br /><br />
      <button class="login-btn my-1 bg btn" style="height: 55px; width: 150px;" type="submit" name="login-submit">login</button>
    </form>
    <h5 class="my-1">don't have an account?<a href="./signup.php" style="color: var(--ac);">signup</a> </h5>
  </div>
</main>
<script>
  sessionStorage.clear()
  sessionStorage.setItem('load', true)
</script>


<?php
require "footer.php"
?>