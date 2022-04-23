<?php

require 'inc/dbh.inc.php';
require 'header.php';
require 'mobile.php';
include_once 'inc/Auth/auth.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

# i am making a search page for the users improve the search page from curren
$id = 0;
$t = null;
if (isset($_SESSION['userId'])) {
    $id = $_SESSION['userId'];
}
if (isset($_GET['token'])) {
    $t = $_GET['token'];
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    echo '<div data-show="true" class="alert alert-info text-center" role="alert">';
    echo '<h5>Please follow around 5 people then you will be redirected to login.</h5>';
    echo '</div>';
    echo '<a href="./login.php?fol" ><button class="login-btn left-2 w-50 btn btn-success mx-auto" style="display: none; position: fixed;left: 23%;bottom: 1rem;z-index: 100;">Now Login</button></a>';
}
$result = [];
// If the search form is submitted
$searchKeyword = $whrSQL = '';
if (isset($_GET['q'])) {
    $searchKeyword = $_GET['q'];
    if (!empty($searchKeyword)) {
        // use prepared statements
        // instead of  $whrSQL = "WHERE `users`.`uidusers` LIKE '%:keyword$%' OR `users`.`usersFirstname` LIKE '%:keyword%' limit 15"; use prepared statements ???
        $whrSQL = "WHERE `users`.`uidusers` LIKE '%$searchKeyword%' OR `users`.`usersFirstname` LIKE '%$searchKeyword%' limit 15";
        $stmt = $conn->prepare("SELECT * FROM users $whrSQL");
        $stmt->execute();
        $result = $stmt->get_result();


        // Get matched records from the database

        // Highlight words in text
        function highlightWords($text, $word)
        {
            return str_ireplace($word, '<span class="highlight">' . $word . '</span>', $text);
        }
    }
}
# code...


?>
<!--<link rel="stylesheet" type="text/css" href="css/search.css?k">  -->


<!-- Search form -->
<form method="get" class="mx-auto my-4" action="">
    <div class="search_input mx-auto row my-2">
        <input type="text" class="search_box mx-1 col-8" name="q" value="<?php echo $searchKeyword; ?>" placeholder="Search by keyword...">
        <button type="submit" class="search_button mx-1 col-3 bg btn">Search</button>
    </div>
</form>

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
        <div class="search-list-item card bg-light my-4 mx-auto shadow py-2 w-75 row">
            <div class="col-md-6 text-left">
                <a href="profile.php?id=<?= $un_ravel->_queryUser($row['idusers'], 4) ?>" class="prof-link co">
                    <h4><?php echo $title; ?></h4>
                </a>
                <p class="text-muted"><?php
                if(!empty($contnet)){
                    echo "@".$contnet;
                }else{
                    echo 'No Name';
                }
                 ?></p>
            </div>
            <div class="col-md-6 text-right pr-4">
                <button id="<?= $un_ravel->_queryUser($row['idusers'], 1) ?>" class="btn col-5 p-2 bg follow-btn"><span><?= $follow ?></span></button>
            </div>
        </div>

    <?php }
} else { ?>
    <p>No user(s) found...</p>
<?php }
?>
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