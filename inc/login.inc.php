<?php 

if (isset($_POST['login-submit'])){

    require 'dbh.inc.php' ;

    $mailuid = $_POST['mailuid'];  
    $password = $_POST['pwd'];

 if (empty($mailuid)|| empty($password)){
    header("Location: ../login.php?error=emptyfields");     
    exit();
    
 }else{
     $sql = "SELECT * FROM users WHERE uidusers=? OR emailusers=?;";
     $stmt = mysqli_stmt_init($conn); 

     if (!mysqli_stmt_prepare($stmt, $sql)){
         header("Location: ../login.php?error=sqlerror");  
         exit();
     } else{ 
         mysqli_stmt_bind_param($stmt, "ss", $mailuid, $uidusers);  
         mysqli_stmt_execute($stmt);
         $result = mysqli_stmt_get_result($stmt);
       
       if ($row = mysqli_fetch_assoc($result)){ 
             $pwdCheck = password_verify($password, $row['pwdUsers']); 

             if ($pwdCheck === false){
                header("Location: ../login.php?error=wrongpwd"); 
                exit();
             }else if ($pwdCheck === true){
                 session_start();
                 $_SESSION['userId'] = $row['idusers'];
                 $_SESSION['userUid'] = $row['uidusers']; 
                 $_SESSION['firstname'] = $row['usersFirstname'];
                 $_SESSION['lastname'] = $row['usersSecondname'];
                 $_SESSION['age'] = $row['usersAge']; 
                 $_SESSION['profile-pic'] = $row['profile_picture'];
                 header("Location: ../index.php?login=success");  
                 exit();
             }else{
                header("Location: ../login.php?error=wrongpwd");
                exit(); 
             }
         }else if(is_null(mysqli_fetch_assoc($result))){
              header("Location: ../login.php?noUser");  
              exit(); 
         } 
     }
 }
}else{
    header("Location: ../login.php?noUser");  
    exit(); 
}
