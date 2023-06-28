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
  require_once 'template/alert.php';
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
    <h1 class="co py-2">Login</h1>
    <form class="form mx-auto co bga" action="inc/login.inc.php" method="post">
      <div class="form-label-group">
        <label for="user" class="w-100 text-left">username or email</label>
        <input type="text" id="user" class="w-100 text-dark form-control" name="mailuid" placeholder="username or email..." autocomplete="false">
      </div>
      <div class="form-label-group">
        <label for="user" class="w-100 text-left">password</label>
        <input type="password" id="pwd" class="w-100 text-dark form-control" name="pwd" placeholder="password...">
      </div>

      <div class="group text-left cookie mb-0" style=" background-color: unset;">
        <input id="check" type="checkbox" name="remember" class="check checkbox form-check-input">
        <label for="check" class="co"> Keep me Signed in</label>
      </div>

      <button class="login-btn w-100 bg btn" type="submit" name="login-submit" style="font-size: 1.2em;padding:0.5em; border-radius: 1.5em;">Login</button>
    </form>
    <p class="my-2 co">don't have an account?<a href="./signup.php" style="color: var(--ac);"> signup</a> or<a href="./forgot_password.php" style="color: var(--ac);"> forgot password</a> </p>
  </div>
</main>
<script>
  sessionStorage.clear()
  sessionStorage.setItem('load', true)
</script>


<?php
require "footer.php"
?>