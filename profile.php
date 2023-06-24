<?php
require 'inc/dbh.inc.php';
require 'inc/Auth/auth.php';
require 'header.php';

$login = true;
if (!isset($_SESSION['token']) && !isset($_GET['id']) && !isset($_COOKIE['token'])) {
  try {
    $login = false;
  } catch (Exception $err) {
    echo 'An error occurred.';
  }
}



if ($login) {
  $profile = isset($_GET['id']) ? $_GET['id'] : $_SESSION['token'];
  $follow = 'follow';
  $usrtk = isset($_SESSION['token']) ? $un_ravel->_getUser($_SESSION['token']) : 133;
  $query = "SELECT * FROM `following` WHERE user=" . $usrtk . " AND `following`=" . $un_ravel->_getUser($profile);
  $result = $conn->query($query)->fetch_assoc();
  if (!is_null($result)) {
    $follow = 'following';
  }
  $usr = $un_ravel->_getUser($profile);
  $isAdmin = $un_ravel->_isAdmin($usr);
  $isBot = $un_ravel->_isBot($usr);
  $un_ravel->_increment_page_visit($usr);
  $user_id = $usrtk;
}
?>
<link rel="stylesheet" href="css/post.css">
<script type="text/javascript" src="./lib/jquery/jquery.js"></script>
<script src="./lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="js/index.js?v1.3"></script>
<script>
  profile = "<?= $profile ?>";
  // took me long to debug but it is here where the post are rendered
  profile_request(profile);
</script>
<?php
// check if email is verified or not, if not, show a message to the user WITH a button to send a verification email


?>

<div class="row mob-m-0">
  <div class="col-sm-3 nav-hide sidebar-sticky pt-3">
    <?php
    require "./template/nav.php";
    ?>
  </div>


  <div class="col-sm-9 p-0">
    <?php

    if ($login) {
    ?>
      <!-- end of replacement -->
      <div class="card m-auto bg-light p-2  text-center shadow">
        <div class="col-sm-12">
          <div class="card-img-top col-xs-6 col-md-12">
            <img src="img/M.jpg" class="profile-pic img-profile rounded-circle p-1 shadow-lg" alt="profile picture" style="width: 150px;height:150px;background: linear-gradient(270deg,#57d2ff,#b260ff);" />
          </div>
          <div class="card-body mx-0 px-0  d-flex flex-column">
            <h5 class="card-title " id="profile-name"></h5>
            <div class="col-12 text-center" style="display: inline-flex;justify-content: center;">
              <h5 class="text-center userName text-muted"></h5>
              <?php
              if ($isAdmin == 1) {
                echo '<i class="fa pt-2 ml-1 fa-check-circle" style="color: var(--ho)" title="this is an admin account"></i>';
              }
              if ($isBot == 1) {
                echo '<i class="fa pt-2 ml-1 fa-robot" style="color: var(--ho)" title="this is a bot account"></i>';
              }
              ?>
            </div>
            <div class="d-flex justify-content-center align-items-center text-muted" style="justify-content: center;">
              <div class="d-flex justify-content-center align-items-center m-1">
                <small class="mx-1 h6" style="font-size: clamp(.9rem, .5vw, 0.6rem);">posts: <span id="posts">...</span></small>
              </div>
              <div class="d-flex justify-content-center align-items-center m-1">
                <small class="mx-1 h6" style="font-size: clamp(.9rem, .5vw, 0.6rem);">following: <span id="following"">...</span></small>
        </div>
        <div class=" d-flex justify-content-center align-items-center m-1">
                    <small class="mx-1 h6" style="font-size: clamp(.9rem, .5vw, 0.6rem);">followers: <span id="followers">...</span> </small>
              </div>
            </div>
            <div class="d-flex justify-content-center align-items-center">
              <button class="shadow-sm bg follow-btn btn m-2 border-0" aria-pressed="true">
                <span>
                  <?= $follow ?>
                </span><i class="fa no-h  fa-user-plus ml-2 icon-light" aria-pressed="true"></i>
              </button>
              <a href="message.php?id=" class="message-btn">
                <button class="shadow-sm btn btn-dark m-2 border-0">
                  message<i class="fa no-h  fa-paper-plane ml-2 icon-light"></i>
                </button>
              </a>

            </div>
            <div class="bio co">
            </div>
          </div>
        </div>
      </div>
      <noscript>
        <div class="alert alert-danger">Please enable javascript to view this page</div>
      </noscript>
      <h3 style="margin: 18px;" class="co">posts</h3>
      <div id="main-post" class="mb-5"></div>
  </div>
<?php
    } else {
?>
  <div class="alert alert-info w-75 text-center mx-auto mt-5">
    <h4>You need to login to access your profile</h4>
  </div>

<?php } ?>
</div>
<br><br><br>
<div class="mobile nav-show">
  <br><br><br>
</div>
<?php
require 'mobile.php';
?>
<script src="./lib/lightbox/js/lightbox.min.js" defer></script>
<script>
  // check local storage for  send_click and if it is true, hide alert
  // jquery document ready
  $(document).ready(function() {
    active_page(4);
  });
</script>