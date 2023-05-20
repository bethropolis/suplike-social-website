<?php
require '../r.php';

// Get the ID from the request body
if(isset($_POST["post_id"])){
$id = $_POST['post_id'];
$userId = $_POST['user_token'];
$user = $un_ravel->_getUser($userId);
$sql = "SELECT * FROM `posts` WHERE `post_id` = '$id'";
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);

// Check if the post exists
if (empty($row)) {
    $output = array(
        "code" => 1,
        "msg" => "Post does not exist",
        "type" => "error"
    );
    echo json_encode($output);
    exit();
}

// Check if the user owns the post
if ($row['userid'] == $user) {
    $output = array(
        "code" => 2,
        "msg" => "Cannot repost your own post",
        "type" => "error"
    );
    echo json_encode($output);
    exit();
}

// Check if the user has already reposted the post
$sql = "SELECT `id` FROM `posts` WHERE `repost` = '$id' AND `userid`='$user'";
$result = $conn->query($sql);

if (!empty(mysqli_fetch_assoc($result))) {
    $output = array(
        "code" => 3,
        "msg" => "Post has already been reposted",
        "type" => "error"
    );
    echo json_encode($output);
    exit();
}

$d = new DateTime('now', $timeZone);
$date = $d->format('j M');
$day = $d->format('l');
$image = $row['image'];
$image_text = $row['image_text'];
$bin = bin2hex(openssl_random_pseudo_bytes(4));

if ($row['type'] == 'txt') {
    $type = "txt";
    $sql = "INSERT INTO posts (`post_id`,`image_text`, `userid`,`repost`,`type` ,`date_posted`, `day`) VALUES ('$bin', '$image_text', $user,'$id', '$type', '$date', '$day')";
    $conn->query($sql);
}

if ($row['type'] == 'img') {
    $type = "img";
    $sql = "INSERT INTO posts (`post_id`,`image`, `image_text`,`repost`, `type`, `userid`, `date_posted`, `day`) VALUES ('$bin','$image','$image_text','$id','$type',$user,'$date','$day')";
    $conn->query($sql);
}

// Return success message
$output = array(
    "code" => 0,
    "msg" => "Post has been reposted",
    "type" => "success"
);
echo json_encode($output);
exit();
}