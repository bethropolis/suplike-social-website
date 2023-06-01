<?php
define('SESSION_UNVERIFY', true);
require '../r.php';
require '../profile.func.php';

$username = isset($_POST["username"]) ? filter_input(INPUT_POST, 'username') : "";
$email =  isset($_POST["email"]) ? filter_input(INPUT_POST, 'email') : "";
$password = isset($_POST["password"]) ? filter_input(INPUT_POST, 'password') : "";
$name = isset($_POST["name"]) ? filter_input(INPUT_POST, 'name') : "";
$oauth = new Auth();
$firstname = null;
$lastname = null;

$username = strtolower($username);

// Check if required data is missing
if (empty($username) || empty($email) || empty($password)) {
    $error->err("API", 22, "missing data in parameter");
    die();
}

// Check if username is valid and does not already exist
if (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9_.-]{3,19}$/", $username)) {
    $error->err("API", 23, "username should be 4 characters long and contain only letters,numbers,underscore and fullstop");
    die();
}

$sql = "SELECT uidusers FROM users WHERE uidusers=? OR emailusers=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$stmt->store_result();
$resultcheck = $stmt->num_rows;

if ($resultcheck > 0) {
    $stmt->bind_result($user);
    $stmt->fetch();
    if ($user == $username) {
        $error->err("API", 24, "username already exists");
    } else {
        $error->err("API", 25, "email already exists");
    }
    die();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error->err("API", 22, "not a valid email");
    die();
}

if (!empty($name)) {
    $name_parts = explode(' ', $name);
    if (count($name_parts) == 2) {
        $firstname = $name_parts[0];
        $lastname = $name_parts[1];
    } else {
        $firstname = $name_parts[0];
    }
}

$start_time = microtime(true);
$prof = saveSvgImage($username);
$end_time = microtime(true);
$elapsed_time = $end_time - $start_time;



$sql = "INSERT INTO users (uidusers, emailusers, pwdUsers, profile_picture, usersFirstname, usersSecondname) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$hashedpwd = password_hash($password, PASSWORD_DEFAULT);
$stmt->bind_param("ssssss", $username, $email, $hashedpwd, $prof, $firstname, $lastname);
$stmt->execute();

$getId = "SELECT `idusers` FROM `users` WHERE `uidusers`='$username'";
$response = (mysqli_fetch_assoc($conn->query($getId)))['idusers'];
$outhsql = "INSERT INTO `auth_key` (`user`,`user_auth`,`chat_auth`,`browser_auth`,`token`,`api_key`) VALUES ($response,'$oauth->user_auth','$oauth->chat_auth','$oauth->browser_auth','$oauth->token','$oauth->api_key') ";
$conn->query($outhsql);

// generate session token
$session_token = create_session_token($response);

$actual_link = BASE_URL . "{$prof}";
$result = [
    'type' => 'success',
    'profile_picture' => $actual_link,
    'username' => $username,
    "full_name" => $name,
    'user_token' => $oauth->token,
    'chat_key' => $oauth->chat_auth,
    'session_token' => $session_token,
    "time" => $elapsed_time
];
print_r(json_encode($result));

$stmt->close();
$conn->close();
