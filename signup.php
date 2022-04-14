<?php

require "header.php";


?>

<main>
  <div class="center  w-100">
    <h1>sign up</h1>
    <form class="form mx-auto" action="inc/signup.inc.php" method="post" enctype="multipart/form-data" style="background: white !important;">

      <label for="uid" class="w-100 text-left" style="font-size: 1.1em">username:</label><br />
      <input type="text" id="uid" name="uid" class="w-100" title="enter your username" placeholder="username..." style="font-size: 1.2em;padding:0.8em" autofocus required><br />
      <label for="mail" class="w-100 text-left" style="font-size: 1.1em">email:</label><br />
      <input type="email" id="mail" name="mail" class="w-100" title="enter your email" placeholder="email..." style="font-size: 1.2em;padding:0.8em" required><br />
      <label for="fn" class="w-100 text-left" style="font-size: 1.1em">firstname:</label><br />
      <input type="text" id="fn" name="firstname" class="w-100" title="enter your firstname" placeholder="firstname.." style="font-size: 1.2em;padding:0.8em"><br />
      <label for="ln" class="w-100 text-left" style="font-size: 1.1em">lastname:</label><br />
      <input type="text" id="ln" name="lastname" class="w-100" title="enter your  lastname" placeholder="lastname..." style="font-size: 1.2em;padding:0.8em"><br />
      <label for="pwd" class="w-100 text-left" style="font-size: 1.1em">pwd:</label><br />
      <input type="password" id="pwd" name="pwd" class="w-100" title="enter your password" placeholder="password..." style="font-size: 1.2em;padding:0.8em" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" oninvalid="this.setCustomValidity('password should be 6 characters long and contain at least one number, one uppercase and one lowercase letter')" oninput="setCustomValidity('')" required><br />
      <label for="pwdR" class="w-100 text-left" style="font-size: 1.1em">pwdR:</label><br />
      <input type="password" id="pwdR" name="pwd-repeat" class="w-100" title="repeat password" placeholder="repeat-password..." style="font-size: 1.2em;padding:0.8em" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" oninvalid="this.setCustomValidity('password should be 6 characters long and contain at least one number, one uppercase and one lowercase letter')" oninput="setCustomValidity('')" required><br />
      <label for="age" class="w-100 text-left" style="font-size: 1.1em">birth date:</label><br />
      <input type="date" id="age" name="age" title="enter your birthday"><br /><br />
      <button type="submit" class="login-btn my-1 w-100 bg btn" style="font-size: 1.2em;padding:0.7em; border-radius: 1.5em;" name="signup-submit">signup</button><br />
    </form>
    <h5 class="my-1">already have an account?<a href="./login.php" style="color: var(--ac);">login</a></h5>
  </div>
</main>
<?php
// password pattern validation (at least one number, one lowercase and one uppercase letter, at least 6 characters)
// $pattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{6,}$/';

require "footer.php"
?>