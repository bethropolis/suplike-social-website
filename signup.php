<?php

require "header.php";
require_once "template/alert.php";
$mail = $_GET['mail'] ?? null;
$username = $_GET['uid'] ?? null;

?>
<main>
  <div class="center  w-100" style=" max-width: 580px !important;margin: 0 auto;">
    <h1 class="co">Sign up</h1>
    <form class="form mx-auto co bga" action="inc/signup.inc.php" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <div class="form-label-group">
          <label for="uid" class="w-100 text-left">username</label>
          <input type="text" id="uid" name="uid" value="<?= $username ?>" class="w-100 text-dark form-control" title="enter your username" placeholder="username..." autofocus required>
        </div>
        <div class="form-label-group">
          <label for="mail" class="w-100 text-left">email</label>
          <input type="email" id="mail" name="mail" value="<?= $mail ?>" class="w-100 text-dark form-control" title="enter your email" placeholder="email..." >
        </div>
        <div class="form-label-group">
          <label for="pwd" class="w-100 text-left">password</label>
          <input type="password" id="pwd" name="pwd" class="w-100 text-dark form-control" title="enter your password" placeholder="password..." pattern="(?=.*\d)(?=.*[a-z]).{4,}" oninvalid="this.setCustomValidity('password should be 6 characters long and contain at least one number, one uppercase and one lowercase letter')" oninput="setCustomValidity('')" required>
        </div>
        <button type="submit" class="login-btn my-1 w-100 bg btn" style="font-size: 1.2em;padding:0.5em; border-radius: 1.5em;" name="signup-submit">Signup</button><br />
      </div>
    </form>
    <p class="my-2 co">already have an account? <a href="./login.php" style="color: var(--ac);">login</a></p>
  </div>
</main>
<?php
require "footer.php"
?>