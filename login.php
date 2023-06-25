<?php
session_start();
  if (isset($_SESSION['token'])) {
    header("Location: home?alrdylogdin");
    exit();
}
session_destroy();
require "header.php";
?>
<main>
  <?php
  if (isset($_GET['error'])) {
    echo '<div class="alert alert-danger text-center" role="alert">';
    if ($_GET['error'] == 'notset') {
      echo '<h5>the database has not been configured <a href="./inc/setup/"><button class="btn mx-2 btn-info">setup</button> </a></h5> ';
    }
    if ($_GET['error'] == 'emptyfields') {
      echo '<h5 > enter input on all fields</h5>';
    }
    if ($_GET['error'] == 'sqlerror') {
      echo '<h5>there is a server error. please contact admin</h5>';
    }
    if ($_GET['error'] == 'disabled') {
      echo '<h5>account has been disabled, contact admin.</h5>';
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
  if (isset($_GET['acc_deleted'])) {
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
  // if he is loged in, he can't login again display a message that he is loged in
  if (isset($_GET['alrdylogdin'])) {
    echo '<div class="alert alert-danger text-center" role="alert">';
    echo '<h5>You are already loged in</h5>';
    echo '</div>';
    die();
  }

  ?>
  <div class="mobile nav-show">
    <br><br><br>
  </div>
  <div class="center w-100">
    <h1 class="co">login</h1>
    <form class="form mx-auto co bga" action="inc/login.inc.php" method="post">
        <input type="text" id="user" class="w-100 text-dark" name="mailuid" placeholder="username or email..." autocomplete="false" style="font-size: 1.2em;padding:0.8em"> <br /><br />
        <input type="password" id="pwd" class="w-100 text-dark" name="pwd" placeholder="password..." style="font-size: 1.2em;padding:0.8em">
      <br />

      <div class="group text-left cookie" style=" background-color: unset;"> <input id="check" type="checkbox" name="remember" class="check checkbox form-check-input"> <label for="check" class="co"><span class="icon"></span> Keep me Signed in</label> </div>
      <button class="login-btn my-1 w-100 bg btn" type="submit" name="login-submit" style="font-size: 1.2em;padding:0.7em; border-radius: 1.5em;">Login</button>
    </form>
    <h5 class="my-1 co" style="font-size: 1.44em">don't have an account?<a href="./signup.php" style="color: var(--ac);"> signup</a> </h5>
    <h5 class="my-1 co" style="font-size: 1.44em">or<br/><a href="./forgot_password.php" style="color: var(--ac);"> forgot password</a> </h5>
  </div>
</main>
<script>
  sessionStorage.clear()
  sessionStorage.setItem('load', true)
</script>


<?php
require "footer.php"
?>