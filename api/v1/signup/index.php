<?php
require '../r.php';
$username = $_POST['uid'];
$email = $_POST['mail'];
$password = $_POST['password'];
$oauth = new Auth();

if (empty($username) || empty($email) || empty($password)) {
    $error->err("API", 22, "missing data in parameter");
    die();
} else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    $error->err("API", 22, "not a valid username");
    die();
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error->err("API", 22, "not a valid email");
    die();
} else {
    $sql = "SELECT uidusers FROM users WHERE uidusers=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        $error->err("API", 28, "server error");
        die();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $resultcheck = mysqli_stmt_num_rows($stmt);

        if ($resultcheck > 0) {
            $error->err("API", 24, "username already exists");
            die();
        } else {
            $sql = "INSERT INTO users (uidusers, emailusers, pwdUsers) VALUES (?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                $error->err("API", 28, "server error");
                die();
            } else {
                $hashedpwd = password_hash($password, PASSWORD_DEFAULT);

                mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedpwd);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $getId = "SELECT `idusers` FROM `users` WHERE `uidusers`='$username'";
                $response = (mysqli_fetch_assoc($conn->query($getId)))['idusers'];
                $outhsql = "INSERT INTO `auth_key` (`user`,`user_auth`,`chat_auth`,`browser_auth`,`token`,`api_key`) VALUES ($response,'$oauth->user_auth','$oauth->chat_auth','$oauth->browser_auth','$oauth->token','$oauth->api_key') ";
                $conn->query($outhsql);
                $result = [
                    'type'=> 'success',
                    'username' => $username,
                    'user_token' => $oauth->token,
                    'chat_key' => $oauth->chat_auth,
                ];
                print_r(json_encode($result));
            }
        }
    }
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
