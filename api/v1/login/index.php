<?php
define('SESSION_UNVERIFY', true);
require "../r.php";

if (!isset($_POST["usermail"], $_POST["userpass"])) {
    $error->err("API", 25, "missing information");
    die();
}

$name = $_POST["usermail"];
$pass = $_POST["userpass"];

$stmt = $conn->prepare("SELECT * FROM users WHERE uidusers = ? OR emailusers = ?");
$stmt->bind_param("ss", $name, $name);
$stmt->execute();
$result = $stmt->get_result();

if ($DB = $result->fetch_assoc()) {
    $pwdCheck = password_verify($pass, $DB['pwdUsers']);

    if (!$pwdCheck) {
        $error->err("API", 25, "wrong credentials given");
        die();
    }

    // generate session token
    $session_token = create_session_token($DB["idusers"]);

    $api_key = $un_ravel->_queryUser($DB["idusers"], 5);
    $chat_key = $un_ravel->_queryUser($DB["idusers"], 2);
    $pic =  $DB['profile_picture'];
    $actual_link = BASE_URL."/{$pic}";
    $result = [
        'username' => $DB['uidusers'],
        'profile_picture' => $actual_link,
        'full_name' => $DB["usersFirstname"] . ' ' . $DB["usersSecondname"],
        'user_token' => $api_key,
        'chat_key' => $chat_key,
        'session_token' => $session_token,
    ];
    print_r(json_encode($result));
} else {
    $error->err("API", 25, "user not found");
    die();
}



