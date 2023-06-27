<?php
define('SESSION_UNVERIFY', true);
require "../r.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $webhook = $_POST['webhook'];
    $password = $_POST['password'];
    $bio = $_POST['bio'];
    $creator = $_SESSION['userId'];
    $oauth = new Auth();
    $iconFile = $_FILES['icon'];
    $iconPath = '../../../img/';

    $firstname = null;
    $lastname = null;

    $username = strtolower($username);
    // Check if the image icon is set and valid
    if (isset($iconFile) && $iconFile['error'] == 0) {
        // Use the uploaded file name
        $iconName = $iconFile['name'];
        // Move the uploaded file to the destination folder
        $iconTempPath = $iconFile['tmp_name'];
        $iconDestination = $iconPath . $iconName;
        move_uploaded_file($iconTempPath, $iconDestination);
    } else {
        // Use a default file name
        $iconName = "default.jpg";
    }

    if (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9_.]{3,19}$/", $username)) {
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

    if (!empty($name)) {
        $name_parts = explode(' ', $name);
        if (count($name_parts) == 2) {
            $firstname = $name_parts[0];
            $lastname = $name_parts[1];
        } else {
            $firstname = $name_parts[0];
        }
    }


    // Insert data into the users table
    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
    $usersSql = "INSERT INTO users (uidusers, emailusers, pwdUsers, usersFirstname, usersSecondname, isBot, profile_picture, bio) 
                VALUES (?, '', ?,?,?, 1, ?, ?)";
    $usersStmt = $conn->prepare($usersSql);
    $usersStmt->bind_param("ssssss", $username, $hashedPwd,$firstname,$lastname ,$iconName, $bio);
    $usersStmt->execute();

    $usersStmt->close();

    $userId = $oauth->_userid($username);
    // Insert data into the bots table
    $botsSql = "INSERT INTO bots (bot_id, userid, webhook) VALUES (?, ?, ?)";
    $botsStmt = $conn->prepare($botsSql);
    $botsStmt->bind_param("iis", $userId, $creator, $webhook);
    $botsStmt->execute();
    $botsStmt->close();

    // Generate session ID for the bot
    $sessionId = create_session_token($userId);

    // Retrieve bot_token and chat_token
    $botToken = $oauth->token;
    $chatToken = $oauth->chat_auth;


    // Insert data into the auth_key table
    $authKeySql = "INSERT INTO auth_key (user, user_auth, chat_auth, browser_auth, token, api_key) 
    VALUES (?, ?, ?, ?, ?, ?)";
    $authKeyStmt = $conn->prepare($authKeySql);
    $authKeyStmt->bind_param("isssss", $userId, $oauth->user_auth, $oauth->chat_auth, $oauth->browser_auth, $oauth->token, $oauth->api_key);
    $authKeyStmt->execute();
    $authKeyStmt->close();

    // Output success response
    $response = array(
        'status' => 'success',
        'message' => 'Bot registration successful.',
        'bot_id' => $userId,
        'session_id' => $sessionId,
        'bot_token' => $botToken,
        'chat_token' => $chatToken
    );
    echo json_encode($response);
} 


if(isset($_POST["block"])){
if($bot->disableBot($_POST["block"], filter_var($_POST['set'], FILTER_VALIDATE_BOOLEAN))){
    $msg = !$_POST['set'] ? "Bot enabled" : "Bot disabled";
    $response = array(
        'status' => 'success',
        'message' => $msg
    );
    echo json_encode($response);
}else{
    $error->err("API", 26, "Could not disable bot" );
}
}