<?php
require '../r.php';
$username = $_POST['uid'];
$email = $_POST['mail'];
$password = $_POST['password'];
$oauth = new Auth();

if (empty($username) || empty($email) || empty($password)) {
    $error->err("API", 22, "missing data in parameter");
    die();
}
// username should be 4 characters long and contain only letters,numbers,underscore and fullstop
if (!preg_match("/^[a-zA-Z0-9_.]{4,}$/", $username)) {
    $error->err("API", 23, "username should be 4 characters long and contain only letters,numbers,underscore and fullstop");
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
                // optional infomation if passed
                if (isset($_POST['firstname'])) {
                    $fname = $_POST['firstname'];
                    $sql = "UPDATE users SET usersFirstname=? WHERE uidusers=?";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        $error->err("API", 28, "server error");
                        die();
                    } else {
                        mysqli_stmt_bind_param($stmt, "ss", $fname, $username);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_store_result($stmt);
                    }
                }
                if (isset($_POST['lastname'])) {


                    $lname = $_POST['lastname'];
                    $sql = "UPDATE users SET usersSecondname=? WHERE uidusers=?";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        $error->err("API", 28, "server error");
                        die();
                    } else {
                        mysqli_stmt_bind_param($stmt, "ss", $lname, $username);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_store_result($stmt);
                    }
                }
                if (isset($_POST['age'])) {
                    $age = $_POST['age'];
                    $sql = "UPDATE users SET usersAge=? WHERE uidusers=?";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        $error->err("API", 28, "server error");
                        die();
                    } else {
                        mysqli_stmt_bind_param($stmt, "ss", $age, $username);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_store_result($stmt);
                    }
                }
                if (isset($_POST['bio'])) {
                    $bio = $_POST['bio'];
                    $sql = "UPDATE users SET bio=? WHERE uidusers=?";
                }
                # change gender (boolean)
                if (isset($_POST['gender'])) {
                    $g = $_POST['gender'];
                    $sql = "UPDATE users SET gender=? WHERE uidusers=?";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        $error->err("API", 28, "server error");
                        die();
                    } else {
                        mysqli_stmt_bind_param($stmt, "bs", $g, $username);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_store_result($stmt);
                    }
                }


                $result = [
                    'type' => 'success',
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
