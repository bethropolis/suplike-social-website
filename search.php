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
    if (!empty($searchKeyword)) {
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
<style>
.main {
    width: 84%;
    margin: 50px auto;
}

/* Bootstrap 3 text input with search icon */

.has-search .form-control-feedback {
    position: relative;
    right: initial;
    left: 12px;
    color: #ccc;
    top: 42px;
}

.has-search .form-control {
    height: 3.2em;
    padding-right: 12px;
    padding-left: 34px;
    width: 100%;
}
p.s{
text-align: center;
    font-size: larger;
    color: #999999;
    font-family: open sans,sans-serif;

}

.list {
  border-radius: 2px;
  list-style: none;
  padding: 10px 20px;
}
.list-item {
  display: flex;
  margin: 10px;
  padding-bottom: 5px;
  padding-top: 5px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}
.list-item:last-child {
  border-bottom: none;
}
.list-item-image {
  border-radius: 50%;
  width: 64px;
  height: 64px;

}
.list-item-content {
  margin-left: 20px;
}
.list-item-content h4, .list-item-content p {
  margin: 0;
}
.list-item-content h4 {
  margin-top: 10px;
  font-size: 18px;
}
.list-item-content p {
  margin-top: 5px;
  color: #aaa;
}
.list-item button{
  outline: none;
  border: none;
  margin-left: auto;
  width: 5.2em;
}
button.sbutton.follow-btn {
  width: fit-content;
  font-size: .9em;
  border-radius: .5rem;
  background-color: var(--ho);
  color: var(--icon-light);
  height: 3.0rem;
  padding .1em 1em
}
</style>

<div class="row mob-m-0">
            <div class="col-sm-3 nav-hide sidebar-sticky pt-3">
             <?php
               require "./template/nav.php";
             ?>
            </div>

            <div class="col-sm-9">

<!-- Search form -->
<form method="get" class="mx-auto my-4" action="">
<div class="main">
  <!-- Actual search box -->
  <div class="form-group has-feedback has-search">
    <span class="fa fa-search form-control-feedback"></span>
    <input type="text" class="form-control" name="q"  value="<?php echo $searchKeyword; ?>" placeholder="Search users...">
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
            $query = "SELECT * FROM `following` WHERE user=" . $id . " AND `following`=" . $row['idusers'] . "";
            $answer = $conn->query($query)->fetch_assoc();
            if (!is_null($answer)) {
                $follow = 'following';
            }
        }

?>

       
    <li ng-repeat="user in ctrl.users" class="list-item">
        <a href="profile.php?id=<?= $un_ravel->_queryUser($row['idusers'], 4) ?>" class="prof-link co">
      <div>
        <img src="./img/<?php if (!is_null($row['profile_picture'])) {
                                                                        echo  $row['profile_picture'];
                                                                    } else {
                                                                        echo 'M.jpg"';
                                                                    } ?>"   class="list-item-image">
      </div></a>
      <div class="list-item-content">
        <h4><?php echo $title; ?></h4>
        <p><?php
                if(!empty($contnet)){
                    echo "@".$contnet;
                }else{
                    echo 'No Name';
                }
                 ?></p>
      </div>
      <button id="<?= $un_ravel->_queryUser($row['idusers'], 1) ?>" class="sbutton follow-btn"><span><?= $follow ?></span> <i class="fa fa-user-plus" aria-hidden="true"></i></button>
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
        var count = parseInt(localStorage.getItem('count')) || 0;

        $(".follow-btn").click(function() {
            console.log("all set")
            count = count + 1;
            localStorage.setItem('count', count)
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