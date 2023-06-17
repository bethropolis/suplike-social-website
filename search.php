<?php

require 'inc/dbh.inc.php';
require 'header.php';
require 'mobile.php';
include_once 'inc/Auth/auth.php';

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$id = isset($_SESSION['userId']) ? $_SESSION['userId'] : 0;
$t = isset($_GET['token']) ? $_GET['token'] : null;

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  echo '<div data-show="true" class="alert alert-info text-center" role="alert">';
  echo '<h5>Please follow around 5 people to get started.</h5>';
  echo '</div>';
  echo '<a href="./login.php?fol" ><button class="login-btn left-2 w-50 btn btn-success mx-auto" style="display: none; position: fixed;left: 23%;bottom: 1rem;z-index: 100;">Now Login</button></a>';
}
$result = [];
// If the search form is submitted
$searchKeyword = $whrSQL = '';
if (isset($_GET['q'])) {
  $searchKeyword = $_GET['q'];
  if (!empty($searchKeyword) && !isset($_GET['post'])) {
    $sql = "SELECT * FROM users WHERE `uidusers` LIKE ? OR `usersFirstname` LIKE ? LIMIT 15";
    $stmt = $conn->prepare($sql);
    $searchKeywordParam = '%' . $searchKeyword . '%';
    $stmt->bind_param("ss", $searchKeywordParam, $searchKeywordParam);
    $stmt->execute();
    $result = $stmt->get_result();
    // Get matched records from the database
    // Highlight words in text
    function highlightWords($text, $word)
    {
      if (!empty($text)) {
        return str_ireplace($word, '<span class="highlight">' . $word . '</span>', $text);
      }
      return $text;
    }
  }
}
# code...


?>
,
<link rel="stylesheet" href="css/search.css">
<div class="row mob-m-0">
  <div class="col-sm-3 nav-hide sidebar-sticky pt-3">
    <?php
    require "./template/nav.php";
    ?>
  </div>

  <div class="col-sm-9 p-0">

    <!-- Search form -->
    <form method="get" class="mx-auto my-4" action="">
      <div class="main">
        <!-- Actual search box -->
        <div class="form-group has-feedback has-search">
          <span class="fa fa-search form-control-feedback"></span>
          <input type="text" class="form-control" name="q" value="<?php echo $searchKeyword; ?>"
            placeholder="Search users...">
        </div>

      </div>
    </form>
    <ul class="list">
      <?php

      if ($result) {
        while ($row = $result->fetch_assoc()) {
          $title = !empty($searchKeyword) ? highlightWords($row['uidusers'], $searchKeyword) : $row['uidusers'];
          $contnet = !empty($searchKeyword) ? highlightWords($row['usersFirstname'], $searchKeyword) : $row['usersFirstname'];
          if (!empty($searchKeyword)) {
            $follow = 'follow';
            $icon = 'fas fa-user-plus';
            $query = "SELECT * FROM `following` WHERE user=" . $id . " AND `following`=" . $row['idusers'] . "";
            $answer = $conn->query($query)->fetch_assoc();
            if (!is_null($answer)) {
              $follow = 'following';
              $icon = 'fas fa-user';
            }
          }

          ?>


          <li ng-repeat="user in ctrl.users" class="list-item">
            <a href="profile.php?id=<?= $un_ravel->_queryUser($row['idusers'], 4) ?>" class="prof-link co">
              <div>
                <img src="./img/<?php if (!is_null($row['profile_picture'])) {
                  echo $row['profile_picture'];
                } else {
                  echo 'M.jpg"';
                } ?>" class="list-item-image">
              </div>
            </a>
            <div class="list-item-content">
              <a href="profile.php?id=<?= $un_ravel->_queryUser($row['idusers'], 4) ?>" class="prof-link co">
              <h4>
                <?php echo $title; ?>
              </h4>
              </a>
              <p>
                <?php
                if (!empty($contnet)) {
                  echo "@" . $contnet;
                } else {
                  echo 'No Name';
                }
                ?>
              </p>
            </div>
            <button id="<?= $un_ravel->_queryUser($row['idusers'], 1) ?>" class="sbutton follow-btn"><span>
                <?= $follow ?>
              </span> <i class="fas <?= $icon ?> text-white " aria-hidden="true"></i></button>
          </li>



        <?php }
      } else { ?>
        <p class="s">No user(s) found...</p>
      <?php }




      ?>
    </ul>

  </div>
</div>
<br><br><br>
<div class="mobile nav-show">
  <br><br><br>
</div>
<?php require 'footer.php'; ?>
<script>
  active_page(2);
  follow("<?= $t ?>");
  if (document.querySelector("[data-show]")) {
    var count = parseInt(sessionStorage.getItem('count')) || 0;

    $(".follow-btn").click(function () {
      count = count + 1;
      sessionStorage.setItem('count', count)
      lookUp()
    })

    function lookUp() {
      if (count >= 5) {
        $(".login-btn").show();
      } else if ($('.follow-btn').length == count) {
        $(".login-btn").show();
      }
    }
  }
</script>