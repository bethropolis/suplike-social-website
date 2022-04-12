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
$query = "SELECT * FROM `users` WHERE `usersFirstname` LIKE '%$search_key%' OR `usersSecondname` LIKE '%$search_key%'";
$result = $conn->query($query);
$i = 0;
$arr = [];
while ($row = mysqli_fetch_assoc($result)) {
    $arr['results'][] = $row;
}
$arr['code'] = 1;
$arr['success'] = true;
print_r(json_encode($arr));

