<?php
    require 'dbh.inc.php';
    require 'Auth/auth.php';
    require 'Auth/email.php';
if (isset($_POST['signup-submit'])) {
    $username = $_POST['uid'];
    $email = $_POST['mail'];
    $password = $_POST['pwd'];
    $oauth = new Auth();

    if (empty($username) || empty($email) || empty($password)) {
        header("Location: ../signup.php?error=emptyfields&uid=" . $username . "&mail=" . $email);
        exit();
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header("Location: ../signup.php?error=invalidmail&uid");
        exit();
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../signup.php?error=invalidmail&uid=" . $username);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        header("Location: ../signup.php?error=invaliduid&uid=" . $email);
        exit();
    } else {
        $sql = "SELECT uidusers FROM users WHERE uidusers=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../signup.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultcheck = mysqli_stmt_num_rows($stmt);

            if ($resultcheck > 0) {
                header("Location: ../signup.php?error=usertaken&mail=" . $email);
                exit();
            } else {
                // check if email is already in use
                $sql = "SELECT emailusers FROM users WHERE emailusers=?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../signup.php?error=sqlerror");
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    $resultcheck = mysqli_stmt_num_rows($stmt);

                    if ($resultcheck > 0) {
                        header("Location: ../signup.php?error=emailtaken&uid=" . $username);
                        exit();
                    }
                }
                $sql = "INSERT INTO users (uidusers, emailusers, pwdUsers) VALUES (?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../signup.php?error=sqlerror");
                    exit();
                } else {         
                     session_start();
                    $hashedpwd = password_hash($password, PASSWORD_DEFAULT);

                    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedpwd);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    $getId = "SELECT `idusers` FROM `users` WHERE `uidusers`='$username'";
                    $response = (mysqli_fetch_assoc($conn->query($getId)))['idusers'];
                    $outhsql = "INSERT INTO `auth_key` (`user`,`user_auth`,`chat_auth`,`browser_auth`,`token`,`api_key`) VALUES ($response,'$oauth->user_auth','$oauth->chat_auth','$oauth->browser_auth','$oauth->token','$oauth->api_key') ";
                    $conn->query($outhsql);
                    $link = 'http://bethro.alwaysdata.net/inc/Auth/verify.php?id='.$oauth->user_auth;
                    $email_template = "Hey $username, <br> please confirm your email by clicking on the link below: <br> <a href='$link'>Confirm Email</a>";
                    send_email($email,'Suplike: Confirm your email', $email_template);     

                    setcookie('token', $oauth->token, time() + (86400 * 30), "/");
                    $_SESSION['userId'] = $getId;
                    header("Location: ../search.php?q=e&id=$response&token=$oauth->token");
                    exit();
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    header("location: ../signup.php");
    exit();
}



//i will add some more soon