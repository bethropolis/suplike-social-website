<?php
if (isset($_GET["login"])) {
    $name = $_POST["userEmail"];
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
        'api_key' => $api_key,
        'chat_key' => $chat_key,
    ];
    print_r(json_encode($result));
}
