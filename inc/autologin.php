<?php
require 'dbh.inc.php';
require 'Auth/auth.php';
$auth = new Auth();

if (isset($_GET['login'])) {
    try {
        //code...
        $token = $_COOKIE['token'];

        $id = $un_ravel->_getUser($token);
        if($id){ 
        $sql = "SELECT * FROM users WHERE idusers = '$id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();  
            session_start();
            $_SESSION['userId'] = $row['idusers'];
            $_SESSION['token'] = $token;
            $auth->_queryUser($row['idusers'], 2);
            $_SESSION['chat_token'] = $auth->user;
            $_SESSION['userUid'] = $row['uidusers'];
            $_SESSION['firstname'] = $row['usersFirstname'];
            $_SESSION['lastname'] = $row['usersSecondname'];
            $_SESSION['age'] = $row['usersAge'];
            $_SESSION['profile-pic'] = $row['profile_picture'];
            $_SESSION['isAdmin'] = $row['isAdmin'];
            header("Location: ../home.php?login=success");
        }
    }else{
        header("Location: ./logout.inc.php");
    }
    } catch (\Throwable $th) {
        //throw $th;
        header("Location: ../home.php?login=error");
    }
} else {
    // send to login page
    header("Location: ../login.php?");
}
