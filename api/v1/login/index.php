<?php
require "../r.php";

if (!isset($_POST["usermail"])) {
    $error->err("API", 21, "missing infomation");
    die();
}

if (!isset($_POST["userPass"])) {
    $error->err("API", 21, "password has not been given");
    die();
}

$name = $_POST["usermail"];
$pass = $_POST["userPass"];
$sql = "SELECT * FROM users WHERE uidusers='$name' OR emailusers='$name'";
$stmt = $conn->query($sql);
if (!mysqli_fetch_assoc($stmt)) {
    $error->err("API", 21, "wrong credentials given");
}
$stmt = $conn->query($sql);
$DB = mysqli_fetch_assoc($stmt);
$pwdCheck = password_verify($pass, $DB["pwdUsers"]);
if (!$pwdCheck) {
    $error->err("API", 21, "wrong credentials given");
    die();
}
$api_key = $un_ravel->_queryUser($DB["idusers"], 5);
$chat_key = $un_ravel->_queryUser($DB["idusers"], 2);
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$link = explode('/', $_SERVER["REQUEST_URI"]);
$actual_link .= "/$link[1]" . "/img/" . $DB['profile_picture'];
$result = [
    'username' => $DB['uidusers'],
    'profile_picture' => $actual_link,
    'full_name' => '' . $DB["usersFirstname"] . ' ' . $DB["usersSecondname"],
    'user_token' => $api_key,
    'chat_key' => $chat_key,
];
print_r(json_encode($result));
