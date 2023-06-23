<?php
require '../dbh.inc.php';
require 'auth.php';
require 'email.php';
require '../extra/notification.class.php';
$notification = new Notification();





if (isset($_GET['id'])) {
    $email_id = $un_ravel->_getUser($_GET['id']);
    $isEmailVerified = $un_ravel->_isEmail_verified($email_id);
    if ($isEmailVerified) {
        $screen_msg = <<<EOT
            <div class="alert alert-success" role="alert">
            <strong>Success!</strong> Email verified.
          </div>

          <a href="home.php">Go to home</a>
         EOT;
        die($screen_msg);
    }
    if ($email_id) {
        $name = $un_ravel->_username($email_id);
        $email_body = <<<EOT
        <html>
        <head>
        <style>
        body{
           padding: 20px;
        }
         h1 {
                    font-size: 24px;
                    font-weight: bold;
                    color: #6c5ce7;
                    text-align: left;
          }
          p {
                    font-size: 16px;
                    color: #666666;
                    text-align: left;
         }
        </style>
        </head>
        <body>
        <h1>Hi $name,</h1>
        <p>Congratulations! Your email has been verified.</p>
        <p>Thank you for joining suplike.</p>
        <p>Best regards,</p>
        <p>suplike</p>
        </body>
        </html>
        EOT;

        $idUsers = $email_id;
        //  update users table set email_verified = 1 where id = $idUsers
        $stmt = mysqli_prepare($conn, "UPDATE users SET email_verified = 1 WHERE idusers = ?");
        mysqli_stmt_bind_param($stmt, "i", $idUsers);
        mysqli_stmt_execute($stmt);
        $sql = "SELECT `emailusers` FROM `users` WHERE `idusers` = '$idUsers'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $email = $row['emailusers'];

        $notification->notify($idUsers, "Your email has been verified");
        send_email($email, "Email Verification", $email_body);
        die(json_encode(array(
            'status' => 'success',
            'message' => 'Email has been verified.'
        )));
    }
}
