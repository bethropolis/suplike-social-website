<?php
require '../r.php';
// search for posts and users
// Path: api\v1\search\index.php
// Compare this snippet from /search.php:

if(!isset($_GET['q'])){
    die(json_encode(['error'=>'no query specified']));
}

$search_key = $_GET['q'];
$arr = [];
if (!strlen($search_key) > 0) {
   die(json_encode(['error'=>'search query cannot be empty']));
}
$query = "SELECT * FROM `users` WHERE `usersFirstname` LIKE '%$search_key%' OR `usersSecondname` LIKE '%$search_key%'";
$result = $conn->query($query);
$i = 0;
$arr = [];
while ($row = mysqli_fetch_assoc($result)) {
    $arr['results'][] = $row;
}
if (isset($_GET['addPosts'])) {
    $query = "SELECT * FROM `posts` WHERE `post_text` LIKE '%$search_key%'";
    $result = $conn->query($query);
    while ($row = mysqli_fetch_assoc($result)) {
        $arr['posts'][] = $row;
    }
}
if(isset($_GET['addUsers'])){
    $query = "SELECT * FROM `users` WHERE `usersFirstname` LIKE '%$search_key%' OR `usersSecondname` LIKE '%$search_key%'";
    $result = $conn->query($query);
    while ($row = mysqli_fetch_assoc($result)) {
        $arr['users'][] = $row;
    }
}
print_r(json_encode($arr));