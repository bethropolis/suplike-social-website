<?php

require "header.php";


?>

<main>
  <div class="center">
    <h1>sign up</h1>
    <form class="form mx-auto" action="inc/signup.inc.php" method="post" enctype="multipart/form-data">
      <label for="uid">username:</label><br />
      <input type="text" id="uid" name="uid" title="enter your username" placeholder="username..." autofocus required><br />
      <label for="mail">email:</label><br />
      <input type="email" id="mail" name="mail" title="enter your email" placeholder="email..." required><br />
      <label for="fn">firstname:</label><br />
      <input type="text" id="fn" name="firstname" title="enter your firstname" placeholder="firstname.."><br />
      <label for="fn">lastname:</label><br />
      <input type="text" id="ln" name="lastname" title="enter your  lastname" placeholder="lastname..."><br />
      <label for="fn">pwd:</label><br />
      <input type="password" id="pwd" name="pwd" title="enter your password" placeholder="password..." required><br />
      <label for="fn">pwdR:</label><br />
      <input type="password" id="pwdR" name="pwd-repeat" title="repeat password" placeholder="repeat-password..."><br />
      <label for="fn">birth date:</label><br />
      <input type="date" id="age" name="age" title="enter your birthday"><br /><br />
      <button type="submit" class="btn bg" style="height: 45px; width: 140px;" name="signup-submit">signup</button><br />
    </form>
    <h5 class="my-1">already have an account?<a href="./login.php" style="color: var(--ac);">login</a></h5>
  </div>
</main>
<?php

require "footer.php"
?>