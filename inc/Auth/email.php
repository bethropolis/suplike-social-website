<?php 


function send_email($email, $subject ,$body) {
$headers  = "From: bethro@alwaysdata.net\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

  mail($email, $subject,$body,$headers);
}
?>
