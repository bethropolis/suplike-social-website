<?php

require "header.php";
if (isset($_GET['error'])) {
  echo '<div class="alert alert-danger text-center" role="alert">';
  if ($_GET['error'] == 'emptyfields') {
    echo '<h5 > enter input on all fields</h5>';
  }
  if ($_GET['error'] == 'sqlerror') {
    echo '<h5>there is a server error. please contact admin</h5>';
  }
  if ($_GET['error'] == 'invalidmail') {
    echo '<h5>not a  valid email</h5>';
  }
  if ($_GET['error'] == 'invaliduid') {
    echo '<h5>not a  valid username</h5>';
  }
  if ($_GET['error'] == 'emailtaken') {
    echo '<h5>the email is already in use</h5>';
  }  
 if ($_GET['error'] == 'usertaken') {
    echo '<h5>the username is already in use</h5>';
  }  



  echo '</div>';
}

$mail = $_GET['mail'] ?? null;
$username = $_GET['uid'] ?? null;

?>
<main>
  <div class="center  w-100" style=" max-width: 580px !important;margin: 0 auto;">
    <h1 class="co">Sign up</h1>
    <form class="form mx-auto co bga" action="inc/signup.inc.php" method="post" enctype="multipart/form-data">
      <div class="form-group">
      <input type="text" id="uid" name="uid" value="<?= $username ?>" class="w-100 text-dark sbutton" title="enter your username" placeholder="username..." autofocus required><br />
      <input type="email" id="mail" name="mail" value="<?= $mail ?>"  class="w-100 text-dark sbutton" title="enter your email" placeholder="email..." required><br />
      <input type="password" id="pwd" name="pwd" class="w-100 text-dark sbutton" title="enter your password" placeholder="password..." pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,}" oninvalid="this.setCustomValidity('password should be 6 characters long and contain at least one number, one uppercase and one lowercase letter')" oninput="setCustomValidity('')" required><br />
      <button type="submit" class="login-btn my-1 w-100 bg btn" style="font-size: 1.2em;padding:0.7em; border-radius: 1.5em;" name="signup-submit">Signup</button><br />
    </form>
    <h5 class="my-1 co">already have an account?<a href="./login.php" style="color: var(--ac);">login</a></h5>
  </div>
</main>
<?php
require "footer.php"
?>