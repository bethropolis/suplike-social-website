<?php
if (isset($_POST['login-submit'])) {

    include 'dbh.inc.php';
    require 'Auth/auth.php';

    $mailuid = $_POST['mailuid'];
    $password = $_POST['pwd'];
    $auth = new Auth();

    if (empty($mailuid) || empty($password)) {
        header("Location: ../login.php?error=emptyfields");
        exit();
    } else {
        $sql = "SELECT * FROM users WHERE uidusers=? OR emailusers=?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $check_setup = file_get_contents("./setup/setup.suplike.json");
            $setup_data = json_decode($check_setup);

            if (!$setup_data->setup) {
                header("Location: ../login.php?error=notset");
                exit();
            }

            header("Location: ../login.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $mailuid, $uidusers);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                if ($row['status'] == "blocked") {
                    header("Location: ../login.php?error=disabled");
                    exit();
                }
                $pwdCheck = password_verify($password, $row['pwdUsers']);

                if ($pwdCheck === false) {
                    header("Location: ../login.php?error=wrongpwd");
                    exit();
                } else if ($pwdCheck === true) {

                    session_start();
                    $_SESSION['userId'] = $row['idusers'];
                    $auth->_queryUser($row['idusers'], 1);
                    $_SESSION["token"] = $auth->user;      # check Auth/auth.php to understand
                    $auth->_queryUser($row['idusers'], 2);
                    $_SESSION["chat_token"] = $auth->user;  # check Auth/auth.php to understand
                    $_SESSION['userUid'] = $row['uidusers'];
                    $_SESSION['firstname'] = $row['usersFirstname'];
                    $_SESSION['lastname'] = $row['usersSecondname'];
                    $_SESSION['age'] = $row['usersAge'];
                    $_SESSION['profile-pic'] = $row['profile_picture'];
                    $_SESSION['isAdmin'] = $row['isAdmin'];
                    // set a cookie for the user to remember them for a week called token ($auth->user)
                    if ($_POST['remember']) {
                        setcookie('token', $auth->user, time() + (86400 * 7), '/');
                    }
                    header("Location: ../home.php?login=success");
                    exit();
                } else {
                    header("Location: ../login.php?error=wrongpwd");
                    exit();
                }
            } else if (is_null(mysqli_fetch_assoc($result))) {
                header("Location: ../login.php?noUser");
                exit();
            }
        }
    }
} else {
    header("Location: ../login.php?noUser");
    exit();
}
