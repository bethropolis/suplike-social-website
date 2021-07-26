<?php
include '../../inc/dbh.inc.php';
if (!isset($_GET['id'])) {
    echo "<h1>comment could not be found. go <a href='./'>back</a></h1>";
    die();
}
$post_id = $_GET['id'];

$sql = "SELECT * FROM `comments` WHERE `comments`.`post_id` ='$post_id'";
$result = $conn->query($sql);



while ($row = $result->fetch_assoc()) {
 if ($row) {
 echo       '<div class="comment">'.
            '<a href="./profile.php?id='.$row["user_token"].'">'.
            '<h5>.'.$row['user'].'</h5>'. 
            '</a>'. 
            '<p>'.$row['comment'].'</p> '.
            '<div class="comment-action">'.
            '<ul>'.
            ' <li><a href="#">delete</a></li>'.
            '<li><a href="#">report</a></li>'.
            '</ul>'.
            ' </div>'.
            '</div>'; 
    }
}
