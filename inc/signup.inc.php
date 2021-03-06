<?php

if (isset($_POST['signup-submit'])){
   
 require 'dbh.inc.php' ;

 $username = $_POST['uid'];
 $email = $_POST['mail'];
 $password = $_POST['pwd'];
 $passwordRepeat = $_POST['pwd-repeat'];
 $firstname = $_POST['firstname'];
 $lastname = $_POST['lastname'];
 $age = $_POST['age'];  


if (empty($username)||empty($email)||empty($password)||empty($passwordRepeat)){
    header("Location: ../signup.php?error=emptyfields&uid=".$username."&mail=".$email); 
    exit();
 }

 else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
       header("Location: ../signup.php?error=invalidmail&uid");
       exit(); 
 }
 
 else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    header("Location: ../signup.php?error=invalidmail&uid=".$username); 
   exit();
 }
 
 else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)){
    header("Location: ../signup.php?error=invaliduid&uid=".$email); 
   exit();
 }

 else if ($password !== $passwordRepeat){
    header("Location: ../signup.php?error=passwordcheck&uid=".$username."&mail".$email); 
    exit();
 }else{
    $sql = "SELECT uidusers FROM users WHERE uidusers=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
     header("Location: ../signup.php?error=sqlerror");
     exit(); 
    } else{
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $resultcheck = mysqli_stmt_num_rows($stmt);

        if ($resultcheck > 0){
            header("Location: ../signup.php?error=usertaken&mail=".$email);  
            exit();

        }else{
                 $sql = "INSERT INTO users (uidusers, emailusers, pwdUsers, usersFirstname, usersSecondname, usersAge) VALUES (?, ?, ?, ?, ?,?)";
                 $stmt = mysqli_stmt_init($conn); 
                 if (!mysqli_stmt_prepare($stmt, $sql)){ 
                 header("Location: ../signup.php?error=sqlerror");
                 exit();
        }else{
            $hashedpwd = password_hash($password, PASSWORD_DEFAULT);  
            
            mysqli_stmt_bind_param($stmt, "ssssss", $username, $email, $hashedpwd, $firstname, $lastname, $age);  
            mysqli_stmt_execute($stmt); 
            mysqli_stmt_store_result($stmt);
            echo "<h1>sign up success <h1> <br/> welcome ".$username." please login to your account  <a href='../login.php'>click here</a>"; 
            exit();
        }  
    }
   }  
 }
 mysqli_stmt_close($stmt);
 mysqli_close($conn);

} else{
    header("location: ../signup.php");
        exit();   
}



//i will add some more soon