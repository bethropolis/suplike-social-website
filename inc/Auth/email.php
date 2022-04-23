<?php 


function send_email($email, $body, $subject) {
if ( function_exists( 'mail' ) ){
  mail($email, $subject, $body);
}else{
    
}
}
?>
