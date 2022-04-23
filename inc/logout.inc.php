<?php

session_start();
session_unset();
session_destroy();
if(isset($_GET['acc_deleted'])) {
header('Location: ../login.php?acc_deleted');
exit();
}
// delete the cookie
setcookie('token', '', time() - 3600, '/');

header('Location: ../login.php');  
