<?php

require 'inc/dbh.inc.php';
require 'inc/Auth/auth.php';
require 'header.php';

if (!isset($_SESSION['token'])) {
  header('Location: ./login.php');
  exit();
}


$profile = isset($_GET['id']) ? $_GET['id'] : $_SESSION['token'];
$follow = 'follow';
$query = "SELECT * FROM `following` WHERE user=" . $un_ravel->_getUser($_SESSION['token']) . " AND `following`=" . $un_ravel->_getUser($profile);
$result = $conn->query($query)->fetch_assoc();
if (!is_null($result)) {
  $follow = 'following';
}

$isAdmin = $un_ravel->_isAdmin($profile);

?>
<link rel="stylesheet" href="css/post.min.css">
<script type="text/javascript" src="./lib/jquery/jquery.js"></script>
<script src="./lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="js/index.min.js?v1.3"></script>
<script>
  profile = "<?= $profile ?>";
  // took me long to debug but it is here where the post are rendered
  profile_request(profile);
</script>
<?php 
// check if email is verified or not, if not, show a message to the user WITH a button to send a verification email
if ($un_ravel->_isEmail_verified($profile)) {
  echo '<div id="email-v" class="alert row align-items-center alert-warning pt-2 pb-0 my-0" role="alert">
  <p class="col-9">Please verify your email.</p>
  <p class="col-3">
    <a href="#sent" class="btn btn-light bg" id="send-v">send</a>
  </p>
</div>';
}

?>

<!-- end of replacement -->
<div class="card m-auto bg-light p-2  text-center shadow">
  <div class="col-sm-12">
    <div class="card-img-top col-xs-6 col-md-12">
      <img  src="img/M.jpg" class="profile-pic img-profile rounded-circle p-1 shadow-lg" alt="profile picture" style="width: 150px;background: linear-gradient(270deg,#57d2ff,#b260ff);" />
    </div>
    <div class="card-body mx-0 px-0  d-flex flex-column">
      <h5 class="card-title " id="profile-name"></h5>
      <div class="col-12 text-center" style="display: inline-flex;justify-content: center;">
        <h5 class="text-center userName text-muted"></h5>
        <?php
        if ($isAdmin == 1) {
          echo '<i class="fa pt-2 ml-1 fa-check-circle" style="color: var(--ho)"></i>';
        }
        ?>
      </div>
      <div class="d-flex justify-content-center align-items-center" style="justify-content: center;">
        <div class="d-flex justify-content-center align-items-center m-1">
          <small class="mx-1 h6"style="font-size: clamp(.9rem, .5vw, 0.6rem);">posts: <span id="posts">...</span></small>
        </div>
        <div class="d-flex justify-content-center align-items-center m-1">
          <small class="mx-1 h6"style="font-size: clamp(.9rem, .5vw, 0.6rem);">followers: <span id="following"">...</span></small>
        </div>
        <div class="d-flex justify-content-center align-items-center m-1">
          <small class="mx-1 h6" style="font-size: clamp(.9rem, .5vw, 0.6rem);">following: <span id="followers">...</span> </small>
        </div>
      </div>
      <div class="d-flex justify-content-center align-items-center">
        <button class="shadow bg follow-btn btn m-2" aria-pressed="true">
         <span><?= $follow ?></span><i class="fa no-h  fa-user-plus ml-2" aria-pressed="true"></i>
        </button>
        <a href="message.php?id=" class="message-btn">
          <button class="shadow  btn btn-dark m-2">
            message<i class="fa no-h  fa-paper-plane ml-2"></i>
          </button>
        </a>

      </div>
      <div class="bio">
      </div>
    </div>
  </div>
</div>
<noscript>
  <div class="alert alert-danger">Please enable javascript to view this page</div>
</noscript>
<h3 style="margin: 18px;" class="co">posts</h3>
<div id="main-post"></div>
<br><br><br>
<div class="mobile nav-show">
<br><br><br>
</div>
<?php 
require 'mobile.php';
?>
<script>
  // check local storage for  send_click and if it is true, hide alert
  if (sessionStorage.getItem('send_click') == 'true') {
    $('#email-v').hide();
  }
  $('#send-v').click(function() {
    $.get('inc/send_verification.php?id='+'<?= $_SESSION['token']?>', function(data) {
      if (data) {
        sessionStorage.setItem('send_click', 'true');
        $('#email-v').hide();
      }
    });
  }); 

  // jquery document ready
  $(document).ready(function() {
    active_page(4);
});
</script>